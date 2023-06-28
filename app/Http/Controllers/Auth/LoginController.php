<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/codes';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'identifier';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password'        => 'required|string',
        ]);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        try {
            if ($result = $this->blueSkyLogin($request)) {
                $request->session()->put('acc', $result);
                $data = json_decode($result, true);
                $request->session()->put([
                    'password_hash_web' => $data['accessJwt'] ?? '',
                ]);
                $request->session()->put('codes', $this->blueSkyCodes($data['accessJwt'] ?? ''));

                return $this->sendLoginResponse($request);
            }
        } catch (\Throwable $t) {
            throw ValidationException::withMessages([
                'error' => $t->getMessage(),
            ]);
        }
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }

    protected function blueSkyLogin(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $res    = $client->post('https://bsky.social/xrpc/com.atproto.server.createSession', [
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode(['identifier' => $request->get('identifier'), 'password' => $request->get('password'),]),
        ]);

        return $res->getBody()->__toString();
    }

    protected function blueSkyCodes(string $token)
    {
        $client = new \GuzzleHttp\Client();
        $res    = $client->get('https://bsky.social/xrpc/com.atproto.server.getAccountInviteCodes', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return $res->getBody()->__toString();
    }
}
