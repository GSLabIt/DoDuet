<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\CryptographicComposition;
use App\Traits\Uuid;
use Doinc\Modules\Referral\Models\Traits\Referrable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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


/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, Uuid, HasRoles, LogsActivity;
    use ActivityLogAll, CryptographicComposition, Referrable;



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
        'name' => 'encrypted',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];



    /**
    * |--------------------------------------------------------------------------
    * | User segments section
    * |--------------------------------------------------------------------------
    * |
    * | Define all the _user segments_ related methods here
    * |
    */

    public function userSegments(): BelongsToMany
    {
        return $this->belongsToMany(UserSegments::class, "user_user_segments");
    }



    /**
     * |--------------------------------------------------------------------------
     * | Tests section
     * |--------------------------------------------------------------------------
     * |
     * | Define all the _tests_ related methods here
     * |
     */

    public function testResults(): HasMany
    {
        return $this->hasMany(TestResult::class);
    }



    /**
     * |--------------------------------------------------------------------------
     * | Settings section
     * |--------------------------------------------------------------------------
     * |
     * | Define all the _user settings_ related methods here
     * |
     */

    public function settings(): HasMany
    {
        return $this->hasMany(UserSettings::class, "owner_id");
    }

    public function personalInformation(): HasOne
    {
        return $this->hasOne(PersonalInformations::class, "owner_id");
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, "owner_id");
    }



    /**
     * |--------------------------------------------------------------------------
     * | Multi user interaction section
     * |--------------------------------------------------------------------------
     * |
     * | Define all the _multi user_ related methods here
     * |
     */

    public function mentions(): HasMany
    {
        return $this->hasMany(Mentions::class, "mentioned_id");
    }

    public function mentioner(): HasMany
    {
        return $this->hasMany(Mentions::class, "mentioner_id");
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comments::class);
    }

    public function followed(): HasMany
    {
        return $this->hasMany(Follows::class, "follower_id");
    }

    public function followers(): HasMany
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

    public function tipped(): HasMany
    {
        return $this->hasMany(Tips::class, "tipper_id");
    }

    public function receivedTips(): HasMany
    {
        return $this->hasMany(Tips::class, "tipped_id");
    }



    /**
     * |--------------------------------------------------------------------------
     * | Tracks & Challenges section
     * |--------------------------------------------------------------------------
     * |
     * | Define all the _tracks_ and _challenges_ related methods here
     * |
     */

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
        return $this->hasMany(Challenges::class, "first_place_id");
    }

    public function secondPlaces(): HasMany
    {
        return $this->hasMany(Challenges::class, "second_place_id");
    }

    public function thirdPlaces(): HasMany
    {
        return $this->hasMany(Challenges::class, "third_place_id");
    }

    public function ownedCovers(): HasMany
    {
        return $this->hasMany(Covers::class, "owner_id");
    }

    public function createdCovers(): HasMany
    {
        return $this->hasMany(Covers::class, "creator_id");
    }

    public function ownedAlbums(): HasMany
    {
        return $this->hasMany(Albums::class, "owner_id");
    }

    public function createdAlbums(): HasMany
    {
        return $this->hasMany(Albums::class, "creator_id");
    }

    public function ownedLyrics(): HasMany
    {
        return $this->hasMany(Lyrics::class, "owner_id");
    }

    public function createdLyrics(): HasMany
    {
        return $this->hasMany(Lyrics::class, "creator_id");
    }

    public function listeningRequests(): HasMany
    {
        return $this->hasMany(ListeningRequest::class, "voter_id");
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class, "voter_id");
    }

    public function libraries(): HasMany
    {
        return $this->hasMany(PersonalLibraries::class, "owner_id");
    }
}
