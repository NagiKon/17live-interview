<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'posts';

    public function comment()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
