<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Devices\Create as Create;
use App\Livewire\Admin\Devices\Index as DeviceIndex;
use App\Livewire\Admin\Logs\Index as LogsIndex;
use App\Livewire\Admin\Users\Create as UserCreate;
use App\Livewire\Admin\Users\Edit;
use App\Livewire\Admin\Devices\Edit as DeviceEdit;
use App\Livewire\Admin\Users\Index as UserIndex;
use App\Livewire\Employee\Dashboard;
use App\Livewire\Employee\GenerateQr;
use App\Livewire\Guard\Scanner;
use Illuminate\Support\Facades\Http;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:employee'])->group(function () {
    // Route::get('/qr', GenerateQr::class)->name('employee.qr');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', UserIndex::class)->name('admin.users.index');
    Route::get('/admin/users/create', UserCreate::class)->name('admin.user.create');
    Route::get('/admin/devices', DeviceIndex::class)->name('admin.devices.index');
    Route::get('/admin/devices/create', Create::class)->name('admin.devices.create');
    Route::get('/admin/users/{user}/edit', Edit::class)->name('admin.users.edit');
    Route::get('/admin/devices/{device}/edit', DeviceEdit::class)->name('admin.devices.edit');
});


Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', Dashboard::class)->name('employee.dashboard');
});


Route::middleware(['auth', 'role:guard|admin'])->group(function () {
    Route::get('/qr/verify/{user}', [QrController::class, 'verify'])
        ->name('qr.verify')
        ->middleware('signed');
});

Route::middleware(['auth', 'role:guard|admin'])->group(function () {
    Route::get('/scanner', Scanner::class)->name('scanner');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/logs', LogsIndex::class)->name('admin.logs');
});

// admin
Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');

// employee
Route::get('/dashboard', Dashboard::class)->name('employee.dashboard');

// guard
Route::get('/scanner', Scanner::class)->name('scanner');

require __DIR__.'/auth.php';

// $2y$12$zmiEMHTchOLHBx1CcrHVPOvaNiYz7zc/GmYI9QSdN9TdBgLzHwpA2
// $2y$12$S6JQarTAMwvgt1ovo2SwPe/E/sEc4oYyZuJfqugwxEhyefdtOhsne



