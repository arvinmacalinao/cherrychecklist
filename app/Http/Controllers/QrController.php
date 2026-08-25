<?php

namespace App\Http\Controllers;

use App\Models\ExitLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QrController extends Controller
{
    /**
     * Look up an employee by their opaque QR token and return the info the
     * exit-scanner kiosk shows before a guard/staff member confirms the exit.
     * Does NOT log an exit — that only happens in confirm().
     */
    public function show(Request $request, string $token): JsonResponse
    {
        $user = User::where('qr_token', $token)->first();

        if (! $user) {
            return response()->json([
                'ok' => false,
                'message' => 'QR code not recognized.',
            ], 404);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'ok' => false,
                'message' => 'This employee account is not active.',
            ], 403);
        }

        $user->load(['company', 'designation', 'activeDevices.device']);

        return response()->json([
            'ok' => true,
            'token' => $token,
            'employee_id' => $user->employee_id,
            'name' => $user->name,
            'company' => $user->company->name ?? null,
            'designation' => $user->designation->name ?? null,
            'photo_url' => $user->photo_path ? asset('storage/'.$user->photo_path) : null,
            'devices' => $user->activeDevices->map(fn ($assignment) => [
                'type' => $assignment->device->device_type,
                'brand' => $assignment->device->brand,
                'model' => $assignment->device->model,
                'serial' => $assignment->device->serial_number,
                'imei' => $assignment->device->imei,
            ]),
        ]);
    }

    /**
     * Confirm the exit shown by show() and write the audit trail entry.
     * Kept as a separate step so a scan alone never logs an exit.
     */
    public function confirm(Request $request, string $token): JsonResponse
    {
        $user = User::where('qr_token', $token)->first();

        if (! $user || $user->status !== 'active') {
            return response()->json([
                'ok' => false,
                'message' => 'Unable to confirm exit.',
            ], 404);
        }

        ExitLog::create([
            'user_id' => $user->id,
            'scanned_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Exit logged for '.$user->name.'.',
        ]);
    }
}
