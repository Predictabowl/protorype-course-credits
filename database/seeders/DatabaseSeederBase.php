<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Support\Seeders\GenerateSSD;

class DatabaseSeederBase extends Seeder
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
        $admin->email_verified_at = now();
        
        Role::create([
            "id" => 1,
            "name" => "admin"
        ]);
        
        Role::create([
            "id" => 2,
            "name" => "supervisor"
        ]);
        
        RoleUser::create([
            "user_id" => 1,
            "role_id" => 1
        ]);
        
        GenerateSSD::createAll();
       
    }
}
