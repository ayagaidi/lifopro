<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Replace numbers in the string that are located between '/24/' and the end of the segment.
     *
     * @param string $string The original string with numbers.
     * @param int $newNumber The number to replace existing numbers with.
     * @return string The modified string with replaced numbers.
     */
   



     public function replaceNumberBetweenSlashes($value, $newNumber) {
        $pattern = '/\/\d+\//'; // Matches the pattern "/number/"
        $replacement = "/$newNumber/";
        return preg_replace($pattern, $replacement, $value);
    }

}
