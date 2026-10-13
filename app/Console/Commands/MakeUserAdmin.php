<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('user:make-admin {email : The email address of an existing user}')]
#[Description('Grant administrator access to an existing user')]
class MakeUserAdmin extends Command
{
    public function handle(): int
    {
        $user = User::query()
            ->where('email', $this->argument('email'))
            ->first();

        if ($user === null) {
            $this->error('No user exists with that email address.');

            return self::FAILURE;
        }

        $user->forceFill(['role' => User::ROLE_ADMIN])->save();

        $this->info("{$user->email} can now access the admin page.");

        return self::SUCCESS;
    }
}
