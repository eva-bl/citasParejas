<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'couple_id',
        'avatar_path',
        'is_admin',
        'nickname',
        'timezone',
        'locale',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the couple this user belongs to
     */
    public function couple(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    /**
     * Get the plans created by this user
     */
    public function createdPlans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Plan::class, 'created_by');
    }

    /**
     * Get the ratings made by this user
     */
    public function ratings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the badges earned by this user
     */
    public function badges(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('obtained_at')
            ->withTimestamps();
    }

    /**
     * Get the user badges (pivot table)
     */
    public function userBadges(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    /**
     * Get the plans favorited by this user
     */
    public function favoritePlans(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'user_plan_favorites')
            ->withTimestamps();
    }

    /**
     * Get the activity log entries for this user
     */
    public function activityLog(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PlanActivityLog::class);
    }

    /**
     * Check if user belongs to a couple
     */
    public function hasCouple(): bool
    {
        return $this->couple_id !== null;
    }

    /**
     * Get the partner (other user in the couple)
     */
    public function partner(): ?User
    {
        if (!$this->hasCouple()) {
            return null;
        }

        return $this->couple->users()
            ->where('id', '!=', $this->id)
            ->first();
    }

    /**
     * Check if user is an administrator
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Get the avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->avatar_path);
        }
        return '';
    }

    /**
     * Check if user has an avatar
     */
    public function hasAvatar(): bool
    {
        return !empty($this->avatar_path);
    }
}
