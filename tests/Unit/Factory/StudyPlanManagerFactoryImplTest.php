<?php

namespace Tests\Unit\Factory;

use App\Factories\Implementations\StudyPlanManagerFactoryImpl;
use App\Models\Front;
use App\Services\Interfaces\StudyPlanManager;
use App\Services\Interfaces\UserFrontManager;
use App\Services\Interfaces\YearCalculator;
use PHPUnit\Framework\TestCase;
use function app;

class StudyPlanManagerFactoryImplTest extends TestCase
{
    private StudyPlanManagerFactoryImpl $sut;
    private UserFrontManager $ufManager;
    private YearCalculator $yCalc;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->ufManager = $this->createMock(UserFrontManager::class);
        app()->instance(UserFrontManager::class, $this->ufManager);
        $this->yCalc = $this->createMock(YearCalculator::class);
        app()->instance(YearCalculator::class, $this->yCalc);
        
        $this->sut = new StudyPlanManagerFactoryImpl();
    }

    
    public function test_factory_instance(){
        $front = new Front(["user_id" => 1]);
        
        $instance = $this->sut->get($front);
        
        $this->assertInstanceOf(StudyPlanManager::class, $instance);
    }
}