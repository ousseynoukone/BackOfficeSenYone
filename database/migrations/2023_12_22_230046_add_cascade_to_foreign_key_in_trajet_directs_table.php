<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeToForeignKeyInTrajetDirectsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trajet_directs', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['ligne_id']);
            
            // Add a new foreign key constraint with ON DELETE CASCADE
            $table->foreign('ligne_id')->references('id')->on('lignes')->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to define the "down" method for this specific case
    }
}
