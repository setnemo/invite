<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaravelIdea\Helper\App\Models\_IH_Invite_C;
use LaravelIdea\Helper\App\Models\_IH_Invite_QB;

class Invite extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'link',
        'train_number',
        'remover_handle',
        'remover_email',
        'remover_did',
    ];

    /**
     * @param $id
     * @return Invite[]|Builder[]|Collection|_IH_Invite_C|_IH_Invite_QB[]
     */
    public static function forget($id)
    {
        return self::query()->where('id', $id)->get()->each(static function (Invite $invite) {
            $invite->runSoftDelete();
        });
    }
}
