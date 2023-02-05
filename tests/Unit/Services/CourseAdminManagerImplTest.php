<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Unit\Services;

use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Repositories\Interfaces\CourseRepository;
use App\Services\Implementations\CourseAdminManagerImpl;
use Tests\TestCase;
use function collect;

/**
 * Description of CourseAdminManagerImpl
 *
 * @author piero
 */
class CourseAdminManagerImplTest extends TestCase{

    private CourseAdminManagerImpl $sut;
    private CourseRepository $courseRepo;
    

    protected function setUp(): void {
        parent::setUp();
        $this->courseRepo = $this->createMock(CourseRepository::class);

        $this->sut = new CourseAdminManagerImpl($this->courseRepo);
    }


    public function test_getCourseFullDepth_orderChildren(){
        $course = new Course();
        $eb1 = new ExamBlock(["id" => 5,
            "course_id" => $course->id]);
        $eb2 = new ExamBlock(["id" => 2,
            "course_id" => $course->id]);
        $ex1 = new Exam(["name" => "Z test", "exam_block_id" => $eb1->id]);
        $ex2 = new Exam(["name" => "A test", "exam_block_id" => $eb1->id]);
        $eb1->setRelation("exams", collect([$ex1, $ex2]));
        $eb2->setRelation("exams", collect([]));
        $course->setRelation("examBlocks",collect([$eb1,$eb2]));
        
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->with(5, true)
                ->willReturn($course);
        
        $result = $this->sut->getCourseFullDepth(5);
        
        $this->assertSame($course, $result);
        $this->assertSame($course->examBlocks->first(), $eb2);
        $this->assertSame($course->examBlocks->get(1), $eb1);
        $this->assertSame($course->examBlocks->get(1)->exams->first(), $ex2);
        $this->assertSame($course->examBlocks->get(1)->exams->get(1), $ex1);
    }
    
    public function test_getCourse(){
        $course = new Course();
        
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->with(7, false)
                ->willReturn($course);
        
        $result = $this->sut->getCourse(7);
        
        $this->assertSame($course, $result);
    }
}
