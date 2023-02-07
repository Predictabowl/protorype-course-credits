<?php

namespace Tests\Feature\Controllers;

use App\Domain\SsdCode;
use App\Exceptions\Custom\ExamBlockNotFoundException;
use App\Exceptions\Custom\SsdNotFoundException;
use App\Models\Course;
use App\Models\ExamBlock;
use App\Models\Role;
use App\Models\Ssd;
use App\Models\User;
use App\Services\Interfaces\ExamBlockManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function app;
use function collect;
use function route;

class ExamBlockSsdControllerTest extends TestCase {

    use RefreshDatabase;

    const FIXTURE_START_URI = "course/examblock/ssds";

    private ExamBlockManager $ebManager;

    protected function setUp(): void {
        parent::setUp();

        ExamBlock::factory()->create([
            "course_id" => Course::factory()->create()
        ]);
        $this->ebManager= $this->createMock(ExamBlockManager::class);
        app()->instance(ExamBlockManager::class, $this->ebManager);
    }

    public function test_authorization_forbidden() {
        $this->be(User::factory()->create());

        $this->put(route("addExamBlockSsd", [ExamBlock::first()->id]))
            ->assertForbidden();
        $this->delete(route("delExamBlockSsd", [
                ExamBlock::first()->id,
                Ssd::factory()->create()->id]))
            ->assertForbidden();
    }
    
    public function test_put_whenExamBlock_isMissing(){
        $this->beAdmin();
        $ssdCode = new SsdCode("inf/03");
        
        $this->ebManager->expects($this->once())
                ->method("addSsd")
                ->with(3,$ssdCode)
                ->willThrowException(new ExamBlockNotFoundException("test error"));
        
        $this->put(route("addExamBlockSsd",[3]),["ssd" => $ssdCode->getCode()])
            ->assertNotFound();
    }

    
    public function test_put_success(){
        $this->beAdmin();
        $ssdCode = new SsdCode("IUS/01");
        
        $this->ebManager->expects($this->once())
                ->method("addSsd")
                ->with(5,$ssdCode);
        
        $examBlock = new ExamBlock(["id" => 5]);
        $examBlock->setRelation("ssds",collect([]));
        $this->ebManager->expects($this->once())
                ->method("getExamBlockWithSsds")
                ->with(5)
                ->willReturn($examBlock);
        
        $this->from(self::FIXTURE_START_URI)
                ->put(route("addExamBlockSsd",[5]),["ssd" => $ssdCode->getCode()])
                ->assertOk()
                ->assertViewIs("components.courses.exam-block-ssds")
                ->assertViewHas("examBlock", $examBlock);
    }
    
    public function test_put_invalidSsdCode(){
        $this->beAdmin();
        $ssdCode = "Weird Code";
        
        $this->ebManager->expects($this->never())
                ->method("addSsd");
        
        $this->ebManager->expects($this->never())
                ->method("getExamBlockWithSsds");
        
        $this->from(self::FIXTURE_START_URI)
                ->put(route("addExamBlockSsd",[5]),["ssd" => $ssdCode])
                ->assertUnprocessable()
                ->assertViewIs("components.courses.flash-error");
    }
    
    public function test_put_ssdCode_notFound(){
        $this->beAdmin();
        $ssdCode = "inf/02";
        
        $this->ebManager->expects($this->once())
                ->method("addSsd")
                ->with(7, new SsdCode($ssdCode))
                ->willThrowException(new SsdNotFoundException("test error"));
        
        $this->ebManager->expects($this->never())
                ->method("getExamBlockWithSsds");
        
        $this->from(self::FIXTURE_START_URI)
                ->put(route("addExamBlockSsd",[7]),["ssd" => $ssdCode])
                ->assertUnprocessable()
                ->assertViewIs("components.courses.flash-error");
    }
    
    public function test_delete_success(){
        $this->beAdmin();
        
        $this->ebManager->expects($this->once())
                ->method("removeSsd")
                ->with(7,11);
        
        $examBlock = new ExamBlock(["id" => 5]);
        $examBlock->setRelation("ssds",collect([]));
        $this->ebManager->expects($this->once())
            ->method("getExamBlockWithSsds")
            ->with(7)
            ->willReturn($examBlock);
                
        $this->delete(route("delExamBlockSsd",[
            "examBlockId" => 7, "ssdId" => 11]))
                ->assertOk()
                ->assertViewHas("examBlock", $examBlock)
                ->assertViewIs("components.courses.exam-block-ssds");
    }


    private function beAdmin(): User {
        $roleAdmin = Role::create([
                    "name" => Role::ADMIN
        ]);
        $admin = User::factory()->create();
        $admin->roles()->attach($roleAdmin);
        $this->be($admin);
        return $admin;
    }
//
//    private function postValidationTest(string $attrName, $attrValue) {
//        $ebAttributes = [
//            "cfu" => 7,
//            "courseYear" => 3,
//            "maxExams" => 2
//        ];
//        $ebAttributes[$attrName] = $attrValue;
//
//        $response = $this->from(self::FIXTURE_START_URI)
//                ->post(route("examBlockCreate", [$this->course->id]), $ebAttributes);
//
//        $response->assertRedirect(self::FIXTURE_START_URI);
//    }
//    
//    private function putValidationTest(string $attrName, $attrValue, int $blockId) {
//        $ebAttributes = [
//            "cfu" => 7,
//            "courseYear" => 3,
//            "maxExams" => 2
//        ];
//        $ebAttributes[$attrName] = $attrValue;
//
//        $response = $this->from(self::FIXTURE_START_URI)
//                ->put(route("examBlockUpdate", [$blockId]), $ebAttributes);
//
//        $response->assertRedirect(self::FIXTURE_START_URI);
//    }

}
