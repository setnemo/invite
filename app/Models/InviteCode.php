<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'booked_at',
        'train_number',
    ];

    protected $casts = [
        'booked_at' => 'datetime',
    ];

    public const TRAIN_MAP = [
        1 => '№1 "Церква Святого Інвайту"',
        5 => '№5 "Військовий"',
        6 => '№6 "Qırım"',
        7 => '№7 "Волонтерський"',
        8 => '№8 "Укркрафт"',
        9 => '№9 "Жіноче купе"',
        10 => '№10 "Великий Ненацьк"',
        11 => '№11 "Украрт"',
        12 => '№12 "Укррайт"',
        14 => '№14 "Укркосплей"',
        15 => '№15 "Блоґерский"',
        16 => '№16 "Розіграші"',
        17 => '№17 "Три Панди"',
        18 => '№18 "Лаковарний"',
    ];
    public const TRAIN_DISABLED = [
        9 => '№9 "Жіноче купе"',
        10 => '№10 "Великий Ненацьк"',
    ];

    public const CONDUCTORS_MAP = [
        'setnemo.online' => [1,5,6,7,8,9,10,11,12,14,15,16],
        'uabluerail.org' => [1,5,6,7,8,9,10,11,12,14,15,16],
        'bsky.church' => [1,5,6,7,8,9,10,11,12,14,15,16],
        'mathan.dev' => [11],
        'djema.qirim.land' => [5,6,7],
        'deadcake.bsky.social' => [7],
        'uacraft.bsky.social' => [8],
        'ukrfanficshn.bsky.social' => [12],
        'eklesa.bsky.social' => [14],
        'soloplayerua.bsky.social' => [15],
        'alco-alchemist.bsky.social' => [16],
        'rikk-tikki-tav.bsky.social' => [17],
        'headhyperempty.bsky.social' => [18],
    ];

    public static function getCodesByHandle(string $handle)
    {
        $trains = static::CONDUCTORS_MAP[$handle] ?: [];
        if (empty($trains)) {
            return [];
        }

        $data = self::query()->whereIn('train_number', $trains)->get();
        $result = [];
        foreach ($data->all() as $item) {
            $result[static::TRAIN_MAP[$item->train_number] ?? ''][] = $item;
        }
        return $result;
    }

    public static function book($id)
    {
        self::query();
        return self::query()->where('id', $id)->each(static function (InviteCode $inviteCode) { $inviteCode->booked_at = new \DateTime(); $inviteCode->save(); });
    }

    public static function unbook($id)
    {
        return self::query()->where('id', $id)->each(static function (InviteCode $inviteCode) { $inviteCode->booked_at = null; $inviteCode->save(); });
    }

    public static function forget($id)
    {
        return self::query()->where('id', $id)->each(static function (InviteCode $inviteCode) { $inviteCode->runSoftDelete(); });
    }
}
