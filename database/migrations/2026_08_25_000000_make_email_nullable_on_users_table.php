<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Most employees in the real HR feed have no email on file — email
     * stays unique (MySQL allows multiple NULLs under a unique index) but
     * is no longer required. Login already supports username-or-email.
     *
     * Raw SQL rather than Schema::table(...)->change(): that requires
     * doctrine/dbal, which isn't installed, for a one-column NULL toggle.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL');
    }
};
