<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InviteAutoRegistration extends Model
{
    protected $primaryKey = 'invite_id';

    use SoftDeletes;

    protected $with = ['originalInvite',];

    protected $fillable = [
        'invite_id',
        'email',
        'username',
        'password',
        'done',
        'successful',
        'response',
    ];

    /**
     * @return BelongsTo
     */
    public function originalInvite(): BelongsTo
    {
        return $this->belongsTo(Invite::class, 'invite_id');
    }
}
