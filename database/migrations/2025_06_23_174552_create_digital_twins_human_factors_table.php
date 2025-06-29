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
        Schema::create('digital_twin_human_factor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('digital_twin_id')->constrained()->onDelete('cascade');
            $table->foreignId('human_factor_id')->constrained()->onDelete('cascade');
            $table->integer('value');   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_twin_human_factor');
    }
};
