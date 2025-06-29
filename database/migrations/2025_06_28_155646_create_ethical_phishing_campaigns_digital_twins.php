<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ethical_phishing_campaigns_digital_twins', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ethical_phishing_campaign_id');
            $table->foreign('ethical_phishing_campaign_id', 'epc_dt_campaign_fk')
                ->references('id')
                ->on('ethical_phishing_campaigns')
                ->onDelete('cascade');

            $table->unsignedBigInteger('digital_twin_id');
            $table->foreign('digital_twin_id', 'epc_dt_twin_fk')
                ->references('id')
                ->on('digital_twins')
                ->onDelete('cascade');

            $table->enum('state', ['Waiting', 'Ongoing', 'Success', 'Failed'])
                  ->default('Waiting');

            $table->json('response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethical_phishing_campaigns_digital_twins');
    }
};
