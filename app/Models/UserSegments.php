<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserSegments extends Model
{
    use HasFactory;

    protected $guarded = ["created_at", "updated_at"];

    function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
