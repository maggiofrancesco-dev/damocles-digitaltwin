<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakeUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'gender',
        'dob',
        'company_role',
        'human_factors',
        'evaluator_id',
    ];

    protected $casts = [
        'dob' => 'date',
        'human_factors' => 'array',
    ];

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function fullName(): string
    {
        return $this->name . ' ' . $this->surname;
    }
    
    public function age(): int
    {
        return Carbon::parse($this->dob)->age;
    }
}
