<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteAdmin extends Command
{
    protected $signature = 'app:promote-admin {email : Email address of the existing account}';

    protected $description = 'Grant admin access to an existing user account';

    public function handle(): int
    {
        $user = User::query()->where('email', $this->argument('email'))->first();

        if ($user === null) {
            $this->error('No account was found with that email address.');

            return self::FAILURE;
        }

        if ($user->role === 'admin') {
            $this->info('That account already has admin access.');

            return self::SUCCESS;
        }

        $user->forceFill(['role' => 'admin'])->save();
        $this->info("Admin access granted to {$user->email}.");

        return self::SUCCESS;
    }
}
