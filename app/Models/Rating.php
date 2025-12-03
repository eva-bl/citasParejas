<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'user_id',
        'fun',
        'emotional_connection',
        'organization',
        'value_for_money',
        'overall',
        'comment',
    ];

    protected $casts = [
        'fun' => 'integer',
        'emotional_connection' => 'integer',
        'organization' => 'integer',
        'value_for_money' => 'integer',
        'overall' => 'integer',
    ];

    /**
     * Get the plan that was rated
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the user who made the rating
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}





