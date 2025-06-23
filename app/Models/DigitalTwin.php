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
    ];

    /**
     * Get the user that owns this digital twin.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
