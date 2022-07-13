<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\HomeController as UserHomeController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    $users = User::all();

    return view('welcome', compact('users'));
});
Route::group(['controller' => HomeController::class], function () {
    Route::get('{username}', 'profile')->where('username', '^(?!settings|dashboard).*$')->name('user.profile');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});
