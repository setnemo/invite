<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaravelIdea\Helper\App\Models\_IH_Invite_C;
use LaravelIdea\Helper\App\Models\_IH_Invite_QB;

class Invite extends Model
{
    use SoftDeletes;

    protected $with = ['autoInvite',];

    protected $fillable = [
        'link',
        'train_number',
        'remover_handle',
        'remover_email',
        'remover_did',
    ];

    protected static function boot()
    {
        parent::boot();

        static::softDeleted(static function (Invite $invite) {
            $invite->autoInvite()->delete();
        });

        static::restored(static function (Invite $invite) {
            $invite->autoInvite()->withTrashed()->restore();
        });
    }

    /**
     * @return BelongsTo
     */
    public function autoInvite(): BelongsTo
    {
        return $this->belongsTo(InviteAutoRegistration::class, 'id', 'invite_id');
    }

    /**
     * @param $id
     * @return Invite[]|Builder[]|Collection|_IH_Invite_C|_IH_Invite_QB[]
     */
    public static function forget($id)
    {
        return self::query()->where('id', $id)->get()->each(static function (Invite $invite) {
            $invite->delete();
        });
    }
}
