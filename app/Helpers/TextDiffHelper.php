<?php

namespace App\Helpers;

class TextDiffHelper
{
    /**
     * Compare two multiline strings and return an array of lines with their diff type.
     * Generates a basic diff sequence ('unchanged', 'added', 'removed').
     *
     * @param string $oldText
     * @param string $newText
     * @return array
     */
    public static function diffLines(string $oldText, string $newText): array
    {
        $oldLines = explode("\n", str_replace("\r", "", $oldText));
        $newLines = explode("\n", str_replace("\r", "", $newText));

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
                array_unshift($diff, ['type' => 'unchanged', 'content' => $oldLines[$i - 1]]);
                $i--;
                $j--;
            } elseif ($matrix[$i][$j - 1] > $matrix[$i - 1][$j]) {
                array_unshift($diff, ['type' => 'added', 'content' => $newLines[$j - 1]]);
                $j--;
            } else {
                array_unshift($diff, ['type' => 'removed', 'content' => $oldLines[$i - 1]]);
                $i--;
            }
        }

        while ($i > 0) {
            array_unshift($diff, ['type' => 'removed', 'content' => $oldLines[$i - 1]]);
            $i--;
        }
        while ($j > 0) {
            array_unshift($diff, ['type' => 'added', 'content' => $newLines[$j - 1]]);
            $j--;
        }

        // Filter out empty trailing unchanged lines if texts were both ending in newline
        if (count($diff) > 0 && end($diff)['content'] === '' && end($diff)['type'] === 'unchanged') {
            // It's just a visual artifact, safe to keep or remove, keeping is safer.
        }

        return $diff;
    }
}
