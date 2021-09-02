<?php
namespace App\blog_api\posts\traits;

use App\blog_api\posts\PostStatusEnum;

trait HasStatusTrait
{
    public function published(): bool
    {
        return (int)$this->status === PostStatusEnum::PUBLISHED;
    }

    public function drafted(): bool
    {
        return (int)$this->status === PostStatusEnum::DRAFT;
    }

    public function publish()
    {
        $this->update(['status' => PostStatusEnum::PUBLISHED]);
    }
}
