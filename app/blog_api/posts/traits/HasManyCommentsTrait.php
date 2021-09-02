<?php
namespace App\blog_api\posts\traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyCommentsTrait
{
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
