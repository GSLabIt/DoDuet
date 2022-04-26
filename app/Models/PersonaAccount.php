<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonaAccount extends Model
{
    use HasFactory;

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "reference_id");
    }
}
