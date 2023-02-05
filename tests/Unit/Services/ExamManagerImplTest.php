<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Unit\Services;

use App\Domain\NewExamInfo;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Mappers\Interfaces\ExamInfoMapper;
use App\Models\Exam;
use App\Models\ExamBlock;
use App\Models\Ssd;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\ExamRepository;
use App\Repositories\Interfaces\SSDRepository;
use App\Services\Implementations\ExamManagerImpl;
use Tests\TestCase;

/**
 * Description of CourseAdminManagerImpl
 *
 * @author piero
 */
class ExamManagerImplTest extends TestCase{

    private ExamManagerImpl $sut;
    private ExamBlockRepository $ebRepo;
    private ExamRepository $examRepo;
    private SSDRepository $ssdRepo;
    private ExamInfoMapper $examMapper;
    

    protected function setUp(): void {
        parent::setUp();
        $this->ebRepo = $this->createMock(ExamBlockRepository::class);
        $this->examRepo = $this->createMock(ExamRepository::class);
        $this->ssdRepo = $this->createMock(SSDRepository::class);
        $this->examMapper = $this->createMock(ExamInfoMapper::class);

        $this->sut = new ExamManagerImpl($this->examRepo, $this->ebRepo, 
                $this->ssdRepo, $this->examMapper);
    }

    public function test_saveExam_withMissingSsd(){
        $examInfo = new NewExamInfo("test name", "inf/02");
        $this->ssdRepo->expects($this->once())
                ->method("getSsdFromCode")
                ->with("INF/02");
        
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

        $createdExam = new Exam();
        
        $this->examRepo->expects($this->once())
                ->method("save")
                ->with($exam)
                ->willReturn($createdExam);

        $result = $this->sut->saveExam($examInfo,2);
        
        $this->assertSame($createdExam, $result);
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
        $createdExam = new Exam();
        $this->examRepo->expects($this->once())
                ->method("save")
                ->with($exam)
                ->willReturn($createdExam);

        $result = $this->sut->saveExam($examInfo,2);
        
        $this->assertSame($createdExam, $result);
    }

    
    public function test_deleteExam(){
        $this->examRepo->expects($this->once())
                ->method("delete")
                ->with(3);
        
        $this->sut->deleteExam(3);
    }
    
    public function test_updateExam_withSsdMissing_shouldThrow(){
        $examInfo = new NewExamInfo("new name", "IUS/01");
        $examId = 3;
        $this->ssdRepo->expects($this->once())
                ->method("getSsdFromCode")
                ->with("IUS/01")
                ->willReturn(null);        
        $this->examRepo->expects($this->never())
                ->method("update");
        
        $this->expectException(SsdNotFoundException::class);
        $this->sut->updateExam($examInfo, $examId);
    }
    
    public function test_updateExam_success(){
        $examInfo = new NewExamInfo("new name", "IUS/01");
        $examId = 3;
        $this->ssdRepo->expects($this->once())
                ->method("getSsdFromCode")
                ->with("IUS/01")
                ->willReturn(new Ssd(["id" => 11]));        
        $this->examMapper->expects($this->once())
                ->method("map")
                ->with($examInfo, null, 11)
                ->willReturn(new Exam());
        $updatedExam = new Exam(["id" => $examId]);
        $this->examRepo->expects($this->once())
                ->method("update")
                ->with($updatedExam)
                ->willReturn($updatedExam);
        
        $result = $this->sut->updateExam($examInfo, $examId);
        
        $this->assertSame($updatedExam, $result);
    }      
    
    public function test_updateExam_withSsd_NullId(){
        $examInfo = new NewExamInfo("new name", null, true);
        $examId = 3;
        $this->ssdRepo->expects($this->never())
                ->method("getSsdFromCode");
        $this->examMapper->expects($this->once())
                ->method("map")
                ->with($examInfo, null, null)
                ->willReturn(new Exam());
        $updatedExam = new Exam(["id" => $examId]);
        $this->examRepo->expects($this->once())
                ->method("update")
                ->with($updatedExam)
                ->willReturn($updatedExam);
        
        $result = $this->sut->updateExam($examInfo, $examId);
        
        $this->assertSame($updatedExam, $result);
    } 
}
