<?php
namespace App\Models;

use App\blog_api\posts\traits\BelongsToCategoryTrait;
use App\blog_api\posts\traits\BelongsToOwnerTrait;
use App\blog_api\posts\traits\CanBeFilteredTrait;
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
    BelongsToOwnerTrait;

    protected $fillable = ['title', 'body', 'description', 'slug', 'owner_id', 'category_id', 'status'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
