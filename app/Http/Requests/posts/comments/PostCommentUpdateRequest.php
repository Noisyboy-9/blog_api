<?php

namespace App\Http\Requests\posts\comments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class PostCommentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['post_id' => "array", 'body' => "string[]"])]
    public function rules(): array
    {
        return [
            'post_id' => ['required', Rule::exists('posts', 'id')],
            'body' => ['required']
        ];
    }
}
