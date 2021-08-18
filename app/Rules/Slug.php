<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Str;

class Slug implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $slugVersion = (string) Str::of($value)->slug('-');
        return  $slugVersion === $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'the :attribute must be a string with only no spaces.';
    }
}
