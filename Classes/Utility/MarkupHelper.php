<?php

namespace JPMSchuler\ShowPageEditors\Utility;

class MarkupHelper
{
    protected const FIRST_WORD_PAD = 12;

    public static function convertLinesForOutput($multiLine): array
    {
        $output = [];

        foreach ($multiLine as $line) {
            $output[] = match (substr((string)$line, 0, 3)) {
                'PID' => '<info>' . static::padLine($line) . '</info>',
                'GID' => '<comment>' . static::padLine($line, ' ') . '</comment>',
                'UID' => '<comment>' . static::padLine($line, ' \\') . '</comment>',
                default => $line,
            };
        }
        return $output;
    }

    protected static function padLine($line, $prefix = ''): string
    {
        $firstWord = explode(' ', trim((string)$line))[0];
        $rest = trim(strstr((string)$line, ' '));
        return str_pad($prefix . $firstWord, self::FIRST_WORD_PAD, ' ') . $rest;
    }
}
