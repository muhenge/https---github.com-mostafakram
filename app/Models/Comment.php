<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    const FIRST_COMMENT = 'First Comment Written';
    const THREE_COMMENTS = '3 Comments Written';
    const FIVE_COMMENTS = '5 Comments Written';
    const TEN_COMMENTS = '10 Comments Written';
    const TWENTY_COMMENTS = '20 comments Written';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'user_id'
    ];

    /**
     * Get the user that wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
