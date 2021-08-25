<?php

namespace App\Http\Requests\posts\comments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostCommentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
//        TODO: implement authorize method for all of the form request classes.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => ['required'],
            'post_id' => ['required', Rule::exists('posts', 'id')]
        ];
    }
}