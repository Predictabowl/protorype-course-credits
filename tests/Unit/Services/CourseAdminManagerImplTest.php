<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Unit\Services;

use App\Domain\NewExamInfo;
use App\Models\Course;
use App\Models\Exam;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\ExamRepository;
use App\Services\Implementations\CourseAdminManagerImpl;
use App\Services\Interfaces\SSDRepository;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;
use function collect;

/**
 * Description of CourseAdminManagerImpl
 *
 * @author piero
 */
class CourseAdminManagerImplTest extends TestCase{

    private CourseAdminManagerImpl $sut;
    private CourseRepository $courseRepo;
    private ExamBlockRepository $ebRepo;
    private ExamRepository $examRepo;
    private SSDRepository $ssdRepo;

    protected function setUp(): void {
        parent::setUp();
        $this->courseRepo = $this->createMock(CourseRepository::class);
        $this->ebRepo = $this->createMock(ExamBlockRepository::class);
        $this->examRepo = $this->createMock(ExamRepository::class);
        $this->ssdRepo = $this->createMock(SSDRepository::class);

        $this->sut = new CourseAdminManagerImpl($this->courseRepo, $this->ebRepo, 
                $this->examRepo, $this->ssdRepo);
    }

    public function test_getAll_shouldSortByName(){
        $course1 = new Course([
            "id" => 1,
            "name" => "Zorro"
        ]);
        $course2 = new Course([
            "id" => 2,
            "name" => "Abele"
        ]);

        $courses = new Collection([$course1, $course2]);

        $this->courseRepo->expects($this->once())
                ->method("getAll")
                ->willReturn($courses);

        $result = $this->sut->getAll();

        $this->assertEquals(collect([$course2, $course1]),$result);
    }
    
    public function test_saveExam_withMissingSsd(){
        $examInfo = new NewExamInfo("test name", "inf/02");
        $this->ssdRepo->expects($this->once())
                ->method("getSsdFromCode")
                ->with("INF/02")
                ->willReturn(null);
        
        $result = $this->sut->saveExam($examInfo,2);
        
        $this->assertNull($result);
    }

    public function test_saveExam(){
        $examInfo = new NewExamInfo("test name", "inf/02");
        $exam = new Exam([
            "id" => null,
            "name" => "test name",
            "ssd" => "INF/02"
        ]);
        $ssd = new Ssd();
        
        $this->ssdRepo->expects($this->once())
                ->method("getSsdFromCode")
                ->with("INF/02")
                ->willReturn($ssd);
        
        $this->examRepo->expects($this->once())
            ->method("save")
            ->with($exam)
            ->willReturn(true);

        $result = $this->sut->saveExam($examInfo,2);

        $this->assertEquals($result, $exam);
    }

}
