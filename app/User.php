<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Nicolaslopezj\Searchable\SearchableTrait;
class User extends Authenticatable
{
    use Notifiable,HasApiTokens, SearchableTrait;
    protected $appends = ['isSubscribe'];
    protected $searchable = [
        'columns' => [
            'users.name' => 1,
            'users.email' => 2,
            'tags.details'=>3,
            ],
        'joins' => [
            'tag_user' => ['users.id', 'tag_user.user_id'],
            'tags' => ['tag_user.tag_id', 'tags.id'],
            ],
        ];
public function subscriptions()
{
    return $this->hasMany(Subscription::class);
}
public function subscribe($userId = null)
{
    return $this->subscriptions()->save(new Subscription(['subscriber_id' => $userId ?: auth()->id()]));
}
public function unsubscribe($userId = null)
{
    $this->subscriptions()->whereSubscriberId($userId ?: auth()->id())->delete();
    return response()->json($this);
}
public function getIsSubscribeAttribute()
{
    return $this->subscriptions()->whereSubscriberId(auth()->id())->exists();
}
public function tags()
{
    return $this->belongsToMany(Tag::class);
}
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
