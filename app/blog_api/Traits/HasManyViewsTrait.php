<?php

namespace App\blog_api\Traits;

use App\Models\View;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Redis;

trait HasManyViewsTrait
{
    public function addViewer(Authenticatable $viewer)
    {
        $this->addViewToDatabase($viewer);
        $this->incrementViewCountCache();
    }

    private function addViewToDatabase(Authenticatable $viewer): void
    {
        $this->views()->create([
            'viewer_id' => $viewer->id
        ]);
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class, 'post_id', 'id');
    }

    private function incrementViewCountCache()
    {
        Redis::incr("posts:$this->id:views");
    }

    public function hasViewer(Authenticatable $viewer): bool
    {
        return $this->views()
            ->where('viewer_id', $viewer->id)
            ->exists();
    }

    public function viewsCount(): int
    {
        return Redis::get("posts:$this->id:views") ?? 0;
    }
}
