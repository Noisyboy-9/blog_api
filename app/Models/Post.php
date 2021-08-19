<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $fillable = ['title', 'body', 'description', 'slug'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
