<?php
namespace App\Models;

use App\blog_api\posts\traits\BelongsToCategoryTrait;
use App\blog_api\posts\traits\BelongsToOwnerTrait;
use App\blog_api\posts\traits\CanBeFilteredTrait;
use App\blog_api\posts\traits\HasManyCommentsTrait;
use App\blog_api\posts\traits\HasManyViewsTrait;
use App\blog_api\posts\traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory,
    HasStatusTrait,
    CanBeFilteredTrait,
    BelongsToCategoryTrait,
    BelongsToOwnerTrait,
    HasManyCommentsTrait,
    HasManyViewsTrait;

    protected $fillable = ['title', 'body', 'description', 'slug', 'owner_id', 'category_id', 'status'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
