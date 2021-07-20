<?php

namespace Tests\Feature\Mappers;


use App\Models\TakenExam;
use App\Models\Ssd;
use App\Models\User;
use App\Models\Front;
use App\Domain\TakenExamDTO;
use App\Mappers\Implementations\TakenExamMapperImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class TakenExamMapperImplTest extends TestCase
{
    use RefreshDatabase;
    
    private $mapper;
    
    protected function setUp(): void {
        parent::setUp();
        $this->mapper = new TakenExamMapperImpl();
    }
    
    public function test_to_dto(){
        $ssd = Ssd::factory()->create();
        $model = TakenExam::factory()->make([
            "name" => "test name",
            "cfu" => 7,
            "ssd_id" => $ssd
        ]);
        $model->id = 5;
        
        $exam = new TakenExamDTO(5, "test name", $ssd->code, 7);

        $dto = $this->mapper->toDTO($model);
        
        $this->assertEquals($exam, $dto);
    }
    
    public function test_toModel_with_no_Ssd() {
        $dto = new TakenExamDTO(13, "test name", "IUS/07", 5);
        
        $result = $this->mapper->toModel($dto,1);
        
        $this->assertNull($result);
    }
    
    public function test_toModel_success() {
        User::factory()->create();
        $ssd = Ssd::factory()->create([
            "code" => "IUS/07"
        ]);
        $front = new Front([
            "course_id" => null,
            "user_id" => 1,
        ]);
        $front->id = 3;
        $front->save();
        
        
        $dto = new TakenExamDTO(13, "test name", "IUS/07", 5);
        
        $this->mapper->toModel($dto,3)->save();
        
        $this->assertDatabaseHas("taken_exams", [
            "id" => 1,
            "front_id" => 3,
            "name" => "test name",
            "cfu" => 5,
            "ssd_id" => 1
        ]);
    }
 
}
