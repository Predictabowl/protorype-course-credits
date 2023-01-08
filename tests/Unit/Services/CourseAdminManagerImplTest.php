<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Unit\Services;

use App\Domain\NewExamBlockInfo;
use App\Domain\NewExamInfo;
use App\Exceptions\Custom\CourseNotFoundException;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Mappers\Interfaces\ExamBlockInfoMapper;
use App\Mappers\Interfaces\ExamInfoMapper;
use App\Models\Course;
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
    private ExamBlockInfoMapper $ebMapper;
    private ExamInfoMapper $examMapper;
    

    protected function setUp(): void {
        parent::setUp();
        $this->courseRepo = $this->createMock(CourseRepository::class);
        $this->ebRepo = $this->createMock(ExamBlockRepository::class);
        $this->examRepo = $this->createMock(ExamRepository::class);
        $this->ssdRepo = $this->createMock(SSDRepository::class);
        $this->ebMapper = $this->createMock(ExamBlockInfoMapper::class);
        $this->examMapper = $this->createMock(ExamInfoMapper::class);

        $this->sut = new CourseAdminManagerImpl($this->courseRepo, $this->ebRepo, 
                $this->examRepo, $this->ssdRepo, $this->ebMapper, $this->examMapper);
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
        $examInfo = new NewExamInfo("test name", "inf/02", false);
        $ssd = new Ssd(["id" => 3]);
        
        $this->ssdRepo->expects($this->once())
                ->method("getSsdFromCode")
                ->with("INF/02")
                ->willReturn($ssd);
        
        $this->ebRepo->expects($this->once())
                ->method("get")
                ->with(2)
                ->willReturn(new ExamBlock());
        $exam = new Exam([
            "ssd_id" => 5,
            "exam_block_id" => 11
        ]);
        $this->examMapper->expects($this->once())
                ->method("map")
                ->with($examInfo, 2, 3)
                ->willReturn($exam);

        $this->examRepo->expects($this->once())
                ->method("save")
                ->with($exam)
                ->willReturn(new Exam());

        $this->sut->saveExam($examInfo,2);
    }
    
    public function test_saveFreeChoiceExam_success(){
        $examInfo = new NewExamInfo("test name", "inf/02", true);
        $this->ssdRepo->expects($this->never())
                ->method("getSsdFromCode");
        $exam = new Exam([
            "ssd_id" => null,
            "exam_block_id" => 11
        ]);
        $this->examMapper->expects($this->once())
                ->method("map")
                ->with($examInfo, 2, null)
                ->willReturn($exam);
        $this->ebRepo->expects($this->once())
                ->method("get")
                ->with(2)
                ->willReturn(new ExamBlock());
        $this->examRepo->expects($this->once())
                ->method("save")
                ->with($exam)
                ->willReturn(new Exam());

        $this->sut->saveExam($examInfo,2);
    }

    public function test_getCourseFullData(){
        $course = new Course();
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->with(5, true)
                ->willReturn($course);
        
        $result = $this->sut->getCourseFullData(5);
        
        $this->assertSame($course, $result);
    }
    
    public function test_saveExamBlock_withMissingCourse_shouldThrow(){
        $examBlock = new NewExamBlockInfo(2, 6, 2);
        
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->with(7)
                ->willReturn(null);
        
        $this->ebRepo->expects($this->never())
                ->method("save");
        
        $this->expectException(CourseNotFoundException::class);
        $this->sut->saveExamBlock($examBlock, 7);
    }
    
    public function test_saveExamBlock_success(){
        $courseId = 7;
        $ebInfo = new NewExamBlockInfo(2, 6, 3);
        
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->with($courseId)
                ->willReturn(new course());
        $examBlock = new ExamBlock(["name" => "test name"]);
        $this->ebMapper->expects($this->once())
                ->method("map")
                ->with($ebInfo, $courseId)
                ->willReturn($examBlock);
        $this->ebRepo->expects($this->once())
                ->method("save")
                ->with($examBlock)
                ->willReturn(true);

        $this->sut->saveExamBlock($ebInfo, $courseId);
    }
}
