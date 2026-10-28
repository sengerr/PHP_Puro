// Runs the JavaScript inside the generated Postman collections against a small
// stand-in for Postman's `pm` object. Needs Node 18+ (no packages).
//
//   node tests/collection_scripts.js
//
// Checks: every script parses; the collection pre-request sets sensible dates and a
// RequestReference; each request's date script gives today + N days (Toronto time);
// the capture scripts store the shipment PIN under every variable the request names.
'use strict';
const fs = require('fs');
const path = require('path');
const vm = require('vm');

let pass = 0, fail = 0;
const check = (ok, label, detail = '') => {
  if (ok) { pass++; return; }
  fail++; console.log(`  FAIL: ${label}${detail ? ' (' + detail + ')' : ''}`);
};

// Toronto date, the way Purolator validates it.
const day = (offset) => new Date(Date.now() + offset * 86400000)
  .toLocaleDateString('en-CA', { timeZone: 'America/Toronto' });

// Minimal pm: environment store, response, test(), expect().
function makePm(responseBody, variables = {}) {
  const env = {};
  const pm = {
    variables: { get: (k) => variables[k] },
    environment: { set: (k, v) => { env[k] = v; }, get: (k) => env[k] },
    response: {
      json: () => { if (responseBody === undefined) throw new Error('no body'); return responseBody; },
    },
    test: (name, fn) => { try { fn(); } catch (e) { check(false, 'pm.test: ' + name, e.message); } },
    expect: (v) => ({ to: { be: { a: (t) => { if (typeof v !== t) throw new Error(`${typeof v} is not ${t}`); } } } }),
  };
  return { pm, env };
}

function run(lines, pm) {
  const code = lines.join('\n');
  const ctx = vm.createContext({ pm, console: { log() {} }, Date, String, Object, Array });
  new vm.Script(code).runInContext(ctx);   // throws on a syntax or runtime error
}

for (const label of ['Development', 'Production']) {
  const file = path.join(__dirname, '..', 'collections', `Purolator-REST-${label}.postman_collection.json`);
  const c = JSON.parse(fs.readFileSync(file, 'utf8'));
  console.log(`\n== ${label}`);

  // Collection pre-request.
  const pre = c.event.find((e) => e.listen === 'prerequest').script.exec;
  const { pm: pm0, env: env0 } = makePm();
  try { run(pre, pm0); } catch (e) { check(false, 'collection pre-request runs', e.message); }
  check(env0.Current_Date === day(0), 'Current_Date is today (Toronto)', env0.Current_Date);
  check(env0.manifestDate === day(0), 'manifestDate is today');
  check(env0.manifestDateOld === day(-11), 'manifestDateOld is today-11');
  check(env0.shipmentDateFar === day(15), 'shipmentDateFar is today+15');
  check(/^REQ\d{14}$/.test(env0.RequestReference || ''), 'RequestReference format', env0.RequestReference);
  const wd = new Date(env0.pickupDate + 'T12:00:00Z').getUTCDay();
  check(env0.pickupDate >= day(1) && wd !== 0 && wd !== 6, 'pickupDate is a future weekday', env0.pickupDate);

  let dateScripts = 0, captureScripts = 0, tokenScripts = 0;
  for (const folder of c.item) {
    for (const it of folder.item) {
      const id = `${folder.name} / ${it.name}`;
      for (const ev of it.event || []) {
        const lines = ev.script.exec;
        if (ev.listen === 'prerequest') {
          dateScripts++;
          const m = /today \+ (\d+) day/.exec(lines[0]);
          const { pm, env } = makePm();
          try { run(lines, pm); } catch (e) { check(false, 'date script runs', id + ': ' + e.message); continue; }
          check(!!m && env.Current_Date === day(Number(m[1])), 'Current_Date = today + N', `${id}: ${env.Current_Date}`);
          // The shipDaysAhead override (e.g. 0 = ship today) must win when set.
          if (/shipDaysAhead/.test(lines.join('\n'))) {
            const o = makePm(undefined, { shipDaysAhead: '0' });
            run(lines, o.pm);
            check(o.env.Current_Date === day(0), 'shipDaysAhead=0 ships today', id);
          }
        } else if (ev.listen === 'test' && /access_token/.test(lines.join('\n'))) {
          tokenScripts++;
          const { pm, env } = makePm({ access_token: 'abc' });
          try { run(lines, pm); } catch (e) { check(false, 'token script runs', e.message); continue; }
          check(env.REST_Token === 'abc', 'token script stores REST_Token');
        } else if (ev.listen === 'test') {
          captureScripts++;
          const src = lines.join('\n');
          const vars = JSON.parse(/const PIN_VARS = (\[.*?\]);/.exec(src)[1]);
          const keys = JSON.parse(/const PIN_KEYS = (\[.*?\]);/.exec(src)[1]);

          // A response with the shipment PIN nested the way the REST API returns it.
          let { pm, env } = makePm({ validShipment: true, shipmentDetails: { shipmentPIN: '329000000001', packagePINs: ['329000000002'] } });
          try { run(lines, pm); } catch (e) { check(false, 'capture script runs', id + ': ' + e.message); continue; }
          check(vars.every((v) => env[v] === '329000000001'), 'captures shipmentPIN into every variable', id);

          // No PIN in the response: nothing is overwritten.
          ({ pm, env } = makePm({ error: 'x' }));
          run(lines, pm);
          check(Object.keys(env).length === 0, 'no PIN: leaves variables alone', id);

          // Not JSON at all: must not throw.
          ({ pm, env } = makePm(undefined));
          try { run(lines, pm); check(Object.keys(env).length === 0, 'non-JSON response: leaves variables alone', id); }
          catch (e) { check(false, 'non-JSON response does not throw', id + ': ' + e.message); }

          // Documents and seeds also accept {"pin": {"value": ...}}.
          if (keys.includes('pin')) {
            ({ pm, env } = makePm({ pin: { value: '329000000003' } }));
            run(lines, pm);
            check(vars.every((v) => env[v] === '329000000003'), 'falls back to pin.value', id);
          }
        }
      }
    }
  }
  console.log(`  ${dateScripts} date scripts, ${captureScripts} capture scripts, ${tokenScripts} token script`);
  check(dateScripts > 0 && captureScripts > 0 && tokenScripts === 1, 'found the expected kinds of scripts');
}

console.log(`\n${fail === 0 ? 'OK' : 'FAILED'}: ${pass} checks passed, ${fail} failed`);
process.exit(fail === 0 ? 0 : 1);
