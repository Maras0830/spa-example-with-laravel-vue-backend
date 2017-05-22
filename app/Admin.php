<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
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

    public function Posts()
    {
        return $this->hasMany('App\Post');
    }

    public function Comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function comment_from()
    {
        return $this->morphMany('App\Comment', 'comment_from');
    }
}
