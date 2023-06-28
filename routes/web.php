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
})->middleware(['guest'])->name('welcome');
//Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
//Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, ''])->name('login');

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
    $account = json_decode(session()->get('acc', '{}'), true);
    $data = request()->toArray();
    $train = $data['train'] ?? 1;
    unset($data['train'],$data['_token']);
    $giftedCodes = array_keys($data);
    foreach ($giftedCodes as $code) {
        \App\Models\InviteCode::query()->create([
            'code' => $code,
            'giver_did' => $account['did'] ?? '',
            'giver_handle' => $account['handle'] ?? '',
            'giver_email' => $account['email'] ?? '',
            'train_number' => intval($train),
        ]);
    }
    return redirect(route('codes'));
})->middleware(['blue-sky'])->name('donate');

Route::post('/book/{id}', static function($id) {
    return new \Illuminate\Http\JsonResponse(['success' => (bool)\App\Models\InviteCode::book($id)]);
});
Route::post('/unbook/{id}', static function($id) {
    return new \Illuminate\Http\JsonResponse(['success' => (bool)\App\Models\InviteCode::unbook($id)]);
});
Route::post('/forget/{id}', static function($id) {
    return new \Illuminate\Http\JsonResponse(['success' => (bool)\App\Models\InviteCode::forget($id)]);
});

