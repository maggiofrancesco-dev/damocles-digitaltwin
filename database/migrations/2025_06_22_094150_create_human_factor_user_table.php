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
        Schema::create('human_factor_user', function (Blueprint $table) {
            //$table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('human_factor_id')->constrained()->onDelete('cascade');
            $table->integer('value');   
            $table->timestamps();
            $table->primary(['user_id', 'human_factor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('human_factor_user');
    }
};
