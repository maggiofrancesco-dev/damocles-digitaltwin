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
        Schema::create('ethical_phishing_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('state',['Draft','Ready','Ongoing', 'Finished'])->default('Draft');
            $table->string('subject'); // subject of the email
            $table->longText('content'); // email body content
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('llm_id')->constrained('llms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethical_phishing_campaigns');
    }
};
