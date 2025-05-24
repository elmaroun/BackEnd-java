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
            Schema::create('travaux', function (Blueprint $table) {
                $table->id();
                $table->foreignId('professionnal_id')->constrained()->onDelete('cascade'); // links to 'service' table
                $table->string('description');
                $table->enum('type', ['image', 'name']); 
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('service_offered');
    }
};
