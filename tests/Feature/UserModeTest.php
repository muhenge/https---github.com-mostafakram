<?php

namespace Tests\Feature;

use App\Models\Lesson;
use App\Models\LessonUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
class UserTest extends TestCase
{
    public function user_can_add_comments()
    {
        $user = User::factory()->create();
        $comment = $user->comments()->create([
            'body' => 'This is a test comment.',
        ]);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals('This is a test comment.', $comment->body);
        $this->assertEquals($user->id, $comment->user_id);
    }

    public function test_user_can_have_lessons()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        LessonUser::factory()->create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'watched' => true,
        ]);

        $lessons = $user->lessons;

        $this->assertTrue($lessons->contains($lesson));
    }

    public function test_user_route_key_name()
    {
        $user = new User();
        $this->assertEquals('id', $user->getRouteKeyName());
    }
}
