<?php

namespace App\blog_api\Traits;

use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToPostTrait
{
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
