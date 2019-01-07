<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
class Publication extends Model
{
    use SearchableTrait;
    public $searchable = [
        'columns' => [
            'publications.detail'=>1,
            'tags.details'=>2,
            ],
        'joins' => [
            'tags' => ['publications.id','tags.id'],
            ],
        ];
    public $fillable = [
        'detail'
        ];
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
