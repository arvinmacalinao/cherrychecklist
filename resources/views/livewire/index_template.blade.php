<div class="max-w-5xl mx-auto">
    <div class="card bg-base-100 shadow">

        <div class="card-body">

            <h2 class="card-title">Title</h2>

            <div class="flex justify-between mb-4 ">
               <select wire:model.live="perPage" class="select select-bordered w-20 bg-base-100 shadow" >
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="25">25</option>
            </select>

                <input type="text" placeholder="Search"
                    class="input input-bordered w-40 bg-base-100 shadow" wire:model.live="search" />
            </div>

            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>#</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- @forelse ($devices as $device)
                        <tr class="border-t text-center">
                            <td class="text-center">
                                {{ ($devices->currentPage() - 1) * $devices->perPage() + $loop->iteration }}
                            </td>
                            <td>{{ $device->device_type }}</td>
                            <td>{{ $device->brand }}</td>
                            <td>{{ $device->model }}</td>
                            <td>{{ $device->serial_number }}</td>
                            <td>{{ $device->imei ?? '-' }}</td>
                            <td>{{ ucfirst($device->status) }}</td>
                            <td>{{ $device->activeAssignment?->user?->name ?? 'Unassigned' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No devices found</td>
                        </tr>
                    @endforelse --}}
                </tbody>
            </table>

        </div>

    </div>

</div>
