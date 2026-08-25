<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The scanner/exit kiosk no longer requires authentication (it's a public
     * tablet at the exit gate), so the "guard" role has no purpose. Any user
     * still holding it falls back to "employee" so no account is left roleless.
     */
    public function up(): void
    {
        $guardRole = Role::where('name', 'guard')->first();

        if (! $guardRole) {
            return;
        }

        foreach ($guardRole->users as $user) {
            if (! $user->hasRole('employee')) {
                $user->assignRole('employee');
            }
        }

        $guardRole->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Role::firstOrCreate([
            'name' => 'guard',
            'guard_name' => 'web',
        ]);
    }
};
