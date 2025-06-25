<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalTwinsPrompt extends Model
{
    use HasFactory;

    protected $table = 'digital_twins_prompt';

    protected $fillable = [
        'title',
        'value',
    ];
}
