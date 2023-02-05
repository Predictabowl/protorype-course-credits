<?php

namespace Tests\Unit\Services;

use App\Factories\Interfaces\FrontManagerFactory;
use App\Factories\Interfaces\StudyPlanBuilderFactory;
use App\Models\Front;
use App\Repositories\Interfaces\FrontRepository;
use App\Services\Implementations\UserFrontManagerImpl;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\StudyPlanBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFrontManagerImplTest extends TestCase
{
    use RefreshDatabase;
    
    private const FIXTURE_USER_ID = 2;
    private const FIXTURE_FRONT_ID = 27;
    
    private FrontRepository $frontRepo;
    private UserFrontManagerImpl $sut;
    private FrontManager $frontManager;
    private StudyPlanBuilderFactory $spbFactory;
    

    protected function setUp():void
    {
        parent::setUp();
        
        $this->frontRepo = $this->frontRepo = $this->createMock(FrontRepository::class);
        $this->frontManager = $this->createMock(FrontManager::class);
        $this->spbFactory = $this->createMock(StudyPlanBuilderFactory::class);
        
        $this->sut = new UserFrontManagerImpl($this->frontRepo,
                $this->frontManager, $this->spbFactory, self::FIXTURE_USER_ID);
    }

    public function test_getOrCreateFront_when_Front_not_present(){
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
                
        $result = $this->sut->getOrCreateFront(3);
        
        $this->assertSame($saved,$result);
    }

    
//    public function test_getOrCreateFront_when_Front_exists_but_course_not_found(){
//        $saved = new Front([
//            "user_id" => self::FIXTURE_USER_ID,
//        ]);
//        $saved->id = 5;
//        $this->frontRepo->expects($this->once())
//                ->method("getFromUser")
//                ->with(self::FIXTURE_USER_ID)
//                ->willReturn($saved);
//        $this->frontRepo->expects($this->once())
//                ->method("updateCourse")
//                ->with(5,3)
//                ->willReturn(null);
//        $this->frontRepo->expects($this->never())
//                ->method("save");
//        
//        $result = $this->sut->getOrCreateFront(3);
//        
//        $this->assertNull($result);
//    }
//    
//       public function test_getOrCreateFront_when_Front_not_exists_and_course_not_found(){
//        $toSave = new Front([
//            "user_id" => self::FIXTURE_USER_ID,
//            "course_id" => 3
//        ]);
//        $this->frontRepo->expects($this->once())
//                ->method("getFromUser")
//                ->with(self::FIXTURE_USER_ID)
//                ->willReturn(null);
//        $this->frontRepo->expects($this->never())
//                ->method("updateCourse");
//        $this->frontRepo->expects($this->once())
//                ->method("save")
//                ->with($toSave)
//                ->willReturn(null);
//        
//        $result = $this->sut->getOrCreateFront(3);
//        
//        $this->assertNull($result);
//    }

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
        $this->frontRepo->expects($this->once())
                ->method("updateCourse")
                ->with(5,7)
                ->willReturn($saved);
        $this->frontRepo->expects($this->never())
                ->method("save");
        
        $result = $this->sut->getOrCreateFront(7);
        
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
        
        $result = $this->sut->getOrCreateFront();
        
        $this->assertSame($found, $result);
    }
    
    public function test_getOrCreateFront_when_Front_exists_and_course_is_the_same(){
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
        
        $result = $this->sut->getOrCreateFront(3);
        
        $this->assertSame($found, $result);
    }

    public function test_getFront_success(){
        $front = new Front();
        $front->id = 13;
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn($front);
        
        $result = $this->sut->getFront();
        
        $this->assertSame($front, $result);
    }
    
    public function test_getFront_when_front_not_present(){
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn(null);
        
        $result = $this->sut->getFront();
        
        $this->assertNull($result);
    }
    
    public function test_getFrontManager_success(){
        $front = new Front();
        $front->id = self::FIXTURE_FRONT_ID;
        
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn($front);
        
        $result = $this->sut->getFrontManager();
        
        $this->assertSame($this->frontManager, $result);
    }
    
//    public function test_getFrontManager_when_cannot_find_Front(){
//        $this->frontRepo->expects($this->once())
//                ->method("getFromUser")
//                ->willReturn(null);
//        $this->frontRepo->expects($this->once())
//                ->method("save")
//                ->willReturn(null);
//        
//        $result = $this->sut->getFrontManager();
//        
//        $this->assertNull($result);
//    }
    
    public function test_getStudyPlanBuilder_success(){
        $studyPlanBuilder = $this->createMock(StudyPlanBuilder::class);
        $front = new Front(["course_id" => 5]);
        $front->id = self::FIXTURE_FRONT_ID;
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn($front);
        $this->spbFactory->expects($this->once())
                ->method("getStudyPlanBuilder")
                ->with(self::FIXTURE_FRONT_ID,5)
                ->willReturn($studyPlanBuilder);
        
        $result = $this->sut->getStudyPlanBuilder();
        
        $this->assertSame($result,$studyPlanBuilder);
    }
    
    public function test_getStudyPlanBuilder_when_course_not_set(){
        $front = new Front();
        $front->id = self::FIXTURE_FRONT_ID;
        
        $this->spbFactory->expects($this->never())
                ->method("getStudyPlanBuilder");
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn($front);
        
        $result = $this->sut->getStudyPlanBuilder();
        
        $this->assertNull($result);
    }
    
//    public function test_getStudyPlanBuilder_when_front_cannot_be_created(){
//        $front = new Front([
//            "user_id" => self::FIXTURE_USER_ID,
//            "course_id" => null
//            ]);
//        
//        
//        $this->spbFactory->expects($this->never())
//                ->method("getStudyPlanBuilder");
//        $this->frontRepo->expects($this->once())
//                ->method("getFromUser")
//                ->with(self::FIXTURE_USER_ID)
//                ->willReturn(null);
//        $this->frontRepo->expects($this->once())
//                ->method("save")
//                ->with($front)
//                ->willReturn(null);
//        
//        $result = $this->sut->getStudyPlanBuilder();
//        
//        $this->assertNull($result);
//    }
    
    public function test_getUserId(){
        $result = $this->sut->getUserId();
        
        $this->assertEquals($result, self::FIXTURE_USER_ID);
    }

}