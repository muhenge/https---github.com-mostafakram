<?php
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Comment creation achievement test', function () {
    it('should unlock 1st user comment created', function () {
        $user = User::factory()->create();

        Comment::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertExactJson([
            'unlocked_achievements' => ['First Comment Written'],
            'next_available_achievements' => [
                '5 Watched',
                '10 Watched',
                '20 Watched',
                '25 Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => [
                'Intermediate',
                'Advanced',
                'Master'
            ]
        ]);
    });
    it('should unlock 5 Comment created, for 5 comments ', function () {
        $user = User::factory()->create();

        Comment::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get("/users/{$user->id}/achievements");

        // Assert that the response status code is 200 (OK)
        $response->assertStatus(200);

        // Optionally, you can assert the response JSON data
        $response->assertExactJson([
            'unlocked_achievements' => ['5 Comments Written'],
            'next_available_achievements' => [
                '5 Watched',
                '10 Watched',
                '20 Watched',
                '25 Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => [
                'Intermediate',
                'Advanced',
                'Master'
            ]
        ]);
    });
});

describe('should unlock Lesson watched achievement test', function () {
    it('An achievement 1 Lesson Watched should', function () {
        $user = User::factory()->create();

        $lesson = new Lesson();
        $lesson->title = 'this is the lesson';
        $lesson->save();


        DB::table((new LessonUser())->getTable())->insert(['lesson_id' => $lesson->id, 'user_id' => $user->id]);


        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);

        $response->assertExactJson([
            'unlocked_achievements' => ['First Watched'],
            'next_available_achievements' => [
                '5 Watched',
                '10 Watched',
                '20 Watched',
                '25 Watched',
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => [
                'Intermediate',
                'Advanced',
                'Master',
            ],
        ]);
    });
    it('Should unlock 25 Lessons Watched and 20 comment written', function () {
        $user = User::factory()->create();

        $lesson = new Lesson();
        $lesson->title = 'this is the lesson';
        $lesson->save();

        $lesson_user = new LessonUser();

        for ($i = 0; $i <= 25; $i++) {
            DB::table((new LessonUser())->getTable())->insert(['lesson_id' => $lesson->id, 'user_id' => $user->id]);
        }
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);

        $response->assertJson([
            'unlocked_achievements' => ['20 comments Written', '25 Watched'],
            'next_available_achievements' => [],
            'current_badge' => 'Master',
            'next_badge' => null,
            'remaining_to_unlock_next_badge' => []
        ]);
    });
});
