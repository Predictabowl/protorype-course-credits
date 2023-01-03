<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Services;

use App\Domain\TakenExamDTO;
use App\Mappers\Interfaces\TakenExamMapper;
use App\Models\Course;
use App\Models\Front;
use App\Models\TakenExam;
use App\Repositories\Interfaces\CourseRepository;
use App\Repositories\Interfaces\FrontRepository;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Services\Implementations\FrontManagerImpl;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use function collect;

/**
 * Description of FrontManagerImplTest
 *
 * @author piero
 */
class FrontManagerImplTest extends TestCase{

    private const FIXTURE_FRONT_ID = 7;

    private TakenExamRepository $takenRepo;
    private FrontRepository $frontRepo;
    private FrontManagerImpl $manager;
    private TakenExamMapper $mapper;
    private CourseRepository $courseRepo;


    protected function setUp():void
    {
        parent::setUp();
        $this->takenRepo = $this->createMock(TakenExamRepository::class);
        $this->frontRepo = $this->createMock(FrontRepository::class);
        $this->mapper = $this->createMock(TakenExamMapper::class);
        $this->courseRepo = $this->createMock(CourseRepository::class);

        $this->manager = new FrontManagerImpl(self::FIXTURE_FRONT_ID, $this->mapper,
                $this->takenRepo, $this->frontRepo, $this->courseRepo);
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
                ->willReturn($exams);

        $sut = $this->manager->getTakenExams();

        $this->assertEquals($returned, $sut->toArray());

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

        $this->manager->saveTakenExam($attributes);
    }

    public function test_delete_takenExam() {
        $exam = new TakenExam(["front_id" => self::FIXTURE_FRONT_ID]);
        $this->takenRepo->expects($this->once())
                ->method("delete")
                ->with($exam->id);

        $this->manager->deleteTakenExam($exam->id);
    }

    public function test_setCourse_success() {
        $courseId = 3;
        $this->frontRepo->expects($this->once())
                ->method("updateCourse")
                ->with(self::FIXTURE_FRONT_ID,$courseId)
                ->willReturn(new Front());

        $result = $this->manager->setCourse($courseId);

        $this->assertTrue($result);
    }

    public function test_setCourse_failure() {
        $courseId = 7;
        $this->frontRepo->expects($this->once())
                ->method("updateCourse")
                ->with(self::FIXTURE_FRONT_ID,$courseId)
                ->willReturn(null);

        $result = $this->manager->setCourse($courseId);

        $this->assertFalse($result);
    }

    public function test_getCourses(){
        $course1 = new Course(["name" => "element"]);
        $course2 = new Course(["name" => "another"]);
        $courses = new Collection([$course1, $course2]);
        $this->courseRepo->expects($this->once())
                ->method("getAll")
                ->willReturn($courses);

        $result = $this->manager->getCourses();
        $this->assertEquals(collect([$course2, $course1]), $result);
    }

    public function test_deleteAllTakenExams(){
        $this->takenRepo->expects($this->once())
                ->method("deleteFromFront")
                ->with(self::FIXTURE_FRONT_ID)
                ->willReturn(true);

        $this->manager->deleteAllTakenExams();
    }


    private function makeTakenExam($id =1): TakenExam {
        $mock = new TakenExam();
        $mock->id = $id;
        return $mock;
    }

}
