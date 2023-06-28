<?php

use http\Client\Request;
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
});

Route::get('/deploy', static function() {
    return response(
        null,
        file_exists(base_path() . '/deploy.pid') ?
            Response::HTTP_CONFLICT :
            Response::HTTP_NO_CONTENT
    );
})->middleware(['dev'])->name('dev_deploy');

Auth::routes();

Route::get('/codes', static function() {
    return view('codes');
})->middleware(['blue-sky'])->name('codes');

Route::post('/donate', static function() {

    var_dump(request());die;
    return redirect(route('codes'));
})->middleware(['blue-sky'])->name('donate');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
