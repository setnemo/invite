<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InviteCodeController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\JsonResponse;
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

Route::middleware(['has.secret'])->group(static function () {
    Route::get('/deploy', static function () {
        return response(
            null,
            file_exists(base_path() . '/deploy.pid') ?
                Response::HTTP_CONFLICT :
                Response::HTTP_NO_CONTENT
        );
    })->name('dev_deploy');

    Route::get('/autoqueue', static function () {
        return new JsonResponse(
            \App\Models\InviteAutoRegistration::query()
                ->where('successful', '=', false)
                ->where('done', '=', false)
                ->orderBy('invite_id')
                ->get()
                ->first()
                ->toArray()
        );
    })->name('autoqueue-get-one');
    Route::patch('/autoqueue', static function () {
        $input = request()->toArray();
        \App\Models\InviteAutoRegistration::query()
            ->where('email', '=', $input['email'])
            ->where('username', '=', $input['username'])
            ->update($input);
        return new JsonResponse(['success' => true]);
    })->name('autoqueue-get-one');
});


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
Route::get('/invite/{id}', [InviteController::class, 'getOne'])->name('invite-get');
Route::post('/invite', [InviteController::class, 'add'])->name('invite-add');

Route::middleware(['bluesky.admin.super'])->group(static function () {
    Route::post('/invite-code-restore/{id}', [InviteCodeController::class, 'restore'])->name('invite-code-restore');
    Route::post('/invite-code-force-delete/{id}', [InviteCodeController::class, 'forceDelete'])->name('invite-code-force-delete');
    Route::post('/invite-restore/{id}', [InviteController::class, 'restore'])->name('invite-restore');
    Route::post('/invite-force-delete/{id}', [InviteController::class, 'forceDelete'])->name('invite-force-delete');
});


