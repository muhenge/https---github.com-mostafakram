<?php

namespace App\Helpers;

class NextHelper
{
    public function getNextElementsFromArray($arr, $element)
    {
        $startIndex = array_search($element, $arr);

        if ($startIndex === false) {
            return [];
        }

        $nextElements = array_slice($arr, $startIndex + 1);

        return $nextElements;
    }
}




