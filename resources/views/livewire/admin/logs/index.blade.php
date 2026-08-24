<div class="max-w-7xl mx-auto">

    <div class="card bg-base-100 shadow-xl border border-base-300">

        <div class="card-body">

            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                <div>

                    <h2 class="text-2xl font-bold">
                        Exit Logs
                    </h2>

                    <p class="text-sm opacity-70">
                        Monitor employee QR scan activity
                    </p>

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
                    placeholder="Search logs..."
                    class="input input-bordered input-sm w-full md:w-72 bg-base-100" />

            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto rounded-xl border border-base-300">

                <table class="table table-zebra">

                    <thead class="bg-base-200 text-base-content">

                        <tr>

                            <th class="w-16">#</th>

                            <th>Employee</th>

                            <th>Company</th>

                            <th>Designation</th>

                            <th>Scanned Time</th>

                            <th>IP Address</th>

                            <th class="text-center">
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody wire:key="logs-page-{{ $logs->currentPage() }}-{{ $perPage }}">

                        @forelse ($logs as $log)

                            <tr>

                                {{-- NUMBER --}}
                                <td>
                                    {{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}
                                </td>

                                {{-- EMPLOYEE --}}
                                <td>

                                    <div class="flex items-center gap-3">

                                        <div class="avatar placeholder">

                                            <div class="bg-primary text-primary-content rounded-full w-10">

                                                <span>
                                                    {{ strtoupper(substr($log->user->name ?? 'U', 0, 1)) }}
                                                </span>

                                            </div>

                                        </div>

                                        <div>

                                            <div class="font-semibold">
                                                {{ $log->user->name ?? 'Unknown User' }}
                                            </div>

                                            <div class="text-xs opacity-60">
                                                {{ $log->user->employee_id ?? '-' }}
                                            </div>

                                        </div>

                                    </div>

                                </td>

                                {{-- COMPANY --}}
                                <td>
                                    {{ $log->user->company->name ?? '-' }}
                                </td>

                                {{-- DESIGNATION --}}
                                <td>
                                    {{ $log->user->designation->name ?? '-' }}
                                </td>

                                {{-- TIME --}}
                                <td>

                                    <div class="flex flex-col">

                                        <span class="font-medium">
                                            {{ \Carbon\Carbon::parse($log->scanned_at)->format('M d, Y') }}
                                        </span>

                                        <span class="text-xs opacity-60">
                                            {{ \Carbon\Carbon::parse($log->scanned_at)->format('h:i A') }}
                                        </span>

                                    </div>

                                </td>

                                {{-- IP --}}
                                <td>

                                    <span class="font-mono text-xs">
                                        {{ $log->ip_address }}
                                    </span>

                                </td>

                                {{-- STATUS --}}
                                <td class="text-center">

                                    <div class="badge badge-success badge-sm">
                                        Scanned
                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-10">

                                    <div class="flex flex-col items-center gap-2 opacity-60">

                                        <span class="text-lg font-semibold">
                                            No logs found
                                        </span>

                                        <span class="text-sm">
                                            QR scans will appear here.
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
                    <span class="font-semibold">{{ $logs->firstItem() ?? 0 }}</span>
                    to
                    <span class="font-semibold">{{ $logs->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold">{{ $logs->total() }}</span>
                    logs

                </div>

                <div>
                    {{ $logs->links() }}
                </div>

            </div>

        </div>

    </div>

</div>
