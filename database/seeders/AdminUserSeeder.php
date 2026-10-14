<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * Bootstraps the first admin so a freshly migrated server is reachable.
 *
 * Login accepts either the username or the email (see LoginRequest), and
 * rejects any account whose status isn't "active" — this satisfies both.
 *
 * Credentials live in config/admin.php. With no ADMIN_PASSWORD set, a random
 * password is generated and printed once, so a server deployed without an
 * .env never ends up with a publicly known admin password.
 *
 * Re-running is safe: an existing admin keeps its password, and only the
 * admin role and active status are re-asserted.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('admin.bootstrap');

        // Normally RoleSeeder has already run, but this keeps
        // `db:seed --class=AdminUserSeeder` working on its own.
        Role::firstOrCreate(['name' => 'admin']);

        $existing = User::where('employee_id', $config['employee_id'])
            ->orWhere('username', $config['username'])
            ->first();

        if ($existing) {
            $existing->forceFill(['status' => 'active'])->save();
            $existing->assignRole('admin');

            $this->command->warn("Admin '{$existing->username}' already exists — password left unchanged, admin role and active status re-asserted.");

            return;
        }

        $password = $config['password'] ?: Str::password(16, symbols: false);

        $admin = User::create([
            'employee_id' => $config['employee_id'],
            'name' => $config['name'],
            'username' => $config['username'],
            'email' => $config['email'],
            'password' => $password,
            'status' => 'active',
        ]);

        $admin->forceFill(['email_verified_at' => now()])->save();

        $admin->assignRole('admin');

        $login = $config['email']
            ? "{$config['username']}  (or {$config['email']})"
            : $config['username'];

        $this->command->info('Admin account created:');
        $this->command->line("  login:    {$login}");
        $this->command->line("  password: {$password}");

        if (! $config['password']) {
            $this->command->warn('  ^ randomly generated — copy it now, it is not stored anywhere else.');
        }
    }
}
