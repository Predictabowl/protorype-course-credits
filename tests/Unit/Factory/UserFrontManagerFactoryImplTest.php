<?php

namespace Tests\Unit\Factory;

use App\Factories\Implementations\UserFrontManagerFactoryImpl;
use App\Factories\Interfaces\StudyPlanBuilderFactory;
use App\Repositories\Interfaces\FrontRepository;
use App\Services\Implementations\UserFrontManagerImpl;
use App\Services\Interfaces\FrontManager;
use Tests\TestCase;

class UserFrontManagerFactoryImplTest extends TestCase
{
    private UserFrontManagerFactoryImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        $frontManager = $this->createMock(FrontManager::class);
        $frontRepo = $this->createMock(FrontRepository::class);
        $spbFactory = $this->createMock(StudyPlanBuilderFactory::class);
        
        $this->sut = new UserFrontManagerFactoryImpl($frontRepo,
                $frontManager, $spbFactory);
    }

    
    public function test_factory_instance(){
        
        $instance = $this->sut->get(3);
        
        $this->assertInstanceOf(UserFrontManagerImpl::class, $instance);
        $this->assertEquals(3, $instance->getUserId());
    }
}