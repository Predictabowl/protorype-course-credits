<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Exam;
use App\Service\Implementations\ExamManagerImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamManagerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_attach()
    {

        $this->withoutExceptionHandling();

        $em = new ExamManagerImpl();
        $exam = Exam::factory()->create();

        $course = Course::factory()->create();
        $em->attach($exam,$course);
        
        $this->assertDatabaseHas("course_exam", [
            "course_id" => $course->id,
            "exam_id" => $exam->id
        ]);
    }
}
