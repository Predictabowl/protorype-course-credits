<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Services;

use App\Domain\TakenExamDTO;
use App\Exceptions\Custom\CourseNotFoundException;
use App\Mappers\Interfaces\TakenExamMapper;
use App\Models\Course;
use App\Models\Front;
use App\Models\TakenExam;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Interfaces\SSDRepository;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Services\Implementations\FrontManagerImpl;
use Tests\TestCase;
use function collect;

/**
 * Description of FrontManagerImplTest
 *
 * @author piero
 */
class FrontManagerImplTest extends TestCase{

    private const FIXTURE_USER_ID = 13;
    private const FIXTURE_FRONT_ID = 7;

    private TakenExamRepository $takenRepo;
    private FrontRepository $frontRepo;
    private FrontManagerImpl $sut;
    private TakenExamMapper $mapper;
    private CourseRepository $courseRepo;
    private SSDRepository $ssdRepo;


    protected function setUp():void
    {
        parent::setUp();
        $this->takenRepo = $this->createMock(TakenExamRepository::class);
        $this->frontRepo = $this->createMock(FrontRepository::class);
        $this->mapper = $this->createMock(TakenExamMapper::class);
        $this->courseRepo = $this->createMock(CourseRepository::class);
        $this->ssdRepo = $this->createMock(SSDRepository::class);

        $this->sut = new FrontManagerImpl($this->mapper,
                $this->takenRepo, $this->frontRepo, $this->courseRepo,
                $this->ssdRepo);
    }


    public function test_getTakenExams() {
        $returned= [new TakenExamDTO(1, "name", "ssd1", 6, 25),
                new TakenExamDTO(2, "name 2", "ssd2", 9, 24)];

        $exams = collect([$this->makeTakenExam(1),$this->makeTakenExam(2)]);
        $this->mapper->expects($this->exactly(2))
                ->method("toDTO")
                ->withConsecutive([$exams[0]],[$exams[1]])
                ->willReturnOnConsecutiveCalls($returned[0],$returned[1]);

        $this->takenRepo->expects($this->once())
                ->method("getFromFront")
                ->with(self::FIXTURE_FRONT_ID)
                ->willReturn($exams);

        $result = $this->sut->getTakenExams(self::FIXTURE_FRONT_ID);

        $this->assertEquals($returned, $result->toArray());

    }

    public function test_save_takenExam_success() {
        $attributes = [
            "name" => "nome",
            "ssd" => "ssd2",
            "cfu" => 9,
            "grade" => 22
        ];

        $dto = new TakenExamDTO(0,"nome","ssd2",9, 22);
        $model = $this->makeTakenExam(13);
        $this->mapper->expects($this->once())
                ->method("toModel")
                ->with($dto)
                ->willReturn($model);
        $this->takenRepo->expects($this->once())
                ->method("save")
                ->with($model);

        $this->sut->saveTakenExam($attributes, self::FIXTURE_FRONT_ID);
    }

    public function test_delete_takenExam() {
        $exam = new TakenExam([
            "id" => 13,
            "front_id" => self::FIXTURE_FRONT_ID]);
        $this->takenRepo->expects($this->once())
                ->method("delete")
                ->with($exam->id);

        $this->sut->deleteTakenExam($exam->id);
    }

    public function test_setCourse_success() {
        $courseId = 3;
        $this->frontRepo->expects($this->once())
                ->method("updateCourse")
                ->with(self::FIXTURE_FRONT_ID,$courseId)
                ->willReturn(new Front());

        $result = $this->sut->setCourse(self::FIXTURE_FRONT_ID, $courseId);

        $this->assertTrue($result);
    }

    public function test_setCourse_failure() {
        $courseId = 7;
        $this->frontRepo->expects($this->once())
                ->method("updateCourse")
                ->with(self::FIXTURE_FRONT_ID,$courseId)
                ->willReturn(null);

        $result = $this->sut->setCourse(self::FIXTURE_FRONT_ID, $courseId);

        $this->assertFalse($result);
    }

