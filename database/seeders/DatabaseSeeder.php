<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Support\Seeders\GenerateSSD;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            "id" => 1,
            "name" => Role::ADMIN
        ]);
        
        Role::create([
            "id" => 2,
            "name" => Role::SUPERVISOR
        ]);
        
        GenerateSSD::createAll();
        
        $this->call(DatabaseSeederAmministrazioneDigitale::class);
        $this->call(DatabaseSeederDirittoAgroalimentare::class);
    }
}
