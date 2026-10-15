<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country_name_prediction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('name_prediction_id')->constrained()->cascadeOnDelete();
            $table->char('country_code', 2);
            $table->decimal('probability', 7, 6);
            $table->timestamps();

            $table->foreign('country_code')->references('code')->on('countries')->cascadeOnUpdate();
            $table->unique(['name_prediction_id', 'country_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_name_prediction');
    }
};
