<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Post;
use App\Models\PostReaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Resources\PostResource;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $posts = Post::query()
            ->withCount('reactions')
            ->withCount('comments') 
            ->with(['comments' => function($query) {
                $query->latest()
                    ->take(5)
                    ->withCount('reactions'); 
            }])
            ->latest()
            ->paginate(20);
    
        return Inertia::render('Home', [
            'user' => new UserResource($user),
            'posts' => PostResource::collection($posts)
        ]);
    }
    
}
