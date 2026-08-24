<?php

namespace App\Http\Controllers;

use App\Models\ExitLog;
use App\Models\User;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function verify(Request $request, User $user)
    {
        // 🔥 LOG EXIT
        ExitLog::create([
            'user_id' => $user->id,
            'scanned_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        $user->load('activeDevices.device');

        return response()->json([
            'name' => $user->name,
            'employee_id' => $user->employee_id,
            'department' => $user->department,
            'devices' => $user->activeDevices->map(function ($assignment) {
                return [
                    'type' => $assignment->device->device_type,
                    'brand' => $assignment->device->brand,
                    'model' => $assignment->device->model,
                    'serial' => $assignment->device->serial_number,
                    'imei' => $assignment->device->imei,
                ];
            }),
        ]);
    }
}
