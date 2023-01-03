<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Unit\Services;

use App\Domain\NewExamInfo;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\Ssd;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\ExamRepository;
use App\Repositories\Interfaces\SSDRepository;
use App\Services\Implementations\CourseAdminManagerImpl;
use Tests\TestCase;

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

    public function test_saveExam_withMissingSsd(){
        $examInfo = new NewExamInfo("test name", "inf/02");
        $this->ssdRepo->expects($this->once())
                ->method("getSsdFromCode")
                ->with("INF/02")
                ->willReturn(null);
        
        $this->expectException(SsdNotFoundException::class);
        $this->sut->saveExam($examInfo,2);
    }

    public function test_saveExam_missingBlock_shouldThrow(){
        $examInfo = new NewExamInfo("test name", "inf/02");
        $ssd = new Ssd();
        
        $this->ssdRepo->expects($this->once())
                ->method("getSsdFromCode")
                ->with("INF/02")
                ->willReturn($ssd);
              
        $this->ebRepo->expects($this->once())
                ->method("get")
                ->with(2)
                ->willReturn(null);

        $this->expectException(ExamBlockNotFoundException::class);
        $this->sut->saveExam($examInfo,2);
    }
    public function test_saveExam_success(){
        $examInfo = new NewExamInfo("test name", "inf/02");
        $exam = new Exam([
            "id" => null,
            "name" => "test name",
            "ssd" => "INF/02"
        ]);
        $ssd = new Ssd(["id" => 11]);
        $examBlock = new ExamBlock();
        $examBlock->id = 7;
        
        $this->ssdRepo->expects($this->once())
                ->method("getSsdFromCode")
                ->with("INF/02")
                ->willReturn($ssd);
        
        $this->ebRepo->expects($this->once())
                ->method("get")
                ->with(2)
                ->willReturn($examBlock);

        $modelExam = new Exam([
            "name" => "test name",
            "ssd_id" => 11]);
        $savedExam = new Exam([
            "name" => "test name",
            "ssd_id" => 11]);
        $savedExam->id = 5;
        $this->examRepo->expects($this->once())
                ->method("save")
                ->with($modelExam)
                ->willReturn($savedExam);

        $this->ebRepo->expects($this->once())
                ->method("attachExam")
                ->with(7,5)
                ->willReturn(true);
        
        $result = $this->sut->saveExam($examInfo,2);

        $this->assertEquals($result, $savedExam);
    }    

}
