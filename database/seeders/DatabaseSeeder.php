<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(PermisosSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AssignPermissionsToAdminSeeder::class);
        $this->call(CargoSeeder::class);
        $this->call(DepartamentoCategoriaSeeder::class);
        $this->call(DepartamentoSeeder::class);
        $this->call(AdminUserSeeder::class);
    }
}
