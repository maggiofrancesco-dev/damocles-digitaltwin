<?php

/**
 * Author: Francesco Maggio
 */

namespace App\Helpers;

class HumanFactorHelper
{
    public static function mergeHumanFactors(array ...$factors): array
    {
        $totals = [];
        $counts = [];

        foreach ($factors as $factorSet) {
            foreach ($factorSet as $key => $value) {
                if (!is_numeric($value)) continue;

                $totals[$key] = ($totals[$key] ?? 0) + $value;
                $counts[$key] = ($counts[$key] ?? 0) + 1;
            }
        }

        $averaged = [];
        foreach ($totals as $key => $sum) {
            $averaged[$key] = round($sum / $counts[$key], 2);
        }

        return $averaged;
    }
}
