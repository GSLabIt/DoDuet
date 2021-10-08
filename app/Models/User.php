<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\CryptographicComposition;
use App\Traits\Uuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
    use ActivityLogAll, CryptographicComposition;

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

    public function userSegments(): BelongsToMany
    {
        return $this->belongsToMany(UserSegments::class);
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

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function referral(): HasOne
    {
        return $this->hasOne(Referral::class);
    }

    public function referred(): HasOne
    {
        return $this->hasOne(Referred::class);
    }

    public function listeningRequests(): HasMany
    {
        return $this->hasMany(ListeningRequest::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comments::class);
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follows::class, "follower_id");
    }

    public function followed(): HasMany
    {
        return $this->hasMany(Follows::class, "followed_id");
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Messages::class, "sender_id");
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Messages::class, "receiver_id");
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Reports::class, "reportable");
    }
  
    public function sentTips(): HasMany
    {
        return $this->hasMany(Tips::class, "tipper_id");
    }

    public function receivedTips(): HasMany
    {
        return $this->hasMany(Tips::class, "tipped_id");
    }
}
