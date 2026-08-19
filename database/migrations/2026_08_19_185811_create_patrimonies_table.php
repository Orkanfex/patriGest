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
        Schema::create('patrimonies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();   
            $table->text('description');
            $table->string('image')->nullable();
            $table->foreignId('environment_id')
                ->constrained();
            $table->foreignId('state_id')
                ->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrimonies');
    }
};
