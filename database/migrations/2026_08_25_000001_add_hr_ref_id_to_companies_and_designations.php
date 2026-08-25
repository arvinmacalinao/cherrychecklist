<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Lets the HR sync match a company/designation by HR's own numeric id
     * first (falling back to a case-insensitive name match, which backfills
     * this column) instead of only ever matching by name — a name-only
     * match would create a duplicate row every time HR's naming drifts.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('hr_ref_id')->nullable()->unique()->after('id');
        });

        Schema::table('designations', function (Blueprint $table) {
            $table->unsignedBigInteger('hr_ref_id')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('hr_ref_id');
        });

        Schema::table('designations', function (Blueprint $table) {
            $table->dropColumn('hr_ref_id');
        });
    }
};
