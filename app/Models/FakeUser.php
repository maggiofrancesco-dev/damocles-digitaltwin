<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Author: Gioele Giannico, Francesco Baldi
 */
class FakeUser extends User
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'gender',
        'dob',
        'company_role',
        'evaluator_id',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // Added Human Factor relationship
    public function humanFactors(): BelongsToMany
    {
        return $this->belongsToMany(HumanFactor::class)
                    ->withPivot('value')
                    ->withTimestamps();
    }

    public function toJson($options = 0): string
    {
        $humanFactorMap = $this->humanFactors->mapWithKeys(function ($factor) {
            return [$factor->factor_name => $factor->pivot->value];
        })->toArray();

        return json_encode([
            'name' => $this->name,
            'surname' => $this->surname,
            'full_name' => $this->fullName(),
            'gender' => $this->gender,
            'age' => $this->age(),
            'role' => $this->company_role,
            'human_factors' => $humanFactorMap,
        ], $options);
    } 
}
