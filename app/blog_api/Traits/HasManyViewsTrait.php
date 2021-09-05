<?php

namespace App\blog_api\Traits;

use App\Models\User;
use App\Models\View;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyViewsTrait
{
    public function addViewIfNotExist(User $viewer)
    {
        if (!$this->viewerExist($viewer)) {
            return $this->views()->create([
                'viewer_id' => $viewer->id
            ]);
        }
    }

    public function viewerExist(User $viewer): bool
    {
        return $this->views()
            ->where('viewer_id', $viewer->id)
            ->exists();
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class, 'post_id', 'id');
    }

    public function viewsCount(): int
    {
        return $this->views()->count();
    }
}
