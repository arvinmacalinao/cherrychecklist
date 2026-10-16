<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Company;
use App\Models\Designation;
use Illuminate\Console\Command;
use App\Services\HrEmployeeService;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class SyncEmployees extends Command
{
    protected $signature = 'sync:employees';

    protected $description = 'Sync employees from HR system';

    public function handle(HrEmployeeService $hrService)
    {
        $employees = $hrService->fetchEmployees();

        $synced = 0;
        $skipped = 0;

        foreach ($employees as $employee) {

            // The HR API also returns system/service accounts (e.g. a bare
            // "admin" record) with no employee_id — that column is NOT
            // NULL/UNIQUE locally and is our sync key, so these can't
            // become real accounts. Email, unlike employee_id, is optional
            // (most real employees in this feed have none on file).
            if (empty($employee['employee_id'])) {
                $skipped++;

                continue;
            }

            try {
                $this->syncEmployee($employee);
                $synced++;
            } catch (Throwable $e) {
                $skipped++;
                $this->warn("Skipped employee_id [{$employee['employee_id']}]: {$e->getMessage()}");
                report($e);
            }
        }

        $this->info("Employees synced successfully. Synced: {$synced}, skipped: {$skipped}.");
    }

    private function syncEmployee(array $employee): void
    {
        $company = $this->resolveNamedLookup(
            Company::class,
            $employee['company']['id'] ?? null,
            $employee['company']['name'] ?? null,
        );

        $designation = $this->resolveNamedLookup(
            Designation::class,
            $employee['designation']['id'] ?? null,
            $employee['designation']['name'] ?? null,
        );

        // No explicit active/inactive flag from HR, but date_separated is a
        // reliable signal: set means the employee has left.
        $status = empty($employee['date_separated']) ? 'active' : 'inactive';

        $existingUser = User::where('employee_id', $employee['employee_id'])->first();
        $isNewUser = ! $existingUser;

        // HR's per-employee username isn't guaranteed globally unique
        // (distinct people can compute the same base username), and that
        // column is UNIQUE locally — resolve collisions the same way the
        // admin "Create User" form does, excluding this employee's own
        // existing row so re-syncing them doesn't suffix their own username.
        $username = User::uniqueUsername($employee['username'], $existingUser?->id);

        $values = [
            'name' => $employee['name'],
            'username' => $username,
            'company_id' => $company?->id,
            'designation_id' => $designation?->id,
            'status' => $status,
        ];

        // HR frequently sends no email at all. Only ever set it here when HR
        // actually provides one, so a sync never clobbers back to null an
        // email an admin filled in by hand for an employee HR has none for.
        if (! empty($employee['email'])) {
            $values['email'] = $employee['email'];
        }

        if ($isNewUser) {
            // HR never sends a password. New accounts start with their
            // employee_id as a temporary password (the 'hashed' cast on
            // User::password hashes this automatically on save); existing
            // accounts are never touched here. Since employee_id is
            // predictable, the account is forced to set a real password
            // on first login.
            $values['password'] = $employee['employee_id'];
            $values['must_change_password'] = true;
        }

        $user = User::updateOrCreate(
            ['employee_id' => $employee['employee_id']],
            $values
        );

        if (! $user->roles()->exists()) {
            $user->assignRole('employee');
        }
    }

    /**
     * ID-first, name-fallback lookup shared by Company/Designation. A name
     * match backfills hr_ref_id, so the next sync for this record goes
     * straight through the id path even if HR's naming drifts afterward.
     *
     * @param  class-string<Model>  $modelClass
     */
    private function resolveNamedLookup(string $modelClass, ?int $hrRefId, ?string $name): ?Model
    {
        if (! $hrRefId && ! $name) {
            return null;
        }

        if ($hrRefId) {
            $match = $modelClass::where('hr_ref_id', $hrRefId)->first();

            if ($match) {
                return $match;
            }
        }

        if ($name) {
            $match = $modelClass::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();

            if ($match) {
                if ($hrRefId && ! $match->hr_ref_id) {
                    $match->update(['hr_ref_id' => $hrRefId]);
                }

                return $match;
            }
        }

        return $modelClass::create([
            'hr_ref_id' => $hrRefId,
            'name' => $name,
        ]);
    }
}
