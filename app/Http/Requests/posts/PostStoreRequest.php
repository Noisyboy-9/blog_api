<?php

namespace App\Http\Requests\posts;

use App\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'body' => ['required'],
            'description' => ['required'],
            'slug' => ['required', new slug(), 'unique:posts']
        ];
    }
}
