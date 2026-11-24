<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreatePlatformAdmin extends Command
{
    protected $signature = 'platform:admin:create {email : E-mail for the platform administrator account}';

    protected $description = 'Create a dedicated platform administrator account';

    public function handle(): int
    {
        $email = strtolower($this->argument('email'));
        $emailValidation = Validator::make(['email' => $email], [
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ]);

        if ($emailValidation->fails()) {
            $this->error($emailValidation->errors()->first('email'));

            return self::FAILURE;
        }

        $password = $this->secret('Choose a password (minimum 12 characters)');
        $passwordValidation = Validator::make(['password' => $password], [
            'password' => ['required', Password::min(12)],
        ]);

        if ($passwordValidation->fails()) {
            $this->error($passwordValidation->errors()->first('password'));

            return self::FAILURE;
        }

        if ($password !== $this->secret('Confirm the password')) {
            $this->error('The password confirmation does not match.');

            return self::FAILURE;
        }

        $user = User::create([
            'name' => 'Administrateur plateforme',
            'email' => $email,
            'password' => $password,
        ]);
        $user->forceFill([
            'is_platform_admin' => true,
            'email_verified_at' => now(),
        ])->save();

        $this->info("Dedicated platform administrator account created for {$email}.");

        return self::SUCCESS;
    }
}
