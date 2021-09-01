<?php
namespace App\Http\Requests\posts;

use App\Rules\Slug;
use App\Rules\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostStoreRequest extends FormRequest
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
            'title' => ['required', 'max:255', 'min: 5'],
            'body' => ['required', 'min: 30'],
            'description' => ['required', 'min: 10'],
            'slug' => ['required', new slug(), 'unique:posts'],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'status' => [new Status()]
        ];
    }
}
