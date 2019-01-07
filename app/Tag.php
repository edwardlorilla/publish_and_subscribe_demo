<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
class Tag extends Model
{
    use SearchableTrait;
    public $searchable = [
        'columns' => [
            'tags.details'=>1,
            ],
        ];
    public $fillable = [
        'details'
        ];
}
