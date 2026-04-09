<?php

namespace App\Helpers;

class TextDiffHelper
{
    /**
     * Compare two multiline strings and return an array of lines with their diff type.
     * Generates a basic diff sequence ('unchanged', 'added', 'removed').
     */
    public static function diffLines(string $oldText, string $newText): array
    {
        $oldLines = explode("\n", str_replace("\r", '', $oldText));
        $newLines = explode("\n", str_replace("\r", '', $newText));

        $matrix = [];
        $oldLen = count($oldLines);
        $newLen = count($newLines);

        for ($i = 0; $i <= $oldLen; $i++) {
            $matrix[$i][0] = 0;
        }
        for ($j = 0; $j <= $newLen; $j++) {
            $matrix[0][$j] = 0;
        }

        for ($i = 1; $i <= $oldLen; $i++) {
            for ($j = 1; $j <= $newLen; $j++) {
                if ($oldLines[$i - 1] === $newLines[$j - 1]) {
                    $matrix[$i][$j] = $matrix[$i - 1][$j - 1] + 1;
                } else {
                    $matrix[$i][$j] = max($matrix[$i][$j - 1], $matrix[$i - 1][$j]);
                }
            }
        }

        $diff = [];
        $i = $oldLen;
        $j = $newLen;

        while ($i > 0 && $j > 0) {
            if ($oldLines[$i - 1] === $newLines[$j - 1]) {
                $diff[] = ['type' => 'unchanged', 'content' => $oldLines[$i - 1]];
                $i--;
                $j--;
            } elseif ($matrix[$i][$j - 1] > $matrix[$i - 1][$j]) {
                $diff[] = ['type' => 'added', 'content' => $newLines[$j - 1]];
                $j--;
            } else {
                $diff[] = ['type' => 'removed', 'content' => $oldLines[$i - 1]];
                $i--;
            }
        }

        while ($i > 0) {
            $diff[] = ['type' => 'removed', 'content' => $oldLines[$i - 1]];
            $i--;
        }
        while ($j > 0) {
            $diff[] = ['type' => 'added', 'content' => $newLines[$j - 1]];
            $j--;
        }

        return array_reverse($diff);
    }
}
