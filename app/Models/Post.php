<?php

namespace App\Models;


use App\blog_api\Traits\BelongsToCategoryTrait;
use App\blog_api\Traits\BelongsToOwnerTrait;
use App\blog_api\Traits\CanBeFilteredTrait;
use App\blog_api\Traits\HasManyCommentsTrait;
use App\blog_api\Traits\HasManyViewsTrait;
use App\blog_api\Traits\HasStatusTrait;
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
