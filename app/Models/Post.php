<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model // implements HasPresenter
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'post';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'description', 'category', 'content', 'excerpt', 'image', 'status', 'user_id'];

    protected $dates = ['deleted_at'];

    /**
     * Author of the quiz
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function author() {

        return $this->hasOne('App\User', 'id', 'user_id');
    }
}