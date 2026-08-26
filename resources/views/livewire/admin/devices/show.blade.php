<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h2 class="text-2xl font-bold">
                {{ $device->brand }} {{ $device->model }}
            </h2>

            <p class="text-sm opacity-70">
                {{ $device->device_type }} details
            </p>
        </div>

        <div class="flex items-center gap-2">

            <a href="{{ route('admin.devices.index') }}" class="btn btn-ghost btn-sm">
                Back
            </a>

            <a href="{{ route('admin.devices.edit', $device) }}" class="btn btn-warning btn-sm">
                Edit
            </a>

        </div>

    </div>

    {{-- FLASH MESSAGE --}}
    @if (session()->has('message'))
        <div class="alert alert-success">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    {{-- DEVICE INFORMATION --}}
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">

                <div>
                    <div class="text-xs uppercase opacity-60">Device Type</div>
                    <div class="font-semibold">{{ $device->device_type }}</div>
                </div>

                <div>
                    <div class="text-xs uppercase opacity-60">Status</div>
                    <div>
                        <span class="badge {{ match($device->status) {
                            'active' => 'badge-success',
                            'lost' => 'badge-error',
                            default => 'badge-warning',
                        } }}">
                            {{ ucfirst($device->status) }}
                        </span>
                    </div>
                </div>

                <div>
                    <div class="text-xs uppercase opacity-60">Brand</div>
                    <div class="font-semibold">{{ $device->brand }}</div>
                </div>

                <div>
                    <div class="text-xs uppercase opacity-60">Model</div>
                    <div class="font-semibold">{{ $device->model }}</div>
                </div>

                <div>
                    <div class="text-xs uppercase opacity-60">Serial Number</div>
                    <div class="font-mono font-semibold">{{ $device->serial_number }}</div>
                </div>

                <div>
                    <div class="text-xs uppercase opacity-60">IMEI</div>
                    <div class="font-mono font-semibold">{{ $device->imei ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs uppercase opacity-60">Asset Tag</div>
                    <div class="font-semibold">{{ $device->asset_tag ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs uppercase opacity-60">Currently Assigned To</div>
                    <div>
                        @if ($device->activeAssignment?->user)
                            <div class="flex items-center gap-2 mt-1">
                                <div class="avatar placeholder">
                                    <div class="bg-primary text-primary-content rounded-full w-8">
                                        <span class="text-xs">
                                            {{ strtoupper(substr($device->activeAssignment->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-semibold">{{ $device->activeAssignment->user->name }}</div>
                                    <div class="text-xs opacity-60">{{ $device->activeAssignment->user->employee_id ?? '' }}</div>
                                </div>
                            </div>
                        @else
                            <span class="opacity-50">Unassigned</span>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- ASSIGNMENT HISTORY --}}
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">

            <h3 class="text-lg font-bold mb-6">Assignment History</h3>

            <div class="overflow-x-auto rounded-xl border border-base-300">
                <table class="table table-zebra">

                    <thead class="bg-base-200 text-base-content">
                        <tr>
                            <th>Employee</th>
                            <th>Assigned</th>
                            <th>Returned</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($history as $assignment)
                            <tr wire:key="assignment-{{ $assignment->id }}">
                                <td>
                                    <div class="font-semibold">{{ $assignment->user->name ?? 'Unknown' }}</div>
                                    <div class="text-xs opacity-60">{{ $assignment->user->employee_id ?? '-' }}</div>
                                </td>
                                <td>{{ optional($assignment->assigned_at)->format('M d, Y h:i A') ?? '-' }}</td>
                                <td>{{ optional($assignment->returned_at)->format('M d, Y h:i A') ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-sm {{ $assignment->status === 'active' ? 'badge-success' : 'badge-ghost' }}">
                                        {{ ucfirst($assignment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 opacity-60">
                                    No assignment history
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
