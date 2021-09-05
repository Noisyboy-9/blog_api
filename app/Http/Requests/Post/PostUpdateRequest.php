<?php

namespace App\Http\Requests\Post;

use App\Rules\Slug;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => ['max:255', 'min: 5'],
            'body' => ['min: 30'],
            'description' => ['min: 10'],
            'slug' => [
                new slug(),
                Rule::unique('posts')->ignore($this->post->slug, 'slug')
            ]
        ];
    }
}
