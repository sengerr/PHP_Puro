<?php
/**
 * Minimal YAML reader for the Postman request files in Purolator_2026.
 *
 * It understands only what those files use: nested maps, lists of maps,
 * plain / "double" / 'single' quoted scalars and | / |- block scalars.
 * It is NOT a general YAML parser. Used by tools/convert_yaml.php only.
 */
declare(strict_types=1);

final class MiniYaml
{
    /** @var list<string> */
    private array $lines = [];
    private int $i = 0;

    /** @return array<string,mixed> */
    public static function parse(string $text): array
    {
        $text = preg_replace('/^\xEF\xBB\xBF/', '', $text) ?? $text;   // strip a UTF-8 BOM
        $p = new self();
        $p->lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $text));
        $result = $p->block(0);
        return is_array($result) ? $result : [];
    }

    private function indentOf(string $line): int
    {
        return strlen($line) - strlen(ltrim($line, ' '));
    }

    /** Move past blank lines and comments. */
    private function skipBlank(): void
    {
        while ($this->i < count($this->lines)) {
            $t = trim($this->lines[$this->i]);
            if ($t !== '' && $t[0] !== '#') {
                return;
            }
            $this->i++;
        }
    }

    /** @return array<mixed>|null */
    private function block(int $minIndent): ?array
    {
        $this->skipBlank();
        if ($this->i >= count($this->lines)) {
            return null;
        }
        $line = $this->lines[$this->i];
        $indent = $this->indentOf($line);
        if ($indent < $minIndent) {
            return null;
        }
        return str_starts_with(substr($line, $indent), '- ') ? $this->seq($indent) : $this->map($indent);
    }

    /** @return array<string,mixed> */
    private function map(int $indent): array
    {
        $out = [];
        while (true) {
            $this->skipBlank();
            if ($this->i >= count($this->lines)) {
                break;
            }
            $line = $this->lines[$this->i];
            if ($this->indentOf($line) !== $indent) {
                break;
            }
            $t = substr($line, $indent);
            if (str_starts_with($t, '- ')) {
                break;
            }
            if (!preg_match('/^("(?:[^"\\\\]|\\\\.)*"|[^\s:"][^:]*?):(?:\s+(.*))?$/', $t, $m)) {
                throw new RuntimeException('YAML: cannot read line ' . ($this->i + 1) . ': ' . $line);
            }
            $this->i++;
            $key = $m[1][0] === '"' ? $this->scalar($m[1]) : $m[1];
            $rest = trim($m[2] ?? '');

            if (preg_match('/^[|>][+-]?$/', $rest)) {
                $out[$key] = $this->blockScalar($indent, $rest);
            } elseif ($rest === '') {
                // Nested map or list on the following lines (or an empty value).
                $this->skipBlank();
                $value = null;
                if ($this->i < count($this->lines)) {
                    $next = $this->lines[$this->i];
                    $ni = $this->indentOf($next);
                    $isSameIndentList = $ni === $indent && str_starts_with(substr($next, $ni), '- ');
                    if ($ni > $indent || $isSameIndentList) {
                        $value = $this->block($ni);
                    }
                }
                $out[$key] = $value;
            } else {
                $out[$key] = $this->scalar($this->continuation($rest, $indent));
            }
        }
        return $out;
    }

    /**
     * Join the continuation lines of a multi-line plain or quoted scalar.
     * Lines indented deeper than the key belong to the value (folded with a space).
     */
    private function continuation(string $first, int $keyIndent): string
    {
        $text = $first;
        $quote = ($first !== '' && ($first[0] === '"' || $first[0] === "'")) ? $first[0] : '';
        while ($this->i < count($this->lines)) {
            if ($quote !== '' && $this->closed($text, $quote)) {
                break;
            }
            $line = $this->lines[$this->i];
            if (trim($line) === '') {
                if ($quote === '') {
                    break;   // a blank line ends a plain scalar
                }
                $this->i++;
                continue;
            }
            if ($this->indentOf($line) <= $keyIndent && $quote === '') {
                break;
            }
            $text .= ' ' . trim($line);
            $this->i++;
        }
        return $text;
    }

    /** True when a quoted scalar has its closing quote. */
    private function closed(string $text, string $quote): bool
    {
        return $quote === '"'
            ? (bool)preg_match('/^"(?:[^"\\\\]|\\\\.)*"$/', $text)
            : (bool)preg_match("/^'(?:[^']|'')*'$/", $text);
    }

    /** @return list<mixed> */
    private function seq(int $indent): array
    {
        $out = [];
        while (true) {
            $this->skipBlank();
            if ($this->i >= count($this->lines)) {
                break;
            }
            $line = $this->lines[$this->i];
            if ($this->indentOf($line) !== $indent || !str_starts_with(substr($line, $indent), '- ')) {
                break;
            }
            $content = substr($line, $indent + 2);
            if (preg_match('/^("(?:[^"\\\\]|\\\\.)*"|[^\s:"][^:]*?):(\s|$)/', $content)) {
                // "- key: value" starts a map whose keys line up two columns in.
                $this->lines[$this->i] = str_repeat(' ', $indent + 2) . $content;
                $out[] = $this->map($indent + 2);
            } else {
                $this->i++;
                $out[] = $this->scalar(trim($content));
            }
        }
        return $out;
    }

    private function blockScalar(int $parentIndent, string $style): string
    {
        $buf = [];
        $blockIndent = null;
        while ($this->i < count($this->lines)) {
            $line = $this->lines[$this->i];
            if (trim($line) === '') {
                $buf[] = '';
                $this->i++;
                continue;
            }
            $ind = $this->indentOf($line);
            if ($ind <= $parentIndent) {
                break;
            }
            $blockIndent ??= $ind;
            $buf[] = substr($line, min($blockIndent, $ind));
            $this->i++;
        }
        while ($buf && end($buf) === '') {
            array_pop($buf);   // trailing blank lines belong to the gap, not the value
        }
        $text = implode("\n", $buf);
        return str_ends_with($style, '-') ? $text : $text . "\n";
    }

    private function scalar(string $s): string
    {
        $s = trim($s);
        if ($s === '') {
            return '';
        }
        if ($s[0] === '"' && str_ends_with($s, '"') && strlen($s) >= 2) {
            // YAML double-quoted strings are JSON-compatible for the escapes used here.
            $decoded = json_decode($s);
            return is_string($decoded) ? $decoded : substr($s, 1, -1);
        }
        if ($s[0] === "'" && str_ends_with($s, "'") && strlen($s) >= 2) {
            return str_replace("''", "'", substr($s, 1, -1));
        }
        return $s;
    }
}
