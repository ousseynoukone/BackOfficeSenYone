<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {  $user = new User();
       $user->email="admin@admin.com";
       $user->name="Ousseynou Kone";
       $user->password=bcrypt("passer123");
       $user->role="Super Administrateur";

       $user->save();
    }

    public function reverse()
    {
        User::truncate();
    }
}
