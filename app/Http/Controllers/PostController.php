<?php

namespace App\Http\Controllers;
use App\Http\Enums\ReactionEnum;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\PostAttachment;
use App\Models\Reaction;
use App\Http\Requests\StorePostRequest; 
use App\Http\Requests\UpdateCommentRequest; 
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Http\Resources\CommentResource;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        $data = $request->validated(); 
        $user = $request->user();
        
        try {
            $post = Post::create($data);
            $attachments = $data['attachments'] ?? []; 
            
            foreach ($attachments as $attachment) {
                $path = $attachment->store('attachments/' . $post->id, 'public');
                PostAttachment::create([
                    'post_id' => $post->id,
                    'name' => $attachment->getClientOriginalName(), 
                    'created_by' => $user->id, 
                    'mime' => $attachment->getMimeType(), 
                    'path' => $path
                ]);
            }
            
        } catch (\Throwable $th) {
            throw $th;
        }

        return back(); 
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $user = $request->user();

        try {

            $data = $request->validated(); 
            $post->update($data);
            
            if (!empty($data['deleted_id'])) {
                foreach ($data['deleted_id'] as $attachmentId) {
                    $attachment = PostAttachment::find($attachmentId);
                    if ($attachment) {
                        $attachment->delete();
                    }
                }
            };
            
            if (!empty($data['attachments'])) {
                foreach ($data['attachments'] as $attachment) {
                    $path = $attachment->store('attachments/' . $post->id, 'public');
                    PostAttachment::create([
                        'post_id' => $post->id,
                        'name' => $attachment->getClientOriginalName(), 
                        'created_by' => $user->id, 
                        'mime' => $attachment->getMimeType(), 
                        'path' => $path
                    ]);
                }
            }


        } catch (\Throwable $th) {
            throw $th;
        }

        return back(); 
    }
    public function delete(Post $post)
    {   
        $id = Auth::id(); 
        
        if ($id !== $post->user_id) {
            return response('You do not have permission to delete this post', 403);
        }
    
        $attachments = PostAttachment::where('post_id', $post->id)->get();
        
        foreach ($attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->path)) {
                Storage::disk('public')->delete($attachment->path);
            }
            $attachment->delete();
        }

        $photoDirectory = 'attachments/' . $post->id;

        // Xóa thư mục chứa ảnh
        if (Storage::disk('public')->exists($photoDirectory)) {
            Storage::disk('public')->deleteDirectory($photoDirectory);
        }
    
        $post->delete();
    
        return back();
    }
    public function downloadFile($attachment)
    {
        $attachment = PostAttachment::findOrFail($attachment);
        return response()->download(Storage::disk('public')->path($attachment->path), $attachment->name);
    }

    public function postReaction (Request $request , Post $post) 
    {
        $data = $request->validate([
            'reaction' => [Rule::enum(ReactionEnum::class)]
        ]);

        $userId = Auth::id();
        $reaction = Reaction::where('user_id', $userId)
            ->where('object_id', $post->id)
            ->where('object_type', Post::class)
            ->first();

        if ($reaction) {
            $hasReaction = false;
            $reaction->delete();
        } else {
            $hasReaction = true;
            Reaction::create([
                'object_id' => $post->id,
                'object_type' => Post::class,
                'user_id' => $userId,
                'type' => $data['reaction']
            ]);
        }

        $reactions = Reaction::where('object_id', $post->id)->where('object_type', Post::class)->count();

        return response([
            'num_of_reactions' => $reactions,
            'current_user_has_reaction' => $hasReaction
        ]);
    }
    public function postComment (Request $request, Post $post) {
        $data = $request->validate([
            'comment' => ['required'],
        ]);
       
        $userId = Auth::id();
        $postId = $post->id;

        $comment = Comment::create([
            'user_id' => $userId,
            'post_id' => $postId,
            'comment' => nl2br($data['comment']) ,
        ]);
        
        return response(new CommentResource($comment), 201);
    }

    public function updateComment (UpdateCommentRequest $request , Comment $comment) {

        $data = $request->validated();
        
        $comment->update($data);

        return response(new CommentResource($comment), 201);
    }
    public function deleteComment (Comment $comment) {

        $comment->delete();

        return response(new CommentResource($comment), 201);

    } 
    public function commentReaction (Request $request ,Comment $comment) {

        $data = $request->validate([
            'reaction' => [Rule::enum(ReactionEnum::class)]
        ]);

        $userId = Auth::id();
        $reaction = Reaction::where('user_id', $userId)
            ->where('object_id', $comment->id)
            ->where('object_type', Comment::class)
            ->first();

        if ($reaction) {
            $hasReaction = false;
            $reaction->delete();
        } else {
            $hasReaction = true;
            Reaction::create([
                'object_id' => $comment->id,
                'object_type' => Comment::class,
                'user_id' => $userId,
                'type' => $data['reaction']
            ]);
        }

        $reactions = Reaction::where('object_id', $comment->id)->where('object_type', Comment::class)->count();

        return response([
            'num_of_reactions' => $reactions,
            'current_user_has_reaction' => $hasReaction
        ]);
    } 
}
