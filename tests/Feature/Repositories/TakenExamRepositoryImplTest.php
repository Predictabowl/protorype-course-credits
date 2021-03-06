<?php

namespace Tests\Feature\Repositories;

use App\Repositories\Implementations\TakenExamRespositoryImpl;
use App\Models\TakenExam;
use App\Models\Ssd;
use App\Models\User;
use App\Models\Course;
use App\Models\Front;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TakenExamRepositoryImplTest extends TestCase {

    use RefreshDatabase;

    private $repository;

    protected function setUp(): void {
        parent::setUp();
        $this->repository = new TakenExamRespositoryImpl();
    }

    public function test_get_when_not_present() {
        $sut = $this->repository->get(1);

        $this->assertNull($sut);
    }

    public function test_get_successful() {
        $taken = TakenExam::factory()->create([
            "ssd_id" => Ssd::factory()->create(),
            "front_id" => Front::factory()->create([
                "user_id" => User::factory()->create(),
                "course_id" => Course::factory()->create()
            ])
        ]);

        $sut = $this->repository->get(1);

        $this->assertEquals(
                [$taken->id, $taken->ssd, $taken->cfu, $taken->name],
                [$sut->id, $sut->ssd, $sut->cfu, $sut->name]);
    }

    public function test_getFromFront_when_empty() {
        Ssd::factory(3)->create();
        $front = Front::factory()->create([
            "user_id" => User::factory()->create(),
            "course_id" => Course::factory()->create()
        ]);

        $sut = $this->repository->getFromFront($front->id);

        $this->assertEmpty($sut);
    }

    public function test_getFrontFront_success() {
        Ssd::factory(3)->create();
        $front = Front::factory()->create([
            "user_id" => User::factory()->create(),
            "course_id" => Course::factory()->create()
        ]);

        $expected = TakenExam::factory(3)->create([
            "front_id" => $front
        ]);

        $found = $this->repository->getFromFront($front->id);

        $this->assertCount(3, $found);
        $this->assertContainsOnlyInstancesOf(TakenExam::class, $found);
        $this->assertEquals([$expected[0]->id, $expected[1]->id, $expected[2]->id],
                [$found[0]->id, $found[1]->id, $found[2]->id]);
    }

    public function test_getFrontFront_when_front_not_present() {
        $found = $this->repository->getFromFront(1);
        
        $this->assertEmpty($found);
    }

    public function test_getFrontFront_when_front_is_empty() {
        $front = Front::factory()->create([
            "user_id" => User::factory()->create(),
            "course_id" => Course::factory()->create()
        ]);

        $sut = $this->repository->getFromFront($front->id);

        $this->assertEmpty($sut);
    }

    public function test_save_success() {
        $ssd = Ssd::factory()->create();
        $front = Front::factory()->create([
            "user_id" => User::factory()->create(),
            "course_id" => Course::factory()->create()
        ]);
        $exam = new TakenExam([
            "name" => "test name",
            "ssd_id" => $ssd->id,
            "cfu" => 6,
            "front_id" => $front->id,
            "grade" => 19,
            "courseYear" => 2
        ]);

        $result = $this->repository->save($exam);

        $this->assertTrue($result);
        $this->assertDatabaseHas("taken_exams", [
            "front_id" => $front->id,
            "ssd_id" => $ssd->id,
            "name" => "test name",
            "cfu" => 6,
            "grade" => 19,
            "courseYear" => 2
        ]);
    }
    
    public function test_save_when_id_not_null_should_throw() {
        $ssd = Ssd::factory()->create();
        $front = Front::factory()->create([
            "user_id" => User::factory()->create(),
            "course_id" => Course::factory()->create()
        ]);
        $exam = new TakenExam([
            "name" => "test name",
            "ssd_id" => $ssd->id,
            "cfu" => 6,
            "front_id" => $front->id
        ]);
        $exam->id = 5;

        $this->expectException(\InvalidArgumentException::class);
        
        $result = $this->repository->save($exam);

        $this->assertFalse($result);
        $this->assertDatabaseCount("taken_exams", 0);
    }
    
    public function test_save_failure_due_to_integrity_violation() {
        $ssd = Ssd::factory()->create();
        $exam = new TakenExam([
            "name" => "test name",
            "ssd_id" => $ssd->id,
            "cfu" => 6,
            "front_id" => 2
        ]);

        $result = $this->repository->save($exam);

        $this->assertFalse($result);
        $this->assertDatabaseCount("taken_exams", 0);
    }

    public function test_delete_success() {
        $ssd = Ssd::factory()->create();
        $front = Front::factory()->create([
            "user_id" => User::factory()->create(),
            "course_id" => Course::factory()->create()
        ]);
        TakenExam::factory(2)->create();
        $taken = [
            "id" => 5,
            "name" => "Mario",
            "cfu" => 7,
            "ssd_id" => $ssd->id,
            "front_id" => $front->id,
        ];
        TakenExam::factory()->create($taken);

        $this->assertDatabaseHas("taken_exams", $taken);

        $result = $this->repository->delete(5);

        $this->assertTrue($result);
        $this->assertDatabaseCount("taken_exams", 2);
        $this->assertDatabaseMissing("taken_exams", $taken);
    }
    
    public function test_delete_failure() {
        $ssd = Ssd::factory()->create();
        $front = Front::factory()->create([
            "user_id" => User::factory()->create(),
            "course_id" => Course::factory()->create()
        ]);
        TakenExam::factory(2)->create();

        $result = $this->repository->delete(5);

        $this->assertFalse($result);
        $this->assertDatabaseCount("taken_exams", 2);
    }
    
     public function test_delete_multiple_entries() {
        $ssd = Ssd::factory()->create();
        $front = Front::factory()->create([
            "user_id" => User::factory()->create(),
            "course_id" => Course::factory()->create()
        ]);
        TakenExam::factory(4)->create();
        $exams = TakenExam::factory(3)->create(["front_id" => $front->id]);

        $examIds = $exams->map(fn(TakenExam $exam) => $exam->id);        

        $result = $this->repository->delete($examIds);

        $this->assertTrue($result);
        $this->assertDatabaseCount("taken_exams", 4);
        $exams = $exams->toArray();
        $this->assertDatabaseMissing("taken_exams", $exams[0]);
        $this->assertDatabaseMissing("taken_exams", $exams[1]);
        $this->assertDatabaseMissing("taken_exams", $exams[2]);
    }
    
    public function test_deleteFromFront_success(){
        $ssd = Ssd::factory()->create();
        $front = Front::factory()->create([
            "user_id" => User::factory()->create(),
            "course_id" => Course::factory()->create()
        ]);
        TakenExam::factory(4)->create(["front_id" => Front::factory()->create()]);
        $exams = TakenExam::factory(3)->create(["front_id" => $front->id]);

        $result = $this->repository->deleteFromFront($front->id);

        $this->assertTrue($result);
        $this->assertDatabaseCount("taken_exams", 4);
        $exams = $exams->toArray();
        $this->assertDatabaseMissing("taken_exams", $exams[0]);
        $this->assertDatabaseMissing("taken_exams", $exams[1]);
        $this->assertDatabaseMissing("taken_exams", $exams[2]);
    }
    
    public function test_deleteFromFront_failure(){

        $result = $this->repository->deleteFromFront(5);

        $this->assertFalse($result);
        $this->assertDatabaseCount("taken_exams", 0);
    }

}
