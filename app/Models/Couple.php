<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Couple extends Model
{
    use HasFactory;

    protected $fillable = [
        'join_code',
        'name',
        'member_count',
        'photo_path',
    ];

    /**
     * Generate a unique join code
     */
    public static function generateJoinCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 12));
        } while (self::where('join_code', $code)->exists());

        return $code;
    }

    /**
     * Get the users in this couple
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the plans for this couple
     */
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }
}




