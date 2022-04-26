<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PersonaInquiry extends Model
{
    use HasFactory;

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "reference_id");
    }

    function verification(): HasOne
    {
        return $this->hasOne(PersonaVerification::class);
    }
}
