<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\PostReaction;
use App\Models\Group;
use App\Models\Comment;
use App\Models\PostAttachment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id','body'];

    function user () {
        return $this->belongsTo(User::class);
    }

    function group () {
        return $this->belongsTo(Group::class);
    }

    function attachments () {
        return $this->hasMany(PostAttachment::class);
    }
    
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'object');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
