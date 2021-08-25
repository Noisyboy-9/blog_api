<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string id
 * @property string slug
 * @property string body
 * @property string title
 * @property string description
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'description', 'slug', 'owner_id', 'category_id'];

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

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['category'] ?? false, fn(Builder $query, string $category) => $query
            ->whereHas('category', fn(Builder $query) => $query
                ->where('slug', $category)
            )
        );

        $query->when($filters['search'] ?? false, fn(Builder $query, string $search) => $query
            ->where(fn() => $query
                ->where('title', 'like', '%' . $search . '%')
                ->orWhere('body', 'like', '%' . $search . '%')
                ->orWhere('slug', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
            )
        );
    }
}
