<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_device_assignments', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index(['device_id', 'status']);
        });

        Schema::table('exit_logs', function (Blueprint $table) {
            $table->index('scanned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_device_assignments', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['device_id', 'status']);
        });

        Schema::table('exit_logs', function (Blueprint $table) {
            $table->dropIndex(['scanned_at']);
        });
    }
};
