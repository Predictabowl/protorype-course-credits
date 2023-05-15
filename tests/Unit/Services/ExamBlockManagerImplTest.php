<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Unit\Services;

use App\Domain\NewExamBlockInfo;
use App\Domain\SsdCode;
use App\Exceptions\Custom\CourseNotFoundException;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Mappers\Interfaces\ExamBlockInfoMapper;
use App\Models\Course;
use App\Models\ExamBlock;
use App\Models\Ssd;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\SSDRepository;
use App\Services\Implementations\ExamBlockManagerImpl;
use Tests\TestCase;
use function collect;

/**
 * Description of CourseAdminManagerImpl
 *
 * @author piero
 */
class ExamBlockManagerImplTest extends TestCase{

    private ExamBlockManagerImpl $sut;
    private CourseRepository $courseRepo;
    private ExamBlockRepository $ebRepo;
    private SSDRepository $ssdRepo;
    private ExamBlockInfoMapper $ebMapper;
    

    protected function setUp(): void {
        parent::setUp();
        $this->courseRepo = $this->createMock(CourseRepository::class);
        $this->ebRepo = $this->createMock(ExamBlockRepository::class);
        $this->ssdRepo = $this->createMock(SSDRepository::class);
        $this->ebMapper = $this->createMock(ExamBlockInfoMapper::class);

        $this->sut = new ExamBlockManagerImpl($this->courseRepo, $this->ebRepo, 
                $this->ssdRepo, $this->ebMapper);
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
                ->willReturn(new Course());
        $examBlock = ExamBlock::make([
                "id" => 5,
                "course_id" => $courseId,
                "max_exams" => 2
            ]);
        $this->ebMapper->expects($this->once())
                ->method("map")
                ->with($ebInfo, $courseId)
                ->willReturn($examBlock);
        
        $savedBlock = ExamBlock::make([
                "id" => 7,
                "course_id" => $courseId,
                "max_exams" => 1
            ]);
        $this->ebRepo->expects($this->once())
                ->method("save")
                ->with($examBlock)
                ->willReturn($savedBlock);

        $result = $this->sut->saveExamBlock($ebInfo, $courseId);
        
        $this->assertSame($savedBlock,$result);
    }
    
    public function test_deleteExamBlock(){
        $this->ebRepo->expects($this->once())
                ->method("delete")
                ->with(5);
        
        $this->sut->deleteExamBlock(5);
    }
    
 
    public function test_updateExamBlock_success() {
        $ebInfo = new NewExamBlockInfo(3, 9, 2);
        $ebId = 3;
        $this->ebMapper->expects($this->once())
                ->method("map")
                ->with($ebInfo, null)
                ->willReturn(new ExamBlock());
        $this->ebRepo->expects($this->once())
                ->method("update")
                ->with(new ExamBlock(["id" => $ebId]));
        
        $this->sut->updateExamBlock($ebInfo, $ebId);
    }
    
    public function test_addSsd_whenMissing(){
        $ssdCode = new SsdCode("inf/02");
        
        $this->ssdRepo->expects($this->once())
                ->method("getSsdFromCodeWithExamBlocks")
                ->with($ssdCode->getCode())
                ->willReturn(null);
        
        $this->ebRepo->expects($this->never())
                ->method("attachSsd");
        
        $this->expectException(SsdNotFoundException::class);
        $this->sut->addSsd(3, $ssdCode);
    }
    
    public function test_addSsd_success(){
        $ssdCode = new SsdCode("inf/01");
        $ssd = new Ssd([
            "id" => 7,
            "code" => $ssdCode
        ]);
        $ssd->setRelation("examBlocks", collect([]));
       
        $this->ssdRepo->expects($this->once())
                ->method("getSsdFromCodeWithExamBlocks")
                ->with($ssdCode->getCode())
                ->willReturn($ssd);
        
        $this->ebRepo->expects($this->once())
                ->method("attachSsd")
                ->with(3, 7);
        
        $this->sut->addSsd(3, $ssdCode);
    }
    
    public function test_addSsd_whenAlreadyPresent(){
        $ssdCode = new SsdCode("INF/01");
        $ssd = new Ssd([
            "id" => 7,
            "code" => $ssdCode
        ]);
        $examBlock = new ExamBlock([
            "id" => 3
        ]);
        $ssd->setRelation("examBlocks", collect([$examBlock]));
       
        $this->ssdRepo->expects($this->once())
                ->method("getSsdFromCodeWithExamBlocks")
                ->with($ssdCode->getCode())
                ->willReturn($ssd);
        
        $this->ebRepo->expects($this->never())
                ->method("attachSsd");
        
        $this->sut->addSsd(3, $ssdCode);
    }
    
    public function test_removeSsd_success(){
        $this->ebRepo->expects($this->once())
                ->method("detachSsd")
                ->with(11, 17);
        
        $this->sut->removeSsd(11, 17);
    }
    
    public function test_getExamBlockWithSsds_whenExamBlockMissing(){
        $this->ebRepo->expects($this->once())
                ->method("getWithSsds")
                ->with(11)
                ->willReturn(null);
        
        $this->expectException(ExamBlockNotFoundException::class);
        $this->sut->getExamBlockWithSsds(11);
    }
    
    public function test_getExamBlockWithSsds_success(){
        $examBlock = new ExamBlock();
        $this->ebRepo->expects($this->once())
                ->method("getWithSsds")
                ->with(11)
                ->willReturn($examBlock);
        
        $result = $this->sut->getExamBlockWithSsds(11);
        
        $this->assertSame($examBlock, $result);
    }
    
    public function test_getAllSsds(){
        $allSsds = collect([]);
        $this->ssdRepo->expects($this->once())
                ->method("getAll")
                ->willReturn($allSsds);
        
        $result = $this->sut->getAllSsds();
        
        $this->assertSame($allSsds, $result);
    }

}
