<?php

namespace App\Helpers;

use App\Models\HumanFactor;
use Illuminate\Support\Collection;

class HumanFactorHelper
{
    /**
     * Merge multiple human‐factor sets by averaging their values.
     *
     * @param  Collection<int, HumanFactor> ...$factors
     * @return Collection<HumanFactor>
     */
    public static function mergeHumanFactors(Collection ...$factors): Collection
    {
        $totals = collect();
        $counts = collect();

        // Accumulate totals and counts per factor_name
        foreach ($factors as $factorSet) {
            foreach ($factorSet as $model) {
                if (!isset($model->factor_name) || !isset($model->pivot->value)) {
                    continue;
                }

                $key = $model->factor_name;
                $value = $model->pivot->value;

                if (!is_numeric($value)) {
                    continue;
                }

                $totals[$key] = $totals->get($key, 0) + $value;
                $counts[$key] = $counts->get($key, 0) + 1;
            }
        }

        // Get distinct models and attach pivot values
        $models = HumanFactor::whereIn('factor_name', $totals->keys())->get();

        $models->each(function ($model) use ($totals, $counts) {
            $name = $model->factor_name;
            $avg = round($totals[$name] / $counts[$name], 2);
            $model->pivot = (object)['value' => $avg];
        });

        return $models;
    }
}
