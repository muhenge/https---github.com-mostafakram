<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Comment;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $comment = Comment::create(['body'=>'this the comment from the controller', 'user_id'=>$user->id]);
        event(new CommentWritten($comment));
        return $comment;
        return response()->json([
            'unlocked_achievements' => [],
            'next_available_achievements' => [],
            'current_badge' => '',
            'next_badge' => '',
            'remaing_to_unlock_next_badge' => 0
        ]);
    }
}
