<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Devices\Create as DeviceCreate;
use App\Livewire\Admin\Devices\Edit as DeviceEdit;
use App\Livewire\Admin\Devices\Index as DeviceIndex;
use App\Livewire\Admin\Logs\Index as LogsIndex;
use App\Livewire\Admin\Users\Create as UserCreate;
use App\Livewire\Admin\Users\Edit as UserEdit;
use App\Livewire\Admin\Users\Index as UserIndex;
use App\Livewire\Employee\Dashboard as EmployeeDashboard;
use App\Livewire\Guard\Scanner;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Generic post-login landing spot. RouteServiceProvider::HOME points here, and
// Breeze's "guest" middleware redirects already-authenticated users here too.
// It simply forwards to the correct role-based dashboard.
Route::get('/dashboard', function () {
    return auth()->user()->dashboardRedirect();
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');

    Route::get('/admin/users', UserIndex::class)->name('admin.users.index');
    Route::get('/admin/users/create', UserCreate::class)->name('admin.user.create');
    Route::get('/admin/users/{user}/edit', UserEdit::class)->name('admin.users.edit');

    Route::get('/admin/devices', DeviceIndex::class)->name('admin.devices.index');
    Route::get('/admin/devices/create', DeviceCreate::class)->name('admin.devices.create');
    Route::get('/admin/devices/{device}/edit', DeviceEdit::class)->name('admin.devices.edit');

    Route::get('/admin/logs', LogsIndex::class)->name('admin.logs');
});

Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', EmployeeDashboard::class)->name('employee.dashboard');
});

// Exit-scanner kiosk: a tablet at the exit gate, intentionally not behind login
// so guards can tap through scans quickly during queue rushes. The QR payload
// itself (an opaque, non-expiring token — never the employee_id/email/name) is
// the credential; lookup is by exact match so a regenerated token stops the
// old QR instantly. Throttled since both routes are unauthenticated.
Route::get('/scanner', Scanner::class)->name('scanner');

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/qr/{token}', [QrController::class, 'show'])->name('qr.show');
    Route::post('/qr/{token}/confirm', [QrController::class, 'confirm'])->name('qr.confirm');
});

require __DIR__.'/auth.php';
