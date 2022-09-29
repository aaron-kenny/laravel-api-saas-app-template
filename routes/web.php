<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/notification', function() {
    $user = App\Models\User::find(1);
    return (new App\Notifications\BrokerResponse('Oanda', '{"broker": "response"}'))->toMail($user);
});

Auth::routes(['register' => false, 'verify' => true]);

Route::get('/healthz', function() { return response('', 200); })->withoutMiddleware(['web']);

Route::middleware(['auth', 'verified', 'subscribed'])->group(function() {
    // DASHBOARD ROUTES
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    // API TOKEN ROUTES
    Route::get('/api/tokens', [ApiTokenController::class, 'index'])->name('api.token.index');
    Route::get('/api/tokens/create', [ApiTokenController::class, 'create'])->name('api.token.create');
    Route::get('/api/tokens/{token_id}/edit', [ApiTokenController::class, 'edit'])->name('api.token.edit');
    Route::post('/api/tokens', [ApiTokenController::class, 'store'])->name('api.token.store');
    Route::patch('/api/tokens/{token_id}', [ApiTokenController::class, 'update'])->name('api.token.update');
    Route::delete('/api/tokens/{token_id}', [ApiTokenController::class, 'destroy'])->name('api.token.destroy');

    // SETTINGS ROUTES
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings', [SettingsController::class, 'destroy'])->name('settings.destroy');
});