<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Company;
use App\Models\Designation;
use Illuminate\Console\Command;
use App\Services\HrEmployeeService;

class SyncEmployees extends Command
{
    protected $signature = 'sync:employees';

    protected $description = 'Sync employees from HR system';

    public function handle(HrEmployeeService $hrService)
    {
        $employees = $hrService->fetchEmployees();

        foreach ($employees as $employee) {

            $company = Company::firstOrCreate([
                'name' => $employee['company'],
            ]);

            $designation = Designation::firstOrCreate([
                'name' => $employee['designation'],
            ]);

            $user = User::updateOrCreate(

                [
                    'employee_id' => $employee['employee_id'],
                ],

                [
                    'name' => $employee['name'],
                    'username' => $employee['username'],
                    'email' => $employee['email'],
                    'company_id' => $company->id,
                    'designation_id' => $designation->id,
                    'status' => $employee['status'] ?? 'active',
                    'password' => $employee['password'],
                ]
            );

            // assign employee role if none
            if (! $user->roles()->exists()) {
                $user->assignRole('employee');
            }
        }

        $this->info('Employees synced successfully.');
    }
}
