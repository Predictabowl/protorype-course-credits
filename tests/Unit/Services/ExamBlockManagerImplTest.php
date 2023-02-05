<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Tests\Unit\Services;

use App\Domain\NewExamBlockInfo;
use App\Exceptions\Custom\CourseNotFoundException;
use App\Mappers\Interfaces\ExamBlockInfoMapper;
use App\Mappers\Interfaces\ExamInfoMapper;
use App\Models\Course;
use App\Models\ExamBlock;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\ExamRepository;
use App\Repositories\Interfaces\SSDRepository;
use App\Services\Implementations\ExamBlockManagerImpl;
use Tests\TestCase;

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
}
