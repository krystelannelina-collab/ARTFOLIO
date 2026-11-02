<?php

namespace Database\Seeders;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => AuthController::DEFAULT_ADMIN_EMAIL],
            [
                'name' => AuthController::DEFAULT_ADMIN_NAME,
                'password' => Hash::make(AuthController::DEFAULT_ADMIN_PASSWORD),
                'role' => 'admin',
            ],
        );

        User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'client',
            ],
        );

        $this->call(ArtworkSeeder::class);
    }
}
