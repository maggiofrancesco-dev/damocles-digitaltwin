<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FakeUser extends User
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

    public function toJson($options = 0): string
    {
        return json_encode([
            'name' => $this->name,
            'surname' => $this->surname,
            'full_name' => $this->fullName(),
            'gender' => $this->gender,
            'age' => $this->age(),
            'role' => $this->company_role,
            'human_factors' => $this->human_factors,
        ], $options);
    } 
}
