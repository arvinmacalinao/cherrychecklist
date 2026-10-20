<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h2 class="text-2xl font-bold">
                {{ $user->name }}
            </h2>

            <p class="text-sm opacity-70">
                User details
            </p>
        </div>

        <div class="flex items-center gap-2">

            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">
                Back
            </a>

            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                Edit
            </a>

            <button
                type="button"
                x-data=""
                x-on:click="$dispatch('open-modal', 'confirm-reset-password')"
                class="btn btn-ghost btn-sm">
                Reset Password
            </button>

            <button
                type="button"
                x-data=""
                x-on:click="$dispatch('open-modal', 'confirm-status-toggle')"
                class="btn btn-sm {{ $user->status === 'active' ? 'btn-error' : 'btn-success' }}">
                {{ $user->status === 'active' ? 'Mark as Inactive' : 'Mark as Active' }}
            </button>

        </div>

    </div>

    {{-- FLASH MESSAGE --}}
    @if (session()->has('message'))
        <div class="alert alert-success">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    {{-- GENERATED PASSWORD (shown once, right after a reset) --}}
    @if ($generatedPassword)
        <div class="alert alert-warning">
            <span>
                Password reset. New password for {{ $user->name }}:
                <span class="font-mono font-bold">{{ $generatedPassword }}</span>
                — copy it now, it won't be shown again. They'll be asked to set
                their own password on next login.
            </span>
        </div>
    @endif

    {{-- USER INFORMATION --}}
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">

            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                @if ($user->photo_path)
                    <img src="{{ asset('storage/' . $user->photo_path) }}"
                         alt="{{ $user->name }}"
                         class="w-28 h-28 rounded-full object-cover border border-base-300" />
                @else
                    <div class="avatar placeholder">
                        <div class="bg-primary text-primary-content rounded-full w-28">
                            <span class="text-3xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 flex-1 w-full">

                    <div>
                        <div class="text-xs uppercase opacity-60">Name</div>
                        <div class="font-semibold">{{ $user->name }}</div>
                    </div>

                    <div>
                        <div class="text-xs uppercase opacity-60">Employee ID</div>
                        <div class="font-semibold">{{ $user->employee_id ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs uppercase opacity-60">Username</div>
                        <div class="font-semibold">{{ $user->username ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs uppercase opacity-60">Email</div>
                        <div class="font-semibold">{{ $user->email ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs uppercase opacity-60">Status</div>
                        <div>
                            <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-error' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs uppercase opacity-60">Role</div>
                        <div>
                            <span class="badge badge-primary badge-sm">
                                {{ optional($user->roles->first())->name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs uppercase opacity-60">Company</div>
                        <div class="font-semibold">{{ $user->company->name ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-xs uppercase opacity-60">Designation</div>
                        <div class="font-semibold">{{ $user->designation->name ?? '-' }}</div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    {{-- ASSIGNED DEVICES --}}
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">

            <h3 class="text-lg font-bold mb-6">Assigned Devices</h3>

            <div class="overflow-x-auto rounded-xl border border-base-300">
                <table class="table table-zebra">

                    <thead class="bg-base-200 text-base-content">
                        <tr>
                            <th>Type</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Serial</th>
                            <th>IMEI</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($user->activeDevices as $assignment)
                            <tr wire:key="device-{{ $assignment->id }}">
                                <td>{{ $assignment->device->device_type }}</td>
                                <td>{{ $assignment->device->brand }}</td>
                                <td>{{ $assignment->device->model }}</td>
                                <td>{{ $assignment->device->serial_number }}</td>
                                <td>{{ $assignment->device->imei ?? '-' }}</td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('admin.devices.edit', $assignment->device) }}"
                                        class="btn btn-xs btn-warning">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 opacity-60">
                                    No devices assigned
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <x-modal name="confirm-status-toggle" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ $user->status === 'active' ? 'Mark this user as inactive?' : 'Mark this user as active?' }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                @if ($user->status === 'active')
                    {{ $user->name }} will no longer be able to log in, and their QR will be
                    rejected at the exit scanner.
                @else
                    {{ $user->name }} will be able to log in again, and their QR will work at
                    the exit scanner.
                @endif
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancel
                </x-secondary-button>

                <button
                    type="button"
                    wire:click="toggleStatus"
                    x-on:click="$dispatch('close')"
                    class="btn {{ $user->status === 'active' ? 'btn-error' : 'btn-success' }}">
                    {{ $user->status === 'active' ? 'Yes, Mark Inactive' : 'Yes, Mark Active' }}
                </button>
            </div>
        </div>
    </x-modal>

    <x-modal name="confirm-reset-password" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Reset {{ $user->name }}'s password?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                A new random password will be generated and shown here once.
                {{ $user->name }} will be required to set their own password
                the next time they log in.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancel
                </x-secondary-button>

                <button
                    type="button"
                    wire:click="resetPassword"
                    x-on:click="$dispatch('close')"
                    class="btn btn-warning">
                    Yes, Reset Password
                </button>
            </div>
        </div>
    </x-modal>

</div>
