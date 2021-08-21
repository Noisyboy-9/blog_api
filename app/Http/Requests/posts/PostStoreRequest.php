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
            'title' => ['required', 'max:255', 'min: 5'],
            'body' => ['required', 'min: 30'],
            'description' => ['required', 'min: 10'],
            'slug' => ['required', new slug(), 'unique:posts'],
            'category_id' => ['required']
        ];
    }
}
