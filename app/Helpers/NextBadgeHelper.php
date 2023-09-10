<?php

namespace App\Helpers;

class NextBadgeHelper {
    public function getNextElement($array, $element)
    {
        $found = false;

        foreach ($array as $key => $value) {
            if ($found) {
                return $value;
            }

            if ($value === $element) {
                $found = true;
            }
        }

        return null;
    }
}
