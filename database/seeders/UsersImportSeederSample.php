<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersImportSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insertUsing(
    [
        'employee_id',
        'name',
        'username',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'status',
        'company_id',
        'designation_id',
    ],
    DB::table('users_old as u1')
        ->whereNotNull('employee_code')
        ->whereRaw('u1.id = (
            SELECT MAX(u2.id)
            FROM users_old u2
            WHERE u2.employee_code = u1.employee_code
        )')
        ->selectRaw("
            employee_code AS employee_id,

            CONCAT(
                first_name,
                IF(middle_name IS NOT NULL AND middle_name != '', CONCAT(' ', middle_name), ''),
                ' ',
                last_name
            ) AS name,

            LOWER(
                CONCAT(
                    COALESCE(
                        NULLIF(username, ''),
                        CONCAT(first_name, last_name),
                        'user'
                    ),
                    id
                )
            ) AS username,

            email,
            NULL AS email_verified_at,
            password,
            remember_token,
            created_at,
            updated_at,
            'active' AS status,
            c_id AS company_id,
            d_id AS designation_id
        ")
);
    }
}
