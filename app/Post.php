<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'author_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'author_id'
    ];

    public function author()
    {
        return $this->belongsTo('App\Admin');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment')->whereNull('comment_id');
    }

    public function all_comments()
    {
        return $this->hasMany('App\Comment');
    }
}
