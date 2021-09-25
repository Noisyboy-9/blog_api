<?php

namespace App\blog_api\Traits;

use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasManyBookmarksTrait
{

    public function bookmark(Post $post): void
    {
        $this->bookmarks()->attach($post->id);
    }

    public function bookmarks(): BelongsToMany
    {
        return $this
            ->belongsToMany(Post::class, 'bookmarks')
            ->withTimestamps();
    }

    public function hasBookmark(Post $post): bool
    {
        return $this->bookmarks()
            ->where('slug', $post->slug)
            ->exists();
    }
}