    public function test_deleteAllTakenExams(){
        $this->takenRepo->expects($this->once())
                ->method("deleteFromFront")
                ->with(self::FIXTURE_FRONT_ID)
                ->willReturn(true);

        $this->sut->deleteAllTakenExams(self::FIXTURE_FRONT_ID);
    }
    
    public function test_getFront(){
        $front = new Front();
        
        $this->frontRepo->expects($this->once())
                ->method("get")
                ->with(self::FIXTURE_FRONT_ID)
                ->willReturn($front);
        
        $result = $this->sut->getFront(self::FIXTURE_FRONT_ID);
        
        $this->assertSame($front, $result);
    }
    
   public function test_getOrCreateFront_whenFront_notPresent(){
        $toSave = new Front([
            "user_id" => self::FIXTURE_USER_ID,
            "course_id" => 3
        ]);
        $saved = new Front([
            "id" => 5,
            "user_id" => self::FIXTURE_USER_ID,
            "course_id" => 3
        ]);
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn(null);
        $this->frontRepo->expects($this->once())
                ->method("save")
                ->with($toSave)
                ->willReturn($saved);
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->with(3)
                ->willReturn(new Course());
                
        $result = $this->sut->getOrCreateFront(self::FIXTURE_USER_ID, 3);
        
        $this->assertSame($saved,$result);
    }
    
    public function test_getOrCreateFront_when_Front_exists_and_course_is_changed(){
        $found = new Front([
            "user_id" => self::FIXTURE_USER_ID,
            "course_id" => 3
        ]);
        $found->id = 5;
        $saved = new Front([
            "user_id" => self::FIXTURE_USER_ID,
            "course_id" => 7
        ]);
        $saved->id = 5;
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn($found);
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->with(7)
                ->willReturn(new Course());
        $this->frontRepo->expects($this->once())
                ->method("updateCourse")
                ->with(5,7)
                ->willReturn($saved);
        $this->frontRepo->expects($this->never())
                ->method("save");
        
        $result = $this->sut->getOrCreateFront(self::FIXTURE_USER_ID, 7);
        
        $this->assertSame($saved, $result);
    }
    
    public function test_getOrCreateFront_when_Front_exists_and_course_is_not_changed(){
        $found = new Front([
            "user_id" => self::FIXTURE_USER_ID,
            "course_id" => 3
        ]);
        $found->id = 5;
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn($found);
        $this->frontRepo->expects($this->never())
                ->method("updateCourse");
        $this->frontRepo->expects($this->never())
                ->method("save");
        $this->courseRepo->expects($this->never())
                ->method("get");
        
        $result = $this->sut->getOrCreateFront(self::FIXTURE_USER_ID, 3);
        
        $this->assertSame($found, $result);
    }
    
    public function test_getOrCreateFront_whenFrontExists_and_courseIsNull(){
        $found = new Front([
            "user_id" => self::FIXTURE_USER_ID,
            "course_id" => 3
        ]);
        $found->id = 5;
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn($found);
        $this->frontRepo->expects($this->never())
                ->method("updateCourse");
        $this->frontRepo->expects($this->never())
                ->method("save");
        $this->courseRepo->expects($this->never())
                ->method("get");
        
        $result = $this->sut->getOrCreateFront(self::FIXTURE_USER_ID, null);
        
        $this->assertSame($found, $result);
    }
    
    public function test_getOrCreateFront_courseIsMissing(){
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn(null);
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->with(3)
                ->willReturn(null);
        $this->frontRepo->expects($this->never())
                ->method("updateCourse");
        $this->frontRepo->expects($this->never())
                ->method("save");

        $this->expectException(CourseNotFoundException::class);
        $this->sut->getOrCreateFront(self::FIXTURE_USER_ID, 3);
        
    }
    
    public function test_getAllSsds(){
        $ssdColl = collect(["something"]);
        $this->ssdRepo->expects($this->once())
                ->method("getAll")
                ->willReturn($ssdColl);
        
        $result = $this->sut->getAllSSds();
        
        $this->assertSame($ssdColl, $result);
    }

    private function makeTakenExam($id =1): TakenExam {
        $mock = new TakenExam();
        $mock->id = $id;
        return $mock;
    }

}
