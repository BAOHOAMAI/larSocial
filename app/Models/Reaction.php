<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = ['object_id','object_type','user_id','type'];

    public function object(): MorphTo
    {
        return $this->morphTo();
    }
}
