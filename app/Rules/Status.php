<?php

namespace App\Rules;

use App\blog_api\Posts\PostStatusEnum;
use Illuminate\Contracts\Validation\Rule;

class Status implements Rule
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
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $value === PostStatusEnum::PUBLISHED ||
            $value === PostStatusEnum::DRAFT;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute can only have values equal to :' . PostStatusEnum::PUBLISHED . ' and ' . PostStatusEnum::DRAFT;
    }
}
