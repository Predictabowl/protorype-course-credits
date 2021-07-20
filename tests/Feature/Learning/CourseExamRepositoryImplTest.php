<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Exam;
use App\Repositories\Implementations\CourseExamRepositoryImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseExamRepositoryImplTest extends TestCase
{
    use RefreshDatabase;

    private $repository;
    
    function __construct()
    {
        parent::__construct();
        $this->repository = new CourseExamRepositoryImpl();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create()
    {
        $this->withoutExceptionHandling();
        $this->seed();


        $exam = Exam::first();
        $course = Course::first();

        $this->repository->create($course, $exam);

        $this->assertDatabaseHas("course_exam", [
            "course_id" => $course->id,
            "exam_id" => $exam->id
        ]);

        /*        ExamManagerImpl::attach($exam,$course);
                $this->assertDatabaseHas("course_exam", [
                    "course_id" => $course->id,
                    "exam_id" => $exam->id
                ]);
                */
    }
}
