<?php

namespace App\Models;

use App\blog_api\Traits\BelongsToOwnerTrait;
use App\blog_api\Traits\BelongsToPostTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory, BelongsToOwnerTrait, BelongsToPostTrait;

    protected $fillable = ['post_id', 'owner_id', 'body'];
}
