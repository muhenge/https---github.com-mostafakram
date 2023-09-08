<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'name'
    ];

    public function user()
    {


        return $this->belongsTo(User::class);
    }

    public static function findByBadgeNameAndUserId($badge_name, $user_id)
    {
        return self::where('name', $badge_name)
            ->where('user_id', $user_id)
            ->first();
    }
}
