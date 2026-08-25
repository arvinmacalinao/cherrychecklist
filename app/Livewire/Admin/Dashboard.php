<?php

namespace App\Livewire\Admin;

use App\Models\Device;
use App\Models\EmployeeDeviceAssignment;
use App\Models\ExitLog;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'employees' => User::role('employee')->count(),
            'devices' => Device::count(),
            'active_assignments' => EmployeeDeviceAssignment::where('status', 'active')->count(),
            'exits_today' => ExitLog::whereDate('scanned_at', now()->toDateString())->count(),
        ];

        $recentLogs = ExitLog::with(['user.company'])
            ->latest('scanned_at')
            ->limit(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'recentLogs' => $recentLogs,
        ])->layout('layouts.admin');
    }
}
