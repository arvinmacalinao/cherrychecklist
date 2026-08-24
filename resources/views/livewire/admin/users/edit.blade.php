<div class="max-w-5xl mx-auto">

    <div class="card bg-base-100 shadow-xl border border-base-300">

        <div class="card-body">

            {{-- HEADER --}}
            <div class="mb-6">

                <h2 class="text-2xl font-bold">
                    Edit User
                </h2>

                <p class="text-sm opacity-70">
                    Update and manage employee account information
                </p>

            </div>

            {{-- SUCCESS --}}
            @if (session()->has('message'))

                <div class="alert alert-success mb-6">

                    <span>
                        {{ session('message') }}
                    </span>

                </div>

            @endif

            {{-- FORM --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- EMPLOYEE ID --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Employee ID
                        </span>
                    </label>

                    <input
                        type="text"
                        wire:model="employee_id"
                        placeholder="Enter employee ID"
                        class="input input-bordered w-full bg-base-100" />

                    @error('employee_id')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- FULL NAME --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Full Name
                        </span>
                    </label>

                    <input
                        type="text"
                        wire:model="name"
                        placeholder="Enter full name"
                        class="input input-bordered w-full bg-base-100" />

                    @error('name')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- EMAIL --}}
                <div class="">

                    <label class="label">
                        <span class="label-text">
                            Username
                        </span>
                    </label>

                    <input
                        type="text"
                        wire:model="username"
                        placeholder="Enter Username"
                        class="input input-bordered w-full bg-base-100" />

                    @error('username')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- EMAIL --}}
                <div class="">

                    <label class="label">
                        <span class="label-text">
                            Email Address
                        </span>
                    </label>

                    <input
                        type="email"
                        wire:model="email"
                        placeholder="Enter email address"
                        class="input input-bordered w-full bg-base-100" />

                    @error('email')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- PASSWORD --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            New Password
                        </span>
                    </label>

                    <input
                        type="password"
                        wire:model="password"
                        placeholder="Leave blank to keep current password"
                        class="input input-bordered w-full bg-base-100" />

                    @error('password')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- STATUS --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Status
                        </span>
                    </label>

                    <select
                        wire:model="status"
                        class="select select-bordered w-full bg-base-100">

                        <option value="active">
                            Active
                        </option>

                        <option value="inactive">
                            Inactive
                        </option>

                    </select>

                    @error('status')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- ROLE --}}
                <div class="md:col-span-2">

                    <label class="label">
                        <span class="label-text">
                            Role
                        </span>
                    </label>

                    <select
                        wire:model="role"
                        class="select select-bordered w-full bg-base-100">

                        <option value="employee">
                            Employee
                        </option>

                        <option value="admin">
                            Admin
                        </option>

                        <option value="guard">
                            Guard
                        </option>

                    </select>

                    @error('role')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- COMPANY --}}
                <div>

                    <label class="label mb-1">

                        <span class="label-text">
                            Company
                        </span>

                    </label>

                    <x-searchable-select
                        id="company-select"
                        wireModel="company_id"
                        :options="$companies"
                        valueField="id"
                        labelField="name"
                        placeholder="Select Company" />

                    @error('company_id')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- DESIGNATION --}}
                <div>

                    <label class="label mb-1">

                        <span class="label-text">
                            Designation
                        </span>

                    </label>

                    <x-searchable-select
                        id="designation-select"
                        wireModel="designation_id"
                        :options="$designations"
                        valueField="id"
                        labelField="name"
                        placeholder="Select Designation" />

                    @error('designation_id')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="flex items-center justify-end gap-2 mt-8">

                <a
                    href="{{ route('admin.users.index') }}"
                    class="btn btn-ghost">

                    Cancel

                </a>

                <button
                    wire:click="save"
                    class="btn btn-primary">

                    Update User

                </button>

            </div>

        </div>

    </div>

</div>
