<?php

namespace App\Livewire\Admin\Users;

use App\Models\Company;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public User $user;

    public $employee_id;
    public $name;
    public $email;
    public $password;
    public $username;
    public $status = 'active';
    public $photo;
    public $company_id;
    public $designation_id;
    public $role;

    public $companies = [];
    public $designations = [];

    public function mount(User $user)
    {
        $this->user = $user;

        $this->companies = Company::all();
        $this->designations = Designation::all();

        $this->employee_id = $user->employee_id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->status = $user->status;
        $this->company_id = $user->company_id;
        $this->designation_id = $user->designation_id;
        $this->role =
    $user->roles->first()?->name ?? 'employee';
    }

    public function save()
    {
        $this->validate([

            'employee_id' => [
                'required',
                Rule::unique('users', 'employee_id')
                    ->ignore($this->user->id),
            ],

            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($this->user->id),
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')
                    ->ignore($this->user->id),
            ],


            'password' => 'nullable|min:6',

            'status' => 'required',
            'role' => 'required',

        ]);

        // // Generate username
        // $nameParts = preg_split('/\s+/', trim($this->name));

        // $baseUsername = strtolower(
        //     substr($this->name, 0, 1) .
        //     end($nameParts)
        // );

        // $username = $baseUsername;

        // $counter = 1;

        // // Ensure unique username
        // while (
        //     User::where('username', $username)
        //         ->where('id', '!=', $this->user->id)
        //         ->exists()
        // ) {

        //     $username = $baseUsername . $counter;

        //     $counter++;
        // }

        $this->user->update([

            'employee_id' => $this->employee_id,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'designation_id' => $this->designation_id,

        ]);

        $this->user->syncRoles([$this->role]);

        // Update password only if filled
        if ($this->password) {

            $this->user->update([
                'password' => Hash::make($this->password),
            ]);
        }

        session()->flash(
            'message',
            'User updated successfully.'
        );
    }

    public function render()
    {
        return view('livewire.admin.users.edit')
            ->layout('layouts.admin');
    }
}
