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
        Schema::create('trajet_directs', function (Blueprint $table) {
            $table->id();
            $table->string("depart");
            $table->string('tarifs', 1000);

            $table->string("arrive");
            $table->double('departLat'); 
            $table->double('departLon'); 
            $table->double('arriveLat'); 
            $table->double('arriveLon'); 
            $table->json('routeInfo'); 
            $table->json('ligne'); 
            $table->double('distance');
            $table->integer('frequence');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trajet_directs');
    }
};
