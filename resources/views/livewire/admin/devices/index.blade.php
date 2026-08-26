<div class="max-w-7xl mx-auto">

    <div class="card bg-base-100 shadow-xl border border-base-300">

        <div class="card-body">

            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                <div>

                    <h2 class="text-2xl font-bold">
                        Devices
                    </h2>

                    <p class="text-sm opacity-70">
                        Manage employee registered devices
                    </p>

                </div>

                <div class="flex items-center gap-2">

                    <a
                        href="{{ route('admin.devices.create') }}"
                        class="btn btn-primary btn-sm">

                        + Add Device

                    </a>

                </div>

            </div>

            {{-- FILTERS --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                <div class="flex items-center gap-2">

                    <span class="text-sm opacity-70">
                        Show
                    </span>

                    <select
                        wire:model.live="perPage"
                        class="select select-bordered select-lg w-24 bg-base-100">

                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>

                    </select>

                    <span class="text-sm opacity-70">
                        entries
                    </span>

                </div>

                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search devices..."
                    class="input input-bordered input-lg w-full md:w-72 bg-base-100" />

            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto rounded-xl border border-base-300">

                <table class="table table-zebra">

                    <thead class="bg-base-200 text-base-content">

                        <tr>

                            <th class="w-16">#</th>

                            <th
                                wire:click="sortBy('device_type')"
                                class="cursor-pointer">

                                Device Type

                            </th>

                            <th
                                wire:click="sortBy('brand')"
                                class="cursor-pointer">

                                Brand

                            </th>

                            <th
                                wire:click="sortBy('model')"
                                class="cursor-pointer">

                                Model

                            </th>

                            <th
                                wire:click="sortBy('serial_number')"
                                class="cursor-pointer">

                                Serial Number

                            </th>

                            <th
                                wire:click="sortBy('imei')"
                                class="cursor-pointer">

                                IMEI

                            </th>

                            <th>Status</th>

                            <th>Assigned To</th>

                            <th class="text-center w-32">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody wire:key="devices-page-{{ $devices->currentPage() }}-{{ $perPage }}">

                        @forelse ($devices as $device)

                            <tr wire:key="device-{{ $device->id }}">

                                <td>
                                    {{ ($devices->currentPage() - 1) * $devices->perPage() + $loop->iteration }}
                                </td>

                                {{-- DEVICE --}}
                                <td>

                                    <div class="font-semibold">
                                        {{ $device->device_type }}
                                    </div>

                                </td>

                                {{-- BRAND --}}
                                <td>
                                    {{ $device->brand }}
                                </td>

                                {{-- MODEL --}}
                                <td>
                                    {{ $device->model }}
                                </td>

                                {{-- SERIAL --}}
                                <td>

                                    <span class="text-xs font-mono">
                                        {{ $device->serial_number }}
                                    </span>

                                </td>

                                {{-- IMEI --}}
                                <td>

                                    <span class="text-xs font-mono">
                                        {{ $device->imei ?? '-' }}
                                    </span>

                                </td>

                                {{-- STATUS --}}
                                <td>

                                    @if ($device->status === 'active')

                                        <div class="badge badge-success badge-sm">
                                            Active
                                        </div>

                                    @elseif ($device->status === 'inactive')

                                        <div class="badge badge-error badge-sm">
                                            Inactive
                                        </div>

                                    @else

                                        <div class="badge badge-ghost badge-sm">
                                            {{ ucfirst($device->status) }}
                                        </div>

                                    @endif

                                </td>

                                {{-- OWNER --}}
                                <td>

                                    @if ($device->activeAssignment?->user)

                                        <div class="flex items-center gap-2">

                                            <div class="avatar placeholder">

                                                <div class="bg-primary text-primary-content rounded-full w-8">

                                                    <span class="text-xs">
                                                        {{ strtoupper(substr($device->activeAssignment->user->name, 0, 1)) }}
                                                    </span>

                                                </div>

                                            </div>

                                            <div>

                                                <div class="font-medium">
                                                    {{ $device->activeAssignment->user->name }}
                                                </div>

                                                <div class="text-xs opacity-60">
                                                    {{ $device->activeAssignment->user->employee_id ?? '' }}
                                                </div>

                                            </div>

                                        </div>

                                    @else

                                        <span class="opacity-50">
                                            Unassigned
                                        </span>

                                    @endif

                                </td>

                                {{-- ACTIONS --}}
                                <td>

                                    <div class="flex items-center justify-center gap-2">

                                        <a class="btn btn-xs btn-info" href="{{ route('admin.devices.show', $device) }}">
                                            View
                                        </a>

                                        <a class="btn btn-xs btn-warning" href="{{ route('admin.devices.edit', $device) }}">
                                            Edit
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="text-center py-10">

                                    <div class="flex flex-col items-center gap-2 opacity-60">

                                        <span class="text-lg font-semibold">
                                            No devices found
                                        </span>

                                        <span class="text-sm">
                                            Try adjusting your search.
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- FOOTER --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-6">

                <div class="text-sm opacity-70">

                    Showing
                    <span class="font-semibold">{{ $devices->firstItem() ?? 0 }}</span>
                    to
                    <span class="font-semibold">{{ $devices->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold">{{ $devices->total() }}</span>
                    devices

                </div>

                <div>
                    {{ $devices->links() }}
                </div>

            </div>

        </div>

    </div>

</div>
