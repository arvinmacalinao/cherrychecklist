<div class="max-w-5xl mx-auto">

    <div class="card bg-base-100 shadow-xl border border-base-300">

        <div class="card-body">

            {{-- HEADER --}}
            <div class="mb-6">

                <h2 class="text-2xl font-bold">
                    Edit Device
                </h2>

                <p class="text-sm opacity-70">
                    Update and manage registered device information
                </p>

            </div>

            {{-- SUCCESS MESSAGE --}}
            @if (session()->has('message'))

                <div class="alert alert-success mb-6">

                    <span>
                        {{ session('message') }}
                    </span>

                </div>

            @endif

            {{-- FORM --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- DEVICE TYPE --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Device Type
                        </span>
                    </label>

                    <select
                        wire:model="device_type"
                        class="select select-bordered w-full bg-base-100">

                        <option value="">
                            Select Device Type
                        </option>

                        <option value="Laptop">
                            Laptop
                        </option>

                        <option value="Desktop">
                            Desktop
                        </option>

                        <option value="Mobile Phone">
                            Mobile Phone
                        </option>

                        <option value="Tablet">
                            Tablet
                        </option>

                        <option value="Smart Watch">
                            Smart Watch
                        </option>

                        <option value="Camera">
                            Camera
                        </option>

                        <option value="External Drive">
                            External Drive
                        </option>

                        <option value="USB Flash Drive">
                            USB Flash Drive
                        </option>

                        <option value="Printer">
                            Printer
                        </option>

                        <option value="Router">
                            Router
                        </option>

                        <option value="Monitor">
                            Monitor
                        </option>

                        <option value="Other">
                            Other
                        </option>

                    </select>

                    @error('device_type')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- BRAND --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Brand
                        </span>
                    </label>

                    <input
                        type="text"
                        wire:model="brand"
                        placeholder="Apple, Samsung, Lenovo..."
                        class="input input-bordered w-full bg-base-100" />

                    @error('brand')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- MODEL --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Model
                        </span>
                    </label>

                    <input
                        type="text"
                        wire:model="model"
                        placeholder="MacBook Pro, iPhone 15..."
                        class="input input-bordered w-full bg-base-100" />

                    @error('model')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- SERIAL --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Serial Number
                        </span>
                    </label>

                    <input
                        type="text"
                        wire:model="serial_number"
                        placeholder="Enter serial number"
                        class="input input-bordered w-full bg-base-100" />

                    @error('serial_number')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- IMEI --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            IMEI
                        </span>
                    </label>

                    <input
                        type="text"
                        wire:model="imei"
                        placeholder="Optional"
                        class="input input-bordered w-full bg-base-100" />

                    @error('imei')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- ASSET TAG --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Asset Tag
                        </span>
                    </label>

                    <input
                        type="text"
                        wire:model="asset_tag"
                        placeholder="Optional"
                        class="input input-bordered w-full bg-base-100" />

                    @error('asset_tag')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                {{-- STATUS --}}
                <div class="md:col-span-2">

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

                        <option value="returned">
                            Returned
                        </option>

                        <option value="lost">
                            Lost
                        </option>

                    </select>

                    @error('status')
                        <span class="text-error text-sm">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

            </div>

            {{-- USER ASSIGNMENT --}}
            <div class="mt-6">

                <label class="label mb-1">

                    <span class="label-text">
                        Assign Device To
                    </span>

                </label>

                <x-searchable-select
                    id="user-select"
                    wireModel="user_id"
                    :options="$users"
                    valueField="id"
                    labelField="name"
                    placeholder="Select Employee"
                    :selected="$user_id" />

                <div class="text-xs opacity-60 mt-2">
                    Leave empty if device is unassigned.
                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="flex items-center justify-end gap-2 mt-8">

                <a
                    href="{{ route('admin.devices.index') }}"
                    class="btn btn-ghost">

                    Cancel

                </a>

                <button
                    wire:click="save"
                    class="btn btn-primary">

                    Update Device

                </button>

            </div>

        </div>

    </div>

</div>
