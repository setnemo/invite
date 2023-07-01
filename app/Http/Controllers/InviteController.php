<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\InviteAutoRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('blue-sky-admin');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function add(Request $request): RedirectResponse
    {
        $data   = $request->toArray();
        $train  = $data['train'] ?? 1;
        $link   = $data['link'] ?? 1;
        $invite = Invite::query()->create([
            'link'         => $link,
            'train_number' => intval($train),
        ]);
        if (isset($data['username']) && isset($data['email'])) {
            $password = $data['password'] ?? '';
            $username = $data['username'];
            $email    = $data['email'];
            InviteAutoRegistration::query()->create([
                'invite_id' => $invite->refresh()->id,
                'password'  => $password,
                'username'  => $username,
                'email'     => $email,
            ]);
        }
        return redirect(route('home'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function forget(int $id, Request $request): JsonResponse
    {
        Invite::query()->whereId($id)->get()->each(static function (Invite $invite) use ($request) {
            $invite->remover_handle = $request->get('remover_handle', '');
            $invite->remover_email  = $request->get('remover_email', '');
            $invite->remover_did    = $request->get('remover_did', '');
            $invite->save();
        });

        return new JsonResponse(['success' => (bool)Invite::forget($id)]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        return new JsonResponse(['success' => (bool)Invite::query()->withTrashed()->whereId($id)->restore()]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function forceDelete(int $id): JsonResponse
    {
        return new JsonResponse(['success' => (bool)Invite::query()->withTrashed()->whereId($id)->forceDelete()]);
    }
}
