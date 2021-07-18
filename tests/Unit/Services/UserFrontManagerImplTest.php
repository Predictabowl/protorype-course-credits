<?php

namespace Tests\Unit\Services;

use App\Models\Front;
use App\Models\User;
use App\Models\Course;
use App\Services\Implementations\UserFrontManagerImpl;
use App\Repositories\Interfaces\UserRepository;
use App\Repositories\Interfaces\CourseRepository;
use Mockery\MockInterface;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\FrontRepository;
use App\Exceptions\Custom\UserNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\TestCase;
//use Tests\TestCase;

class UserFrontManagerImplTest extends TestCase
{
    private const FIXTURE_USER_ID = 2;
    
    private $frontRepo;
    private $userRepo;
    private $courseRepo;
    private $manager;
    

    protected function setUp():void
    {
        parent::setUp();
        
        $factory = $this->createMock(RepositoriesFactory::class);
        $this->frontRepo = $this->createMock(FrontRepository::class);
        $this->userRepo = $this->createMock(UserRepository::class);
        $this->courseRepo = $this->createMock(CourseRepository::class);
        
        $factory->method("getUserRepository")
                ->willReturn($this->userRepo);
        $factory->method("getFrontRepository")
                ->willReturn($this->frontRepo);
        $factory->method("getCourseRepository")
                ->willReturn($this->courseRepo);
        
        $user = new User();
        $this->userRepo->method("get")->willReturn($user);
        $this->manager = new UserFrontManagerImpl(self::FIXTURE_USER_ID,$factory);
        
    }

    public function test_createFront_when_Front_not_present(){
        $toSave = new Front([
            "user_id" => 2,
            "course_id" => 3
        ]);
        $saved = new Front();
        $saved->id = 7;
        $this->frontRepo->expects($this->once())
                ->method("save")
                ->with($toSave)
                ->willReturn($saved);
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
                ->willReturn(null);
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
    
    
    // Mocking example with binding. 
    // Work but not very practical with complex argument data
    // needs Tests\TestCase
/*    
    public function test_createFront_failure(){
        $this->frontRepo->expects($this->once())
                ->method("save")
                ->with(3,4)
                ->willReturn(null);
        
        $sut = $this->manager->createFront(3,4);
        $this->assertEquals(0,$sut);
        $this->assertNull($this->manager->getActiveFrontId());
    }*/
   

}