<div class="py-6">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded-lg p-6">

            <h1 class="text-xl font-bold mb-4">My Devices</h1>

            <!-- Devices -->
            <table class="w-full border mb-6">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Type</th>
                        <th class="p-2">Brand</th>
                        <th class="p-2">Model</th>
                        <th class="p-2">Serial</th>
                        <th class="p-2">IMEI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($user->activeDevices as $assignment)
                        <tr class="border-t">
                            <td class="p-2">{{ $assignment->device->device_type }}</td>
                            <td class="p-2">{{ $assignment->device->brand }}</td>
                            <td class="p-2">{{ $assignment->device->model }}</td>
                            <td class="p-2">{{ $assignment->device->serial_number }}</td>
                            <td class="p-2">{{ $assignment->device->imei ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-4">
                                No devices assigned
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- QR Section -->
            <div class="text-center">

                <h2 class="font-semibold mb-2">Your Exit QR</h2>

                <div class="flex justify-center mb-3">
                    {!! QrCode::size(200)->generate($qrUrl) !!}
                </div>

                <p class="text-sm text-gray-500 mb-3">
                    Valid for 30 days
                </p>

                <button wire:click="generateQr"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    Generate New QR
                </button>

            </div>

        </div>

    </div>
</div>
