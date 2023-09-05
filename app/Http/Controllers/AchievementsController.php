<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Listeners\LessonWatcheListener;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\LessonUser;
use Illuminate\Support\Facades\DB;
use App\Models\Achievement;
class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $comment = Comment::create(['body'=>'this the comment from the controller', 'user_id'=>$user->id]);
        event(new CommentWritten($comment));
        //return $comment;
        // $lesson = new Lesson();
        // $lesson->title = 'this is the lesson';
        // $lesson->save();

        $user_achievements = Achievement::where('user_id', $user->id);


        // DB::table((new Lesson())->getTable())->insert(['title' => 'this is the test lesson']);
        // DB::table((new LessonUser())->getTable())->insert(['lesson_id' => $lesson->id, 'user_id' => $user->id]);
        // event(new LessonWatched($lesson, $user));
        return response()->json([
            'unlocked_achievements' => ['achievement' => $user_achievements],
            'next_available_achievements' => [],
            'current_badge' => '',
            'next_badge' => '',
            'remaing_to_unlock_next_badge' => 0
        ]);
    }
}
