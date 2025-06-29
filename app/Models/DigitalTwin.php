<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Author: Gioele Giannico
 */
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
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function ethicalPhishingCampaigns(): BelongsToMany
    {
        return $this->belongsToMany(EthicalPhishingCampaign::class, 'ethical_phishing_campaigns_digital_twins')
                    ->using(EthicalPhishingCampaignsDigitalTwin::class)
                    ->withPivot(['state', 'response']) // optional
                    ->withTimestamps();
    }

    // Added Human Factor relationship
    public function humanFactors(): BelongsToMany
    {
        return $this->belongsToMany(HumanFactor::class)
                    ->withPivot('value')
                    ->withTimestamps();
    }
}
