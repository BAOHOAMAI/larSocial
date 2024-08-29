<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            "comments" => CommentResource::collection($this->comments),
            "created_at" => $this->created_at->format('Y-m-d H:i:s'),
            "updated_at" => $this->updated_at->format('Y-m-d H:i:s'),
            "nums_of_reaction" => $this->reactions_count, // Aggregating Related Models sử dụng withCount
            "nums_of_comment" => $this->comments_count,
            "current_user_reaction" => $this->reactions->count() > 0,
            'user' => [
                "id" => $this->user->id,
                "name" => $this->user->name,
                "username" => $this->user->username,
                "avatar_url" => $this->user->avatar_path ? Storage::url($this->user->avatar_path) : '/img/default_avatar.webp',
            ]
        ];
    }
}
