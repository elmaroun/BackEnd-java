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
        Schema::create('transporteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professionnal_id')->constrained('professionnals')->onDelete('cascade');
            $table->string('image_vehicule');
            $table->string('charge_max');
            $table->string('type_vehicule');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_transporteur');
    }
};
