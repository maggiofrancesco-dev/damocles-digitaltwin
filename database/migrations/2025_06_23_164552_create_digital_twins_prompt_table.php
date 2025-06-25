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
        Schema::create('digital_twins_prompt', function (Blueprint $table) {
            $table->id();
            $table->json('human_factors')->nullable();
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->text('title');
            $table->longtext('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_twins_prompt');
    }
};
