<?php

namespace Tests\Unit\Services;

use App\Models\Front;
use App\Models\User;
use App\Services\Implementations\UserFrontManagerImpl;
use App\Factories\Interfaces\RepositoriesFactory;
use App\Repositories\Interfaces\TakenExamRepository;
use App\Repositories\Interfaces\ExamBlockRepository;
use App\Repositories\Interfaces\FrontRepository;
use App\Exceptions\Custom\UserNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class UserFrontManagerImplTest extends TestCase
{
    private $factory;
    private $takenRepo;
    private $blockRepo;
    private $frontRepo;
    private $manager;
    

    protected function setUp():void
    {
        $this->factory = $this->createMock(RepositoriesFactory::class);
        $this->takenRepo = $this->createMock(TakenExamRepository::class);
        $this->blockRepo = $this->createMock(ExamBlockRepository::class);
        $this->frontRepo = $this->createMock(FrontRepository::class);
        
        $this->factory->method("getTakenExamRepository")
                ->willReturn($this->takenRepo);
        $this->factory->method("getExamBlockRepository")
                ->willReturn($this->blockRepo);
        $this->factory->method("getFrontRepository")
                ->willReturn($this->frontRepo);

        
        
    }
    /*
    public function test_createFront_when_not_present(){
        $front = new Front();
        $front["id"] = 7;
        $this->frontRepo->expects($this->once())
                ->method("save")
                ->with(3,4)
                ->willReturn($front);
        
        $sut = $this->manager->createFront(3,4);
        $this->assertEquals(1,$sut);
        $this->assertEquals(7,$this->manager->getActiveFrontId());
    }
    
    public function test_createFront_failure(){
        $this->frontRepo->expects($this->once())
                ->method("save")
                ->with(3,4)
                ->willReturn(null);
        
        $sut = $this->manager->createFront(3,4);
        $this->assertEquals(0,$sut);
        $this->assertNull($this->manager->getActiveFrontId());
    }*/
    
    public function test_example() {
        $user = new User();
        User::shoduldReceive("find")
                ->once()
                ->with(1)
                ->andReturn($user);
        
        $got = User::find(1);
        
        $this->assertSame($user, $got);
    }

}