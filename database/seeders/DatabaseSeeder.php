<?php

namespace Database\Seeders;
use Database\Seeders\Organizations\FamilyRelationshipsSeeder;

use App\Models\User;
use Database\Seeders\SuperAdminSeeder;
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
        //====================================

        $this->call([
            FamilyRelationshipsSeeder::class,
        ]);
        //====================================


        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        // $this->call([
        //     RolesAndPermissionsSeeder::class,
        // ]);
        $this->call([
            // RolesAndPermissionsSeeder::class,
            SuperAdminSeeder::class,
        ]);

    }
}
