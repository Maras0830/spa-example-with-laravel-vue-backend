<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
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
        'password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function posts()
    {
        return $this->hasMany('App\Post', 'author_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'comment_from_id', 'id');
    }

    public function comment_from()
    {
        return $this->morphMany('App\Comment', 'comment_from');
    }
}
