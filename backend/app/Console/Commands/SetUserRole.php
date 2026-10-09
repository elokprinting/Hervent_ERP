<?php

namespace App\Console\Commands;

use App\Models\User;
use App\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SetUserRole extends Command
{
    protected $signature = 'admin:user-role {email} {role : One of the application roles, or none}';

    protected $description = 'Assign or clear a user role';

    public function handle(): int
    {
        $roleName = $this->argument('role');
        $role = $roleName === 'none' ? null : UserRole::tryFrom($roleName);

        if ($roleName !== 'none' && $role === null) {
            $this->error('Unknown role. Valid roles: '.implode(', ', array_column(UserRole::cases(), 'value')).', none.');

            return self::FAILURE;
        }

        $user = User::query()->where('email', $this->argument('email'))->first();

        if ($user === null) {
            $this->error('No user exists with that email address.');

            return self::FAILURE;
        }

        if ($user->role === $role) {
            $this->info('No changes are needed.');

            return self::SUCCESS;
        }

        $currentRole = $user->role?->value ?? 'none';
        $newRole = $role?->value ?? 'none';

        if (! $this->confirm("Change {$user->email} role from {$currentRole} to {$newRole}?")) {
            $this->info('No changes were made.');

            return self::SUCCESS;
        }

        $user->forceFill(['role' => $role])->save();

        Log::notice('User role changed', [
            'user_id' => $user->getKey(),
            'previous_role' => $currentRole,
            'new_role' => $newRole,
        ]);

        $this->info('User role updated.');

        return self::SUCCESS;
    }
}
