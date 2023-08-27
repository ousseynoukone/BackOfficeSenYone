<?php

use Database\Seeders\UsersTableSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Artisan::call('db:seed', [
            '--class' => UsersTableSeeder::class,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        (new UsersTableSeeder())->reverse();
    }
};
