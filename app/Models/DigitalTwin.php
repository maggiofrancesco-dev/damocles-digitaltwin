<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'dob' => 'date',
        'human_factors' => 'array',
    ];

    public function ethicalPhishingCampaigns(): BelongsToMany
    {
        return $this->belongsToMany(EthicalPhishingCampaign::class, 'ethical_phishing_campaigns_digital_twins')
                    ->using(EthicalPhishingCampaignsDigitalTwin::class)
                    ->withPivot(['state', 'response']) // optional
                    ->withTimestamps();
    }
}
