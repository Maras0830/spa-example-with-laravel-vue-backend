<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'comment_id', 'post_id', 'comment_from_id', 'comment_from_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'comment_from_id', 'comment_from_type', 'comment_id'
    ];

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function main_comment()
    {
        return $this->belongsTo('App\Comment');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function comment_from()
    {
        return $this->morphTo();
    }
}