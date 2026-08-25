<div class="max-w-5xl mx-auto space-y-6">

    {{-- Flash message --}}
    @if (session()->has('message'))
        <div class="alert alert-success">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    {{-- MY INFORMATION --}}
    <div id="my-information" class="card bg-base-100 shadow-xl border border-base-300 scroll-mt-20">
        <div class="card-body">

            <h2 class="text-2xl font-bold mb-6">My Information</h2>

            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                {{-- Photo --}}
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

                {{-- Details grid --}}
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
                        <div class="text-xs uppercase opacity-60">Status</div>
                        <div>
                            <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-error' }}">
                                {{ ucfirst($user->status) }}
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

    {{-- MY QR --}}
    <div id="my-qr" class="card bg-base-100 shadow-xl border border-base-300 scroll-mt-20">
        <div class="card-body items-center text-center">

            <h2 class="text-2xl font-bold mb-2">My QR</h2>

            <p class="text-sm opacity-70 mb-4">
                Show this QR to the guard when exiting the building.
            </p>

            <div class="p-4 bg-white rounded-xl border border-base-300 inline-block">
                {!! QrCode::size(220)->generate($qrUrl) !!}
            </div>

            <p class="text-sm opacity-60 mt-4 max-w-sm">
                This QR does not expire. It stays valid until you generate a new one.
            </p>

            <button
                type="button"
                x-data=""
                x-on:click="$dispatch('open-modal', 'confirm-qr-regeneration')"
                class="btn btn-primary mt-4">
                Generate New QR
            </button>

        </div>
    </div>

    {{-- MY DEVICES --}}
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">

            <h2 class="text-2xl font-bold mb-6">My Devices</h2>

            <div class="overflow-x-auto rounded-xl border border-base-300">
                <table class="table table-zebra">

                    <thead class="bg-base-200 text-base-content">
                        <tr>
                            <th>Type</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Serial</th>
                            <th>IMEI</th>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 opacity-60">
                                    No devices assigned
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <x-modal name="confirm-qr-regeneration" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Generate a new QR code?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Your current QR code will stop working immediately. Use this if it becomes
                unreadable, your phone was replaced, or a screenshot of it may have been seen
                by someone else. You'll need to show the new QR the next time you exit.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancel
                </x-secondary-button>

                <button
                    type="button"
                    wire:click="regenerateQr"
                    x-on:click="$dispatch('close')"
                    class="btn btn-primary">
                    Yes, Generate New QR
                </button>
            </div>
        </div>
    </x-modal>

</div>
