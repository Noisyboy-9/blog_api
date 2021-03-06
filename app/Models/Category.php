<?php

namespace App\Models;

use App\blog_api\Traits\HasManyPostsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasManyPostsTrait;

    protected $fillable = ['name', 'slug'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function path(): string
    {
        return "/api/feed?category=$this->slug";
    }
}
