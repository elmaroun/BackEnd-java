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
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // links to 'service' table
            $table->foreignId('professionnal_id')->constrained()->onDelete('cascade'); // links to 'service' table
            $table->string('location');
            $table->string('description');
            $table->string('date');
            $table->enum('statut', ['En cours', 'Done']); 
            $table->timestamps();
        });
    }
    


    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
