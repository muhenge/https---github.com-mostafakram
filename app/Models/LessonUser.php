<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonUser extends Model
{
    use HasFactory;

    const FIRST_WATCHED = 'First Watched';
    const FIVE_WATCHED = '5 Watched';
    const TEN__WATCHED = '10 Watched';
    const TWENTY_WATCHED = '20 Watched';
    const TWENTY_FIVE_WATCHED = '25 Watched';

    protected $fillable = [
        'lesson_id',
        'user_id',
    ];

    protected $table = 'lesson_user';

    public $timestamps = false;
}
