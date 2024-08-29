<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id','post_id','comment','parent_id'];

    function user () {
        return $this->belongsTo(User::class);
    }
    function post () {
        return $this->belongsTo(Post::class);
    }
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'object');
    }
    public function comments()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
