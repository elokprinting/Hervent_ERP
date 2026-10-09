<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SetSystemAdminAccess extends Command
{
    protected $signature = 'admin:system-access {email} {action : grant or revoke}';

    protected $description = 'Grant or revoke system administrator access for an existing user';

    public function handle(): int
    {
        $action = $this->argument('action');

        if (! in_array($action, ['grant', 'revoke'], true)) {
            $this->error('Action must be either grant or revoke.');

            return self::FAILURE;
        }

        $user = User::query()->where('email', $this->argument('email'))->first();

        if ($user === null) {
            $this->error('No user exists with that email address.');

            return self::FAILURE;
        }

        $grantAccess = $action === 'grant';

        if ($user->is_system_admin === $grantAccess) {
            $this->info('No changes are needed.');

            return self::SUCCESS;
        }

        if (! $grantAccess && User::query()->where('is_system_admin', true)->count() === 1) {
            $this->error('The last system administrator cannot be revoked.');

            return self::FAILURE;
        }

        if (! $this->confirm("{$action} system administrator access for {$user->email}?")) {
            $this->info('No changes were made.');

            return self::SUCCESS;
        }

        $user->forceFill(['is_system_admin' => $grantAccess])->save();

        Log::notice('System administrator access changed', [
            'user_id' => $user->getKey(),
            'action' => $action,
        ]);

        $this->info('System administrator access updated.');

        return self::SUCCESS;
    }
}
