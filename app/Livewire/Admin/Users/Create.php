<?php

namespace App\Livewire\Admin\Users;

use App\Models\Company;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $employee_id;
    public $name;
    public $email;
    public $password;
    public $department;
    public $status = 'active';
    public $photo;
    public $company_id;
    public $designation_id;

    public $companies = [];
    public $designations = [];

    public function save()
    {
        $this->validate([
            'employee_id' => 'required|unique:users,employee_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'status' => 'required',
        ]);

        // Generate username: first letter of first name + last word of full name.
        $nameParts = preg_split('/\s+/', trim($this->name));

        $baseUsername = strtolower(
            substr($this->name, 0, 1) .
            end($nameParts)
        );

        $username = User::uniqueUsername($baseUsername);

        $user = User::create([
            'employee_id' => $this->employee_id,
            'username' => $username,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'status' => $this->status,
            'company_id' => $this->company_id,
            'designation_id' => $this->designation_id,
        ]);

        // default employee role
        $user->assignRole('employee');

        session()->flash('message', 'User created successfully.');

        $this->reset([
            'employee_id',
            'name',
            'email',
            'password',
            'status',
            'company_id',
            'designation_id'
        ]);

        $this->status = 'active';
    }

    public function render()
    {
        return view('livewire.admin.users.create')
            ->layout('layouts.admin');
    }

    public function mount()
    {
        $this->companies = Company::all();
        $this->designations = Designation::all();
    }
}
