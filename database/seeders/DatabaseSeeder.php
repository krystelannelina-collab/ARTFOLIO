<?php

namespace Database\Seeders;

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
        $this->seedAdminAccount();

        User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'username' => 'testuser',
                'password' => Hash::make('password'),
                'role' => 'client',
            ],
        );

        $this->call(ArtworkSeeder::class);
    }

    /**
     * Create or update the first admin from the ARTFOLIO_ADMIN_* settings.
     * Skipped when no password is configured, so no default password exists.
     */
    private function seedAdminAccount(): void
    {
        /** @var array{name: string, email: string, password: ?string} $admin */
        $admin = config('artfolio.admin');

        if (blank($admin['password'])) {
            $this->command?->warn('Skipped the admin account: set ARTFOLIO_ADMIN_PASSWORD in .env and run "php artisan db:seed" again.');

            return;
        }

        User::query()->updateOrCreate(
            ['email' => mb_strtolower($admin['email'])],
            [
                'name' => $admin['name'],
                'password' => Hash::make($admin['password']),
                'role' => 'admin',
            ],
        );
    }
}
