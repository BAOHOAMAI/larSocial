<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "body" => $this->body,
            "user" => new UserResource($this->user),
            "group" => $this->group,
            "attachments" => PostAttachmentResource::collection($this->attachments),
            "created_at" => $this->created_at->format('Y-m-d H:i:s'),
            "updated_at" => $this->updated_at->format('Y-m-d H:i:s'),
            "nums_of_reaction" => $this->reactions_count, // Aggregating Related Models sử dụng withCount
            "nums_of_comment" => count($this->comments),
            "current_user_reaction" => $this->reactions->count() > 0,
            "comments" => self::convertCommentsIntoTree($this->comments),
        ];
    }

    private static function convertCommentsIntoTree($comments, $parentId = null): array
    {
        $commentTree = [];

        foreach ($comments as $comment) {
            if ($comment->parent_id === $parentId) {

                $children = self::convertCommentsIntoTree($comments, $comment->id);
                $comment->childComments = $children;
                $comment->numOfComments = collect($children)->sum('numOfComments') + count($children);
                $commentTree[] = new CommentResource($comment);
                
            }
        }

        return $commentTree;
    }
    
}
