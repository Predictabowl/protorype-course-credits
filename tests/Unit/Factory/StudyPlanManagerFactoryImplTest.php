<?php

namespace Tests\Unit\Factory;

use App\Factories\Implementations\StudyPlanManagerFactoryImpl;
use App\Factories\Interfaces\StudyPlanBuilderFactory;
use App\Models\Front;
use App\Services\Interfaces\StudyPlanManager;
use App\Services\Interfaces\YearCalculator;
use PHPUnit\Framework\TestCase;

class StudyPlanManagerFactoryImplTest extends TestCase
{
    private StudyPlanManagerFactoryImpl $sut;
    private StudyPlanBuilderFactory $studyPlanBuilderFactory;
    private YearCalculator $yCalc;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->studyPlanBuilderFactory = $this->createMock(StudyPlanBuilderFactory::class);
        $this->yCalc = $this->createMock(YearCalculator::class);
        
        $this->sut = new StudyPlanManagerFactoryImpl(
                $this->studyPlanBuilderFactory,
                $this->yCalc);
    }

    
    public function test_factory_instance(){
        $front = new Front(["user_id" => 1]);
        
        $instance = $this->sut->get($front);
        
        $this->assertInstanceOf(StudyPlanManager::class, $instance);
    }
}