<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EthicalPhishingCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'state',
        'subject',
        'content',
        'evaluator_id',
        'llm_id'
    ];

    public function digitalTwins(): BelongsToMany
    {
        return $this->belongsToMany(DigitalTwin::class, 'ethical_phishing_campaigns_digital_twins')
                    ->using(EthicalPhishingCampaignsDigitalTwin::class)
                    ->withPivot(['state', 'response']) // optional
                    ->withTimestamps();
    }
}