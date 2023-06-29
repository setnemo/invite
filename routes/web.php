<?php

use App\Models\InviteCode;
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

Auth::routes();

Route::get('/codes', static function () {
    return view('codes');
})->middleware(['blue-sky'])->name('codes');

Route::post('/donate', static function () {
    $account = json_decode(session()->get('acc', '{}'), true);
    $data    = request()->toArray();
    $train   = $data['train'] ?? 1;
    unset($data['train'], $data['_token']);
    $giftedCodes = array_keys($data);
    foreach ($giftedCodes as $code) {
        if (InviteCode::query()->withTrashed()->where('code', $code)->exists()) {
            InviteCode::query()->where('code', $code)->withTrashed()->each(static function (InviteCode $inviteCode) {
                if ($inviteCode->deleted_at) {
                    $inviteCode->restore();
                    $inviteCode = $inviteCode->refresh();
                    $inviteCode->booked_at = null;
                    $inviteCode->save();
                }
            });
            continue;
        }
        InviteCode::query()->create([
            'code'         => $code,
            'giver_did'    => $account['did'] ?? '',
            'giver_handle' => $account['handle'] ?? '',
            'giver_email'  => $account['email'] ?? '',
            'train_number' => intval($train),
        ]);
    }
    return redirect(route('codes'));
})->middleware(['blue-sky'])->name('donate');

Route::post('/book/{id}', static function ($id) {
    InviteCode::query()->whereId($id)->get()->each(static function ($inviteCode) {
        $inviteCode->recipient_handle = request()->get('recipient_handle', '');
        $inviteCode->recipient_email  = request()->get('recipient_email', '');
        $inviteCode->recipient_did    = request()->get('recipient_did', '');
        $inviteCode->save();
    });
    return new JsonResponse(['success' => (bool)InviteCode::book($id)]);
});
Route::post('/unbook/{id}', static function ($id) {
    InviteCode::query()->whereId($id)->get()->each(static function ($inviteCode) {
        $inviteCode->recipient_handle = null;
        $inviteCode->recipient_email  = null;
        $inviteCode->recipient_did    = null;
        $inviteCode->save();
    });
    return new JsonResponse(['success' => (bool)InviteCode::unbook($id)]);
});
Route::post('/forget/{id}', static function ($id) {
    InviteCode::query()->whereId($id)->get()->each(static function ($inviteCode) {
        $inviteCode->remover_handle = request()->get('remover_handle', '');
        $inviteCode->remover_email  = request()->get('remover_email', '');
        $inviteCode->remover_did    = request()->get('remover_did', '');
        $inviteCode->save();
    });
    return new JsonResponse(['success' => (bool)InviteCode::forget($id)]);
});

