<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
        public function up()
    {
        Schema::create('professionnals', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone');
            $table->string('ville');
            $table->string('adresse');
            $table->string('domaine');
            $table->string('service');
            $table->string('email')->unique();
            $table->string('motdepasse');
            $table->string('carte_identite_recto')->nullable();
            $table->string('carte_identite_verso')->nullable();
            $table->boolean('is_patent')->default(false);
            $table->string('image_patent')->nullable();
            $table->timestamps();
        });
    }
    


    public function down(): void
    {
        Schema::dropIfExists('professionnals');
    }
};
