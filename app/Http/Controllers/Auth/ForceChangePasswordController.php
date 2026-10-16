<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Where EnsurePasswordIsChanged redirects anyone whose account has
 * must_change_password set — a new HR-synced account, an admin-created
 * account, or one an admin just reset. Reachable only while that flag is
 * set; the middleware sends them straight back to their dashboard once
 * it's cleared below.
 */
class ForceChangePasswordController extends Controller
{
    public function create(): View
    {
        return view('auth.force-change-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ])->save();

        return $request->user()->dashboardRedirect()
            ->with('status', 'Password updated.');
    }
}
