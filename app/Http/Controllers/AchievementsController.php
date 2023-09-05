<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\User;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\LessonUser;
use Illuminate\Support\Facades\DB;
use App\Models\Achievement;
use App\Helpers\NextAchievementService;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $comments = new Comment();
        $lesson_user = new LessonUser();

        $comment = Comment::create(['body'=>'this the comment from the controller', 'user_id'=>$user->id]);
        event(new CommentWritten($comment));
        //return $comment;

        $lesson = new Lesson();
        $lesson->title = 'this is the lesson';
        $lesson->save();

        $user_achievements = Achievement::where('user_id', $user->id)
        ->pluck('unlocked_achievement');



        DB::table((new Lesson())->getTable())->insert(['title' => $lesson->title]);
        $watched = DB::table((new LessonUser())->getTable())->insert(['lesson_id' => $lesson->id, 'user_id' => $user->id]);
        if ($watched) {
            DB::table((new LessonUser)->getTable())
                ->update([
                    'watched' => 'true',
                ]);
        }
        event(new LessonWatched($lesson, $user));

        $all_comments = [$comments::FIRST_COMMENT, $comments::THREE_COMMENTS, $comments::FIVE_COMMENTS, $comments::TEN_COMMENTS, $comments::TWENTY_COMMENTS];
        $all_watched = [$lesson_user::FIRST_WATCHED, $lesson_user::FIVE_WATCHED, $lesson_user::TEN__WATCHED, $lesson_user::TWENTY_WATCHED, $lesson_user::TWENTY_FIVE_WATCHED];

        $latestLessonWatchedAchievement = Achievement::where('achievement_type', 'lesson_watched')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->first();

        $latestCommentWrittenAchievement = Achievement::where('achievement_type', 'comment_written')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->first();


        $getNextElements = new NextAchievementService();

        $latest_comment = $getNextElements->getNextElementsFromArray($all_comments, $latestCommentWrittenAchievement->unlocked_achievement);
        $nextAchievements = [];

        array_push($latest_comment, $nextAchievements);

        $latest_watch = $getNextElements->getNextElementsFromArray($all_watched, $latestLessonWatchedAchievement->unlocked_achievement);


        return response()->json([
            'unlocked_achievements' => $user_achievements,
            'next_available_achievements' => $latest_watch,
            'current_badge' => '',
            'next_badge' => '',
            'remaing_to_unlock_next_badge' => 0
        ]);
    }
}
