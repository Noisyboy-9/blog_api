<?php

namespace App\blog_api\Traits;

use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCategoryTrait
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
