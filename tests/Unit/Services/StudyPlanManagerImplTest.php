<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Services;

use App\Domain\StudyPlan;
use App\Factories\Interfaces\StudyPlanBuilderFactory;
use App\Models\Course;
use App\Models\Front;
use App\Services\Implementations\StudyPlanManagerImpl;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Services\Interfaces\YearCalculator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function collect;

/**
 * Description of StudyPlanManagerImplTest
 *
 * @author piero
 */
class StudyPlanManagerImplTest extends TestCase{
    
    use RefreshDatabase;
    
    private const FIXTURE_COURSE_ID = 7;
    private const FIXTURE_USER_ID = 3;
    private const FIXTURE_FRONT_ID = 5;
    
    private Front $front;
    private StudyPlanBuilderFactory $spbFactory;
    private StudyPlanBuilder $studyPlanBuilder;
    private YearCalculator $yCalc;
    private StudyPlanManagerImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->front = new Front([
            "id" => self::FIXTURE_FRONT_ID,
            "user_id" => self::FIXTURE_USER_ID]);
        $this->spbFactory = $this->createMock(StudyPlanBuilderFactory::class);
        $this->yCalc = $this->createMock(YearCalculator::class);
        $this->studyPlanBuilder = $this->createMock(StudyPlanBuilder::class);
        
        $this->sut = new StudyPlanManagerImpl($this->front, $this->spbFactory,
                $this->yCalc);
    }
    
    public function test_getStudyPlan_with_course_not_set(){
        $this->spbFactory->expects($this->never())
                ->method("get");
        
        $this->studyPlanBuilder->expects($this->never())
                ->method("getStudyPlan");
        
        $plan = $this->sut->getStudyPlan();
        
        $this->assertNull($plan);
    }
    
    public function test_getStudyPlan_success(){
        $plan = new StudyPlan(collect([]));
        $this->setupStudyPlan($plan);
        
        $result = $this->sut->getStudyPlan();
        
        $this->assertSame($plan, $result);
    }
    
    public function test_getAcademicYear(){
        $date = Carbon::now();
        $year = $date->format("Y");
        $month = $date->format("m");
        $day = $date->format("d");
        $this->yCalc->expects($this->once())
                ->method("getAcademicYear")
                ->with($day,$month,$year)
                ->willreturn(1498);

        $result = $this->sut->getAcademicYear();
        
        $this->assertEquals(1498, $result);
    }
    
    public function test_getCourseYear_failure(){
        $this->yCalc->expects($this->never())
                ->method("getCourseYear");
        
        $this->assertNull($this->sut->getCourseYear());
        
    }
    
    public function test_getCourseYear_success(){
        $plan = $this->createMock(StudyPlan::class);
        $this->front = $this->setupStudyPlan($plan);
        $this->yCalc->expects($this->once())
                ->method("getCourseYear")
                ->with($this->front->course, $plan)
                ->willReturn(2);
        
        $result = $this->sut->getCourseYear();
        
        $this->assertEquals(2, $result);
    }
    
    private function setupStudyPlan(?StudyPlan $plan){
        $course = new Course(["id" => self::FIXTURE_COURSE_ID]);
        $this->front->setRelation("course",$course);
        
        $this->spbFactory->expects($this->once())
                ->method("get")
                ->with($this->front->id, $course)
                ->willReturn($this->studyPlanBuilder);
        
        $this->studyPlanBuilder->expects($this->once())
                ->method("getStudyPlan")
                ->willReturn($plan);
        
        return $this->front;
    }
    
}
