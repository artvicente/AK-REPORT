<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ProjectStatController;
use App\Http\Controllers\ClientController;
use App\Models\ProjectStat;

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// routes/web.php (Sa loob ng admin group)


// Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::middleware(['auth'])->group(function () {
//     Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
// });

//     Route::get('/dashboard', [ProjectStatController::class, 'index'])->name('dashboard');
// ... iba pang code sa taas

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin']], function () {

    // Ito ang gagamitin nating route name na 'admin.dashboard' para sa Client/Admin toggle logic.
    Route::get('/dashboard', [ProjectStatController::class, 'index'])->name('dashboard');

    // TAMA: Gamitin ang ProjectStatController
    Route::put('/project-stats/update', [ProjectStatController::class, 'updateProjectStats'])->name('updateProjectStats');

    // TAMA: Gamitin ang ProjectStatController
    Route::put('/members/{id}', [ProjectStatController::class, 'updateMembersDatabase'])->name('updateMembersDatabase');

    // ... iba pang routes

// ... iba pang code sa baba

/*
|--------------------------------------------------------------------------
| Client Routes (Middleware: auth)
|--------------------------------------------------------------------------
*/
// Gumawa ng simpleng controller para sa Client
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    // Ito ang gagamitin nating route name na 'client.dashboard' para sa Client/Admin toggle logic.
    Route::get('/dashboard', [ClientController::class, 'index'])->name('dashboard');
});

    // TAMA: Gamitin ang ProjectStatController
    Route::put('/project-stats/update', [ProjectStatController::class, 'updateProjectStats'])->name('updateProjectStats');

    // TAMA: Gamitin ang ProjectStatController
    Route::put('/members/{id}', [ProjectStatController::class, 'updateMembersDatabase'])->name('updateMembersDatabase');

    // Stats CRUD (OK na ito)
    Route::resource('stats', ProjectStatController::class)->only(['index', 'edit', 'update']);
});
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin']], function () {

    // 1. Route para I-DISPLAY ang Edit Form (Pupunta sa edit.blade.php)
Route::get('/members/edit', [ProjectStatController::class, 'edit'])->name('members.edit');

    // 2. Route para I-SAVE ang Pagbabago (Ito ang action ng form)
    // Dapat ay PUT/PATCH ang method at magta-target sa 'update' method ng Controller
Route::put('/members/update', [ProjectStatController::class, 'update'])->name('members.update');

    // ... iba pang routes (dashboard, etc.)
    Route::get('/dashboard', [ProjectStatController::class, 'index'])->name('dashboard');
});

$stat = ProjectStat::first();

route('admin.stats.edit', $stat);

// Tama - Ibinigay ang parameter na 'stat' (halimbawa, ang ID ay 1)
route('admin.stats.edit', ['stat' => 1]);
// o kung ang $stat$ mo ay isang object/model:
route('admin.stats.edit', $stat);

/*
|--------------------------------------------------------------------------
| Client Routes (Middleware: auth)
|--------------------------------------------------------------------------
*/
// Gumawa ng simpleng controller para sa Client
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'index'])->name('dashboard');
});
