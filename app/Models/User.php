<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, Uuid, HasRoles, LogsActivity;
    use ActivityLogAll;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function userSegment(): BelongsTo
    {
        return $this->belongsTo(UserSegments::class);
    }

    public function testResults(): HasMany
    {
        return $this->hasMany(TestResult::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(UserSettings::class, "owner_id");
    }

    public function mentions(): HasMany
    {
        return $this->hasMany(Mentions::class, "mentioned_id");
    }

    public function mentioner(): HasMany
    {
        return $this->hasMany(Mentions::class, "mentioner_id");
    }

    public function personalInformation(): HasOne
    {
        return $this->hasOne(PersonalInformations::class, "owner_id");
    }

    public function ownedTracks(): HasMany
    {
        return $this->hasMany(Tracks::class, "owner_id");
    }

    public function createdTracks(): HasMany
    {
        return $this->hasMany(Tracks::class, "creator_id");
    }

    public function firstPlaces(): HasMany
    {
        return $this->hasMany(Elections::class, "first_place_id");
    }

    public function secondPlaces(): HasMany
    {
        return $this->hasMany(Elections::class, "second_place_id");
    }

    public function thirdPlaces(): HasMany
    {
        return $this->hasMany(Elections::class, "third_place_id");
    }

    public function ownedCovers(): HasMany
    {
        return $this->hasMany(Covers::class, "owner_id");
    }

    public function createdCovers(): HasMany
    {
        return $this->hasMany(Covers::class, "creator_id");
    }

    public function ownedLyrics(): HasMany
    {
        return $this->hasMany(Lyrics::class, "owner_id");
    }

    public function createdLyrics(): HasMany
    {
        return $this->hasMany(Lyrics::class, "creator_id");
    }

    public function referral(): HasOne
    {
        return $this->hasOne(Referral::class);
    }
}
