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
     * Generate a unique join code (12 characters: numbers, uppercase and lowercase letters)
     */
    public static function generateJoinCode(): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        
        do {
            $code = '';
            for ($i = 0; $i < 12; $i++) {
                $code .= $characters[random_int(0, strlen($characters) - 1)];
            }
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




