<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER  = 'ROLE_USER';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        $roles = explode(',', $this->roles);

        return in_array(self::ROLE_ADMIN, $roles);
    }

    public function isUser()
    {
        $roles = explode(',', $this->roles);

        return in_array(self::ROLE_USER, $roles);
    }
}
