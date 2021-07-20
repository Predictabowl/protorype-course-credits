<?php

namespace Tests\Unit\Services;

use App\Models\Front;
use App\Models\User;
use App\Models\Course;
use App\Services\Implementations\UserFrontManagerImpl;
use App\Services\Interfaces\FrontInfoManager;
use App\Factories\Interfaces\FrontInfoManagerFactory;
use App\Repositories\Interfaces\UserRepository;
use App\Repositories\Interfaces\CourseRepository;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Repositories\Interfaces\FrontRepository;
use Tests\TestCase;

class UserFrontManagerImplTest extends TestCase
{
    private const FIXTURE_USER_ID = 2;
    private const FIXTURE_FRONT_ID = 27;
    
    private $frontRepo;
    private $userRepo;
    private $courseRepo;
    private $manager;
    private $repoFactory;
    private $authUser;
    

    protected function setUp():void
    {
        parent::setUp();
        
        $this->repoFactory = $this->createMock(RepositoriesFactory::class);
        $this->frontRepo = $this->createMock(FrontRepository::class);
        $this->userRepo = $this->createMock(UserRepository::class);
        $this->courseRepo = $this->createMock(CourseRepository::class);
        
        $this->repoFactory->method("getUserRepository")
                ->willReturn($this->userRepo);
        $this->repoFactory->method("getFrontRepository")
                ->willReturn($this->frontRepo);
        $this->repoFactory->method("getCourseRepository")
                ->willReturn($this->courseRepo);
        
        $infoFactory = $this->createMock(FrontInfoManagerFactory::class);
        
        $this->authUser = new User();
        $this->authUser->id = self::FIXTURE_USER_ID;
        $this->userRepo->method("get")->willReturn($this->authUser);
        $this->actingAs($this->authUser);
        $this->manager = new UserFrontManagerImpl($this->repoFactory, $infoFactory);
    }

    public function test_createFront_when_Front_not_present(){
        $toSave = new Front([
            "user_id" => 2,
            "course_id" => 3
        ]);
        $this->frontRepo->expects($this->once())
                ->method("save")
                ->with($toSave)
                ->willReturn(true);
        $course = new Course();
        $course->id = 3;
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->with(3)
                ->willReturn($course);
                
        $result = $this->manager->createFront(3);
        
        $this->assertTrue($result);
    }
    
    public function test_createFront_when_course_not_found(){
        $this->frontRepo->expects($this->never())
                ->method("save");
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->with(3)
                ->willReturn(null);
                
        $result = $this->manager->createFront(3);
        
        $this->assertFalse($result);
    }
    
     public function test_createFront_when_Front_already_present(){
        $toSave = new Front([
            "user_id" => 2,
            "course_id" => 3
        ]);
        $this->frontRepo->expects($this->once())
                ->method("save")
                ->with($toSave)
                ->willReturn(false);
        $course = new Course();
        $course->id = 3;
        $this->courseRepo->expects($this->once())
                ->method("get")
                ->with(3)
                ->willReturn($course);
                
        $result = $this->manager->createFront(3);
        
        $this->assertFalse($result);
    }
    
    public function test_getFrontId_success(){
        $front = new Front();
        $front->id = 13;
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn($front);
        
        $result = $this->manager->getFrontId();
        
        $this->assertEquals(13, $result);
    }
    
    public function test_getFrontId_when_front_not_present(){
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn(null);
        
        $result = $this->manager->getFrontId();
        
        $this->assertNull($result);
    }
    
    public function test_getFrontInfoManager_success(){
        $front = new Front();
        $front->id = self::FIXTURE_FRONT_ID;
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn($front);
        $infoManager = $this->createMock(FrontInfoManager::class);
        $infoFactory = $this->createMock(FrontInfoManagerFactory::class);
        $infoFactory->expects($this->once())
                ->method("getInstance")
                ->with(self::FIXTURE_FRONT_ID)
                ->willReturn($infoManager);
        $localManager = new UserFrontManagerImpl($this->repoFactory, $infoFactory);
        
        $result = $localManager->getFrontInfoManager();
        
        $this->assertSame($infoManager, $result);
    }
    
    public function test_getFrontInfoManager_failure(){
        $front = new Front();
        $front->id = self::FIXTURE_FRONT_ID;
        $this->frontRepo->expects($this->once())
                ->method("getFromUser")
                ->with(self::FIXTURE_USER_ID)
                ->willReturn(null);
        $infoFactory = $this->createMock(FrontInfoManagerFactory::class);
        $infoFactory->expects($this->never())
                ->method("getInstance");
        $localManager = new UserFrontManagerImpl($this->repoFactory, $infoFactory);

        $result = $localManager->getFrontInfoManager();
        
        $this->assertNull($result);
    }
    
    public function test_deleteFront_when_not_present() {
        $this->frontRepo->expects($this->once())
            ->method("getFromUser")
            ->with(self::FIXTURE_USER_ID)
            ->willReturn(null);
        
        $result = $this->manager->deleteFront();
        
        $this->assertFalse($result);
    }
    
    public function test_deleteFront_success() {
        $front = new Front();
        $front->id = self::FIXTURE_FRONT_ID;
        $this->frontRepo->expects($this->once())
            ->method("getFromUser")
            ->with(self::FIXTURE_USER_ID)
            ->willReturn($front);
        $this->frontRepo->expects($this->once())
            ->method("delete")
            ->with(self::FIXTURE_FRONT_ID)
            ->willReturn(1);
        
        $result = $this->manager->deleteFront();
        
        $this->assertTrue($result);
        
    }
    
    public function test_deleteFront_failure() {
        $front = new Front();
        $front->id = self::FIXTURE_FRONT_ID;
        $this->frontRepo->expects($this->once())
            ->method("getFromUser")
            ->with(self::FIXTURE_USER_ID)
            ->willReturn($front);
        $this->frontRepo->expects($this->once())
            ->method("delete")
            ->with(self::FIXTURE_FRONT_ID)
            ->willReturn(0);
        
        $result = $this->manager->deleteFront();
        
        $this->assertFalse($result);
        
    }


}