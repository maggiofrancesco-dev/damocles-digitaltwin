<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalTwin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prompt',
        'evaluator_id',
        'human_factors'
    ];

    protected $casts = [
        'human_factors' => 'array',
    ];

    /**
     * Define a one-to-one relationship with User model representing the real user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
