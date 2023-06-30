<?php

namespace App\Http\Controllers;

use App\Services\BlueSky;
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
    protected $redirectTo = '/home';

    private BlueSky $blueSkyService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->blueSkyService = app(BlueSky::class);
    }

    /**
     * @return BlueSky
     */
    public function getBlueSkyService(): BlueSky
    {
        return $this->blueSkyService;
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

    public function showLoginForm()
    {
        return redirect(route('welcome'));
    }

    public function logout()
    {
        session()->forget([
            'blue_sky_access_jwt',
            'account',
            'codes',
        ]);

        return redirect(route('welcome'));
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        try {
            if ($result = $this->getBlueSkyService()->atprotoCreateSession($request->get('identifier', ''), $request->get('password', ''))) {
                $request->session()->put('account', $result);
                $data = json_decode($result, true);
                $request->session()->put([
                    'blue_sky_access_jwt' => $data['accessJwt'] ?? '',
                ]);
                $request->session()->put('codes', $this->getBlueSkyService()->atprotoGetAccountInviteCodes($data['accessJwt'] ?? ''));

                return $this->sendLoginResponse($request);
            }
        } catch (\Throwable $t) {
            throw ValidationException::withMessages([
                'error' => $t->getMessage(),
            ]);
        }

        throw ValidationException::withMessages(['error' => 'login failed']);
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

}
