<?php

namespace App\blog_api\Traits;

use App\blog_api\Posts\PostStatusEnum;

trait HasStatusTrait
{
    public function isPublished(): bool
    {
        return (int)$this->status === PostStatusEnum::PUBLISHED;
    }

    public function isDrafted(): bool
    {
        return (int)$this->status === PostStatusEnum::DRAFT;
    }

    public function publish()
    {
        $this->update(['status' => PostStatusEnum::PUBLISHED]);
    }
}
