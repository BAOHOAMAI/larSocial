<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

     public static array $extensions = [
        'jpg', 'jpeg', 'png', 'gif', 'webp',
        'mp3', 'wav', 'mp4',
        "doc", "docx", "pdf", "csv", "xls", "xlsx",
        "zip"
    ];

    
    public function authorize(): bool
    {
        $post = $this->route('post');

        return $post->user_id == Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => 'nullable',
            'user_id' => 'numeric',
            'attachments' => ['array', 'max:50'],
            'deleted_id' => ['array', 'max:50'], 
            'attachments.*' => [
                'file',
                File::types(self::$extensions),
            ],
        ];
    }
}
