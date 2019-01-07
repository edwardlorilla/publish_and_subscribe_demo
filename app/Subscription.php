<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
  protected $fillable = ['user_id', 'subscriber_id'];
  protected $appends = ['isSubscribe'];
  public function user()
  {
      return $this->belongsTo(User::class);
  }
  public function subscriber()
  {
      return $this->belongsTo(User::class, 'subscriber_id');
  }
  public function getIsSubscribeAttribute()
  {
      return $this->whereUserId(auth()->id())->exists();
  }
}
