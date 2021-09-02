<?php
namespace App\Models;

use App\blog_api\posts\traits\BelongsToCategoryTrait;
use App\blog_api\posts\traits\BelongsToOwnerTrait;
use App\blog_api\posts\traits\CanBeFilteredTrait;
use App\blog_api\posts\traits\HasManyCommentsTrait;
use App\blog_api\posts\traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory,
    HasStatusTrait,
    CanBeFilteredTrait,
    BelongsToCategoryTrait,
    BelongsToOwnerTrait,
    HasManyCommentsTrait;

    protected $fillable = ['title', 'body', 'description', 'slug', 'owner_id', 'category_id', 'status'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function addViewIfNotExist(User $viewer)
    {
        if (!$this->viewerExist($viewer)) {
            return $this->views()->create([
                'viewer_id' => $viewer->id
            ]);
        }
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class, 'post_id', 'id');
    }

    public function viewsCount(): int
    {
        return $this->views()->count();
    }

    public function viewerExist(User $viewer): bool
    {
        return $this->views()
        ->where('viewer_id', $viewer->id)
        ->exists();
    }
}
