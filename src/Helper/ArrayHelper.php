<?php

declare(strict_types=1);

namespace Priorist\EDM\Helper;

class ArrayHelper
{
    public static function containsArray(array &$array): bool
    {
        foreach ($array as &$item) {
            if (is_array($item)) {
                return true;
            }
        }

        return false;
    }
}
