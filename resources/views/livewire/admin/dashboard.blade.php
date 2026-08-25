<div class="max-w-7xl mx-auto space-y-6">

    <div>
        <h2 class="text-2xl font-bold">Dashboard</h2>
        <p class="text-sm opacity-70">Overview of employees, devices, and exit activity</p>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    {{-- STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body">
                <span class="text-sm opacity-60">Employees</span>
                <span class="text-3xl font-bold">{{ $stats['employees'] }}</span>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body">
                <span class="text-sm opacity-60">Devices</span>
                <span class="text-3xl font-bold">{{ $stats['devices'] }}</span>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body">
                <span class="text-sm opacity-60">Active Assignments</span>
                <span class="text-3xl font-bold">{{ $stats['active_assignments'] }}</span>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body">
                <span class="text-sm opacity-60">Exits Today</span>
                <span class="text-3xl font-bold">{{ $stats['exits_today'] }}</span>
            </div>
        </div>

    </div>

    {{-- QUICK ACTIONS --}}
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">

            <h3 class="text-lg font-bold mb-4">Quick Actions</h3>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-sm">
                    + Add User
                </a>

                <a href="{{ route('admin.devices.create') }}" class="btn btn-primary btn-sm">
                    + Add Device
                </a>

                <livewire:admin.sync-employees-button />
            </div>

        </div>
    </div>

    {{-- RECENT ACTIVITY --}}
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">Recent Exit Activity</h3>

                <a href="{{ route('admin.logs') }}" class="btn btn-ghost btn-sm">
                    View All
                </a>
            </div>

            <div class="overflow-x-auto rounded-xl border border-base-300">
                <table class="table table-zebra">

                    <thead class="bg-base-200 text-base-content">
                        <tr>
                            <th>Employee</th>
                            <th>Company</th>
                            <th>Time</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($recentLogs as $log)
                            <tr wire:key="recent-log-{{ $log->id }}">
                                <td>
                                    <div class="font-semibold">{{ $log->user->name ?? 'Unknown User' }}</div>
                                    <div class="text-xs opacity-60">{{ $log->user->employee_id ?? '-' }}</div>
                                </td>
                                <td>{{ $log->user->company->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->scanned_at)->format('M d, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-8 opacity-60">
                                    No exit activity yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
