<?php

namespace App\blog_api\Traits;

use App\blog_api\Posts\PostStatusEnum;
use Illuminate\Database\Eloquent\Builder;

trait CanBeFilteredTrait
{
    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when(
            $filters['category'] ?? false,
            fn (Builder $query, string $category) => $query
                ->whereHas(
                    'category',
                    fn (Builder $query) => $query
                        ->where('slug', $category)
                )
        );

        $query->when(
            $filters['search'] ?? false,
            fn (Builder $query, string $search) => $query
                ->where(
                    fn () => $query
                        ->where('title', 'like', '%' . $search . '%')
                        ->orWhere('body', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                )
        );
    }

    public function scopePublished(Builder $query)
    {
        $query->where('status', PostStatusEnum::PUBLISHED);
    }

    public function scopeDrafted(Builder $query)
    {
        $query->where('status', PostStatusEnum::DRAFT);
    }
}
