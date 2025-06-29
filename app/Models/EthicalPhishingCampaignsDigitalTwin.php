<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EthicalPhishingCampaignsDigitalTwin extends Pivot
{
    use HasFactory;

    protected $table = 'ethical_phishing_campaigns_digital_twins';

    protected $fillable = [
        'ethical_phishing_campaign_id',
        'digital_twin_id',
        'state',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];

    // Relationships
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(EthicalPhishingCampaign::class, 'ethical_phishing_campaign_id');
    }

    public function digitalTwin(): BelongsTo
    {
        return $this->belongsTo(DigitalTwin::class, 'digital_twin_id');
    }
}
