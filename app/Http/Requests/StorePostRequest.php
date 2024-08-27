<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */


     public static array $extensions = [
        'jpg', 'jpeg', 'png', 'gif', 'webp',
        'mp3', 'wav', 'mp4','m4v' ,
        "doc", "docx", "pdf", "csv", "xls", "xlsx",
        "zip"
    ];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['nullable', 'string'],
            'user_id' => 'numeric',
            'attachments' => ['array', 'max:50'], 
            'attachments.*' => [
                'file',
                File::types(self::$extensions)->max(512000),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => Auth::user()->id,
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $files = $this->file('attachments');
            $totalSize = 0;

            if ($files) {
                foreach ($files as $file) {
                    $totalSize += $file->getSize();
                }

                if ($totalSize > 1073741824) { // 1GB
                    $validator->errors()->add('attachments', 'The total size of all files must not exceed 1GB.');
                }
            }
        });
    }
    public function messages()
    {
        return [
            'attachments.*.file' => 'Each attachment must be a file.',
            'attachments.*.mimes' => 'Invalid file type for attachments.',
        ];
    }
}
