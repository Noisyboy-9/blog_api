<?php


namespace App\blog_api\Traits;

use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyPostsTrait
{
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
