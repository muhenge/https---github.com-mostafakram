# Documentation

`git clone https://github.com/muhenge/https---github.com-mostafakram`

Run

`composer install` in project directory

## Sample controller example

```
<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\Badge;
use App\Models\User;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\LessonUser;
use Illuminate\Support\Facades\DB;
use App\Models\Achievement;
use App\Helpers\NextHelper;
use App\Helpers\NextBadgeHelper;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $comments = new Comment();
        $lesson_user = new LessonUser();

        $comment = Comment::create(['body'=>'this the comment from the controller', 'user_id'=>$user->id]);
        event(new CommentWritten($comment));
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


        $getNextElements = new NextHelper();

        $nextBage = new NextBadgeHelper();

        $latest_comment = $getNextElements->getNextElementsFromArray($all_comments, $latestCommentWrittenAchievement->unlocked_achievement);

        $nextAchievements = [];


        array_push($latest_comment, $nextAchievements);
        $latest_watch = $getNextElements->getNextElementsFromArray($all_watched, $latestLessonWatchedAchievement->unlocked_achievement);

        $badge = Badge::where('user_id', $user->id)->value('name');

        $badges = ['Beginner', 'Intermediate', 'Advanced', 'Master'];

        $remaining_badges = $getNextElements->getNextElementsFromArray($badges, $badge);

        $next_badge = $nextBage->getNextElement($badges, $badge);

        return response()->json([
            'unlocked_achievements' => $user_achievements,
            'next_available_achievements' => $latest_watch,
            'current_badge' => $badge,
            'next_badge' => $next_badge,
            'remaining_to_unlock_next_badge' => $remaining_badges
        ]);
    }
}
```

### Steps

* Run the server after database configuration and migration.
* Go to the browser and run `localhost:8000/users/{user_id}/achievements` replace `user_id` with user id created or run `php artisan tinker` and run this 	`App\Models\User::factory()->count(25)->create();` this will generate 25 users and replace the id you want between 1 to 25.
