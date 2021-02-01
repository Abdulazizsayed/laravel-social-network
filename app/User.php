<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'nickname', 'bio'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* Relationships */

    public function followers()
    {
        return $this->belongsToMany('App\User', 'user_user', 'following_id', 'user_id');
    }

    public function following()
    {
        return $this->belongsToMany('App\User', 'user_user', 'user_id', 'following_id');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    public function sent()
    {
        return $this->hasMany('App\Message', 'sender_id');
    }

    public function received()
    {
        return $this->hasMany('App\Message', 'receiver_id');
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification', 'to_id');
    }

    public function image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }

    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function lastMessage(User $user)
    {
        return Message::whereIn('sender_id', [$user->id, $this->id])->whereIn('receiver_id', [$user->id, $this->id])->orderBy('created_at', 'desc')->first();
    }
}
