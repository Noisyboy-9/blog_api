<?php

namespace App\blog_api\Traits;

use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

trait HasManyBookmarksTrait
{
    public function bookmark(Post $post): void
    {
        Redis::sadd("users:$this->id:bookmarks", serialize($post));
    }

    public function hasBookmark(Post $target): bool
    {
        $serializedPosts = Redis::sMembers("users:$this->id:bookmarks");
        $posts = [];

        foreach ($serializedPosts as $serializedPost) {
            $posts [] = unserialize($serializedPost);
        }

        foreach ($posts as $post) {
            if ($target->is($post)) {
                return true;
            }
        }

        return false;
    }


    public function bookmarks(): Collection
    {
        $id = $this->id;
        $serializedPosts = Redis::smembers("users:$id:bookmarks");

        $posts = collect([]);

        foreach ($serializedPosts as $post) {
            $posts->add(unserialize($post));
        }

        return $posts;
    }
}
