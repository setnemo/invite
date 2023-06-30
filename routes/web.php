<?php

use App\Http\Controllers\DonateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InviteCodeController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\LoginController;
use App\Models\Invite;
use App\Models\InviteCode;
use App\Services\BlueSky;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

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
    return view('welcome');
})->middleware(['guest'])->name('welcome');

Route::get('/deploy', static function () {
    return response(
        null,
        file_exists(base_path() . '/deploy.pid') ?
            Response::HTTP_CONFLICT :
            Response::HTTP_NO_CONTENT
    );
})->middleware(['dev'])->name('dev_deploy');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('show-login-form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::post('/donate', [HomeController::class, 'donate'])->name('donate');

Route::post('/book/{id}', [InviteCodeController::class, 'book'])->name('invite-book');
Route::post('/unbook/{id}', [InviteCodeController::class, 'unbook'])->name('invite-unbook');
Route::post('/forget/{id}', [InviteCodeController::class, 'forget'])->name('invite-forget');
Route::post('/move', [InviteCodeController::class, 'move'])->name('invite-move');
Route::post('/forget-invite/{id}', [InviteController::class, 'forget'])->name('invite-forget');
Route::post('/invite', [InviteController::class, 'add'])->name('invite-add');

Route::middleware(['blue-sky-super-admin'])->group(static function () {
    Route::post('/invite-code-restore/{id}', [InviteCodeController::class, 'restore'])->name('invite-code-restore');
    Route::post('/invite-code-force-delete/{id}', [InviteCodeController::class, 'forceDelete'])->name('invite-code-force-delete');
    Route::post('/invite-restore/{id}', [InviteController::class, 'restore'])->name('invite-restore');
    Route::post('/invite-force-delete/{id}', [InviteController::class, 'forceDelete'])->name('invite-force-delete');
});


