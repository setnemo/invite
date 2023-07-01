<?php

namespace App\Http\Controllers;

use App\Models\InviteCode;
use App\Services\BlueSky;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class HomeController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('bluesky');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function home()
    {
        return view('home');
    }

    /**
     * @return Application|RedirectResponse|Redirector
     * @throws GuzzleException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Throwable
     */
    public function donate(Request $request)
    {
        $account = json_decode($request->session()->get('account', '{}'), true);
        $data    = $request->toArray();
        $train   = $data['train'] ?? 1;
        unset($data['train'], $data['_token']);
        if (isset($data['code'])) {
            $token         = $account['accessJwt'];
            $giftedCodes[] = $data['code'];
            $giftedFrom    = json_decode(app(BlueSky::class)->bskyGetProfile($token, $data['handle'] ?? ''), true);
            $did           = $giftedFrom['did'] ?? '';
            $handle        = $giftedFrom['handle'] ?? '';
            $email         = $giftedFrom['email'] ?? $account['email'] ?? '' ?: '';
        } else {
            $giftedCodes = array_keys($data);
            $did         = $account['did'] ?? '';
            $handle      = $account['handle'] ?? '';
            $email       = $account['email'] ?? '';
        }
        foreach ($giftedCodes as $code) {
            if (InviteCode::query()->withTrashed()->where('code', $code)->exists()) {
                InviteCode::query()->where('code', $code)->withTrashed()->each(static function (InviteCode $inviteCode) {
                    if ($inviteCode->deleted_at) {
                        $inviteCode->restore();
                        $inviteCode            = $inviteCode->refresh();
                        $inviteCode->booked_at = null;
                        $inviteCode->save();
                    }
                });
                continue;
            }
            InviteCode::query()->create([
                'code'         => $code,
                'giver_did'    => $did,
                'giver_handle' => $handle,
                'giver_email'  => $email,
                'train_number' => intval($train),
            ]);
        }

        return redirect(route('home'));
    }
}
