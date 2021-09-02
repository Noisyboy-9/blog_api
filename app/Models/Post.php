<?php
namespace App\Models;

use App\blog_api\posts\traits\CanBeFilteredTrait;
use App\blog_api\posts\traits\HasStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory, HasStatusTrait, CanBeFilteredTrait;

    protected $fillable = ['title', 'body', 'description', 'slug', 'owner_id', 'category_id', 'status'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
