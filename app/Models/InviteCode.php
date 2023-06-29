<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaravelIdea\Helper\App\Models\_IH_InviteCode_C;
use LaravelIdea\Helper\App\Models\_IH_InviteCode_QB;

class InviteCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'giver_handle',
        'giver_email',
        'giver_did',
        'recipient_handle',
        'recipient_email',
        'recipient_did',
        'remover_handle',
        'remover_email',
        'remover_did',
        'booked_at',
        'train_number',
    ];

    protected $casts = [
        'booked_at' => 'datetime',
    ];

    public const CAN_ADD_CODES = [
        'setnemo.online',
        'uabluerail.org',
        'bsky.church',
    ];

    public const TRAIN_MAP = [
        1  => '№1 "Церква Святого Інвайту"',
        5  => '№5 "Військовий"',
        6  => '№6 "Qırım"',
        7  => '№7 "Волонтерський"',
        8  => '№8 "Укркрафт"',
        11 => '№11 "Украрт"',
        12 => '№12 "Укррайт"',
        14 => '№14 "Укркосплей"',
        15 => '№15 "Блоґерский"',
        16 => '№16 "Розіграші"',
        17 => '№17 "Три Панди"',
        18 => '№18 "Лаковарний"',
    ];
    public const TRAIN_DISABLED = [
        9  => '№9 "Жіноче купе"',
        10 => '№10 "Великий Ненацьк"',
    ];

    public const CONDUCTORS_MAP = [
        'setnemo.online'             => [1, 5, 6, 7, 8, 9, 10, 11, 12, 14, 15, 16, 17, 18, 19, 20],
        'uabluerail.org'             => [1, 5, 6, 7, 8, 9, 10, 11, 12, 14, 15, 16, 17, 18, 19, 20],
        'bsky.church'                => [1, 5, 6, 7, 8, 9, 10, 11, 12, 14, 15, 16, 17, 18, 19, 20],
        'mathan.dev'                 => [11],
        'djema.qirim.land'           => [5, 6, 7],
        'deadcake.bsky.social'       => [7],
        'uacraft.bsky.social'        => [8],
        'ukrfanficshn.bsky.social'   => [12],
        'eklesa.bsky.social'         => [14],
        'soloplayerua.bsky.social'   => [15],
        'alco-alchemist.bsky.social' => [16],
        'rikk-tikki-tav.bsky.social' => [17],
        'headhyperempty.bsky.social' => [18],
    ];

    /**
     * @param string $handle
     * @return array
     */
    public static function getCodesByHandle(string $handle): array
    {
        $trains = static::CONDUCTORS_MAP[$handle] ?? [];
        if (empty($trains)) {
            return [];
        }

        $data   = self::query()->whereIn('train_number', $trains)->orderBy('created_at')->get();
        $result = [];
        foreach ($data->all() as $item) {
            $result[static::TRAIN_MAP[$item->train_number] ?? ''][] = $item;
        }

        return $result;
    }

    /**
     * @param string $handle
     * @return array
     */
    public static function getQueuesByHandle(string $handle): array
    {
        $trains = static::CONDUCTORS_MAP[$handle] ?? [];
        if (empty($trains)) {
            return [];
        }

        $data   = Invite::query()->whereIn('train_number', $trains)->orderBy('id')->get();
        $result = [];
        foreach ($data->all() as $item) {
            $result[static::TRAIN_MAP[$item->train_number] ?? ''][] = $item;
        }

        return $result;
    }

    /**
     * @param $id
     * @return _IH_InviteCode_QB[]|Builder[]|Collection|InviteCode[]|_IH_InviteCode_C
     */
    public static function book($id)
    {
        return self::query()->where('id', $id)->get()->each(static function (InviteCode $inviteCode) {
            $inviteCode->booked_at = new DateTime();
            $inviteCode->save();
        });
    }

    /**
     * @param $id
     * @return _IH_InviteCode_QB[]|Builder[]|Collection|InviteCode[]|_IH_InviteCode_C
     */
    public static function unbook($id)
    {
        return self::query()->where('id', $id)->get()->each(static function (InviteCode $inviteCode) {
            $inviteCode->booked_at = null;
            $inviteCode->save();
        });
    }

    /**
     * @param $id
     * @return _IH_InviteCode_QB[]|Builder[]|Collection|InviteCode[]|_IH_InviteCode_C
     */
    public static function forget($id)
    {
        return self::query()->where('id', $id)->get()->each(static function (InviteCode $inviteCode) {
            $inviteCode->runSoftDelete();
        });
    }
}
