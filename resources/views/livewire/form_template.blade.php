<div class="max-w-5xl mx-auto">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h1 class="card-title">Title</h1>

             @if (session()->has('message'))
                <div class="text-green-600 mb-3">
                    {{ session('message') }}
                </div>
            @endif
            <div class="space-y-3">

                <input type="text" wire:model="device_type" placeholder="Device Type" class="border p-2 w-full">
                <input type="text" wire:model="brand" placeholder="Brand" class="border p-2 w-full">
                <input type="text" wire:model="model" placeholder="Model" class="border p-2 w-full">
                <input type="text" wire:model="serial_number" placeholder="Serial Number" class="border p-2 w-full">
                <input type="text" wire:model="imei" placeholder="IMEI (optional)" class="border p-2 w-full">
                <input type="text" wire:model="asset_tag" placeholder="Asset Tag (if Applicable)" class="border p-2 w-full">

                <div>
                    <label>Assign to (optional)</label>
                    <select wire:model="assigned_user" class="border p-2 w-full">
                        <option value="">Unassigned</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button wire:click="save" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Save Device
                </button>

            </div>


        </div>
    </div>
</div>
