<div class="max-w-7xl mx-auto">
<div class="card bg-base-100 shadow-xl border border-base-300">

    <div class="card-body">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

            <div>
                <h2 class="text-2xl font-bold">
                    Users
                </h2>

                <p class="text-sm opacity-70">
                    Manage employees and accounts
                </p>
            </div>

            <div class="flex items-center gap-2">

                <a
                    href="{{ route('admin.user.create') }}"
                    class="btn btn-primary btn-sm">

                    + Create User

                </a>

                <livewire:admin.sync-employees-button />

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
                    class="bg-base-100 select select-bordered select-lg w-24">

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
                placeholder="Search users..."
                class="input input-bordered bg-base-100 input-lg w-full md:w-72" />

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto rounded-xl border border-base-300">

            <table class="table table-zebra">

                <thead class="bg-base-200 text-base-content">

                    <tr>
                        <th class="w-16">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Designation</th>
                        <th>Company</th>
                        <th class="text-center">Status</th>
                        <th class="text-center w-32">Actions</th>
                    </tr>

                </thead>

                <tbody wire:key="users-page-{{ $users->currentPage() }}-{{ $perPage }}">

                    @forelse ($users as $user)

                        <tr wire:key="user-{{ $user->id }}">

                            <td>
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>

                            <td>
                                <div class="flex items-center gap-3">
                                    <div>
                                        <div class="font-semibold">
                                            {{ $user->name }}
                                        </div>

                                        <div class="text-xs opacity-60">
                                            {{ $user->employee_id ?? 'No Employee ID' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                {{ $user->email ?? '-' }}
                            </td>

                            <td>
                                <div class="badge badge-primary badge-sm">
                                    {{ optional($user->roles->first())->name ?? 'N/A' }}
                                </div>
                            </td>

                            <td>
                                {{ $user->designation->name ?? '-' }}
                            </td>

                            <td>
                                {{ $user->company->name ?? '-' }}
                            </td>

                            <td class="text-center">
                                <div class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-error' }} badge-sm">
                                    {{ ucfirst($user->status) }}
                                </div>
                            </td>

                            <td>

                                <div class="flex items-center justify-center gap-2">

                                    <a class="btn btn-xs btn-info" href="{{ route('admin.users.show', $user) }}">
                                        View
                                    </a>

                                    <a class="btn btn-xs btn-warning" href="{{ route('admin.users.edit', $user) }}">
                                        Edit
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center py-10">

                                <div class="flex flex-col items-center gap-2 opacity-60">

                                    <span class="text-lg font-semibold">
                                        No users found
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
                <span class="font-semibold">{{ $users->firstItem() ?? 0 }}</span>
                to
                <span class="font-semibold">{{ $users->lastItem() ?? 0 }}</span>
                of
                <span class="font-semibold">{{ $users->total() }}</span>
                users

            </div>

            <div>
                {{ $users->links() }}
            </div>

        </div>

    </div>

</div>
</div>
