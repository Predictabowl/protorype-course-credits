<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Hash;

class DatabaseSeederAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            "name" => "Amministratore Temporaneo",
            "email" => "admin@email.org",
            "password" => Hash::make("password"),
            "email_verified_at" => now()
        ]);
        
        RoleUser::create([
            "user_id" => $admin->id,
            "role_id" => 1
        ]);
       
    }
}
