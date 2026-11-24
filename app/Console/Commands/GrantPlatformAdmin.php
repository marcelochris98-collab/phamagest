<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GrantPlatformAdmin extends Command
{
    protected $signature = 'platform:admin {email : E-mail of the existing user}';

    protected $description = 'Grant platform administrator access to an existing account';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('No account exists with this e-mail address.');

            return self::FAILURE;
        }

        if (! $this->confirm("Grant platform administrator access to {$user->email}?")) {
            $this->warn('No changes were made.');

            return self::SUCCESS;
        }

        $user->forceFill(['is_platform_admin' => true])->save();

        $this->info('Platform administrator access granted.');

        return self::SUCCESS;
    }
}
