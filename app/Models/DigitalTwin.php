<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalTwin extends User
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'dob',
        'gender',
        'company_role',
        'prompt',
        'evaluator_id',
        'human_factors'
    ];

    protected $casts = [
        'human_factors' => 'array',
    ];
}
