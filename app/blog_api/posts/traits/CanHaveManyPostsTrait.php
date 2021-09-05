<?php


namespace App\blog_api\posts\traits;

use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanHaveManyPostsTrait
{

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
