<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer id
 * @property string slug
 * @property string name
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function path(): string
    {
        return "/api/posts?category=$this->slug";
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

}
