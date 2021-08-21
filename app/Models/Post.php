<?php

namespace App\Models;

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

    protected $fillable = ['title', 'body', 'description', 'slug', 'category_id'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
