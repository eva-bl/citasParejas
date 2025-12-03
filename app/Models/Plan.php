<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'couple_id',
        'title',
        'date',
        'category_id',
        'location',
        'cost',
        'description',
        'created_by',
        'status',
        'overall_avg',
        'fun_avg',
        'emotional_connection_avg',
        'organization_avg',
        'value_for_money_avg',
        'ratings_count',
        'photos_count',
        'last_rated_at',
    ];

    protected $casts = [
        'date' => 'date',
        'cost' => 'decimal:2',
        'overall_avg' => 'decimal:2',
        'fun_avg' => 'decimal:2',
        'emotional_connection_avg' => 'decimal:2',
        'organization_avg' => 'decimal:2',
        'value_for_money_avg' => 'decimal:2',
        'last_rated_at' => 'datetime',
    ];

    /**
     * Get the couple that owns the plan
     */
    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    /**
     * Get the category of the plan
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user who created the plan
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the ratings for the plan
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the photos for the plan
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * Get users who favorited this plan
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_plan_favorites')
            ->withTimestamps();
    }

    /**
     * Get the activity log for the plan
     */
    public function activityLog(): HasMany
    {
        return $this->hasMany(PlanActivityLog::class);
    }

    /**
     * Scope: Get plans for a couple
     */
    public function scopeForCouple($query, $coupleId)
    {
        return $query->where('couple_id', $coupleId);
    }

    /**
     * Scope: Get completed plans
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Get pending plans
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if plan is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if user has rated this plan
     */
    public function hasRatingFrom(User $user): bool
    {
        return $this->ratings()->where('user_id', $user->id)->exists();
    }

    /**
     * Get rating from specific user
     */
    public function getRatingFrom(User $user): ?Rating
    {
        return $this->ratings()->where('user_id', $user->id)->first();
    }
}





