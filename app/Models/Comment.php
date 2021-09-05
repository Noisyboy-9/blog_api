<?php

namespace App\Models;

use App\blog_api\Traits\BelongsToOwnerTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory, BelongsToOwnerTrait;

    protected $fillable = ['post_id', 'owner_id', 'body'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
