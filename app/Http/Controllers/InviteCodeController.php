<?php

namespace App\Http\Controllers;

use App\Models\InviteCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class InviteCodeController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('bluesky.admin');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function move(Request $request): RedirectResponse
    {
        $data       = $request->toArray();
        $train_from = (int)$data['train_from'] ?? 1;
        $train_to   = (int)$data['train_to'] ?? 1;
        $quantity   = (int)$data['quantity'] ?? 0;
        if ($quantity) {
            InviteCode::query()
                ->where('booked_at', null)
                ->where('train_number', $train_from)
                ->orderBy('id')
                ->limit($quantity)
                ->get()
                ->each(static function (InviteCode $inviteCode) use ($train_to) {
                    $inviteCode->train_number = $train_to;
                    $inviteCode->save();
                });
        }
        return redirect(route('home'));
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function forget(int $id): JsonResponse
    {
        InviteCode::query()->whereId($id)->get()->each(static function (InviteCode $inviteCode) {
            $inviteCode->remover_handle = request()->get('remover_handle', '');
            $inviteCode->remover_email  = request()->get('remover_email', '');
            $inviteCode->remover_did    = request()->get('remover_did', '');
            $inviteCode->save();
        });
        return new JsonResponse(['success' => (bool)InviteCode::forget($id)]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function unbook(int $id): JsonResponse
    {
        InviteCode::query()->whereId($id)->get()->each(static function (InviteCode $inviteCode) {
            $inviteCode->recipient_handle = null;
            $inviteCode->recipient_email  = null;
            $inviteCode->recipient_did    = null;
            $inviteCode->save();
        });
        return new JsonResponse(['success' => (bool)InviteCode::unbook($id)]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function book(int $id, Request $request): JsonResponse
    {
        $inviteCode = InviteCode::query()->whereId($id)->get()->first();
        if ($inviteCode->booked_at) {
            return new JsonResponse(['success' => false], Response::HTTP_CONFLICT);
        }
        $inviteCode->recipient_handle = $request->get('recipient_handle', '');
        $inviteCode->recipient_email  = $request->get('recipient_email', '');
        $inviteCode->recipient_did    = $request->get('recipient_did', '');
        $inviteCode->save();

        return new JsonResponse(['success' => (bool)InviteCode::book($id)]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        return new JsonResponse(['success' => (bool)InviteCode::query()->withTrashed()->whereId($id)->get()->each(static function(InviteCode $inviteCode) {
            $inviteCode->restore();
        })]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function forceDelete(int $id): JsonResponse
    {
        return new JsonResponse(['success' => (bool)InviteCode::query()->withTrashed()->whereId($id)->get()->each(static function(InviteCode $inviteCode) {
            $inviteCode->forceDelete();
        })]);
    }
}
