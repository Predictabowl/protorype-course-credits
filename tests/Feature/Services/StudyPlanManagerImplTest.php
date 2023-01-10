<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Feature\Services;

use App\Domain\StudyPlan;
use App\Factories\Interfaces\UserFrontManagerFactory;
use App\Models\Course;
use App\Models\Front;
use App\Models\User;
use App\Services\Implementations\StudyPlanManagerImpl;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Services\Interfaces\UserFrontManager;
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
    
    private Front $front;
    private UserFrontManager $ufManager;
    private UserFrontManagerFactory $ufManagerFactory;
    private YearCalculator $yCalc;
    private StudyPlanManagerImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        
        $user = User::factory()->create();
        $this->front = Front::create(["user_id" => $user->id]);
        $this->ufManagerFactory = $this->createMock(UserFrontManagerFactory::class);
        $this->ufManager = $this->createMock(UserFrontManager::class);
        $this->yCalc = $this->createMock(YearCalculator::class);

        
        $this->sut = new StudyPlanManagerImpl($this->front, $this->ufManagerFactory,
                $this->yCalc);
    }
    
    public function test_getStudyPlan_with_course_not_set(){
        $this->ufManagerFactory->expects($this->once())
                ->method("get")
                ->with($this->front->user_id)
                ->willReturn($this->ufManager);
        
        $this->ufManager->expects($this->once())
                ->method("getStudyPlanBuilder")
                ->willReturn(null);
        
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
        $this->ufManagerFactory->expects($this->once())
                ->method("get")
                ->with($this->front->user_id)
                ->willReturn($this->ufManager);
        
        $this->ufManager->expects($this->once())
                ->method("getStudyPlanBuilder")
                ->willReturn(null);        

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
        $course = Course::factory()->create();
        $this->front->course()->associate($course);
        $this->front->save();
        
        $this->ufManagerFactory->expects($this->once())
                ->method("get")
                ->with($this->front->user_id)
                ->willReturn($this->ufManager);
        
        $builder = $this->createMock(StudyPlanBuilder::class);
        
        $this->ufManager->expects($this->once())
                ->method("getStudyPlanBuilder")
                ->willReturn($builder);
        
        $builder->expects($this->once())
                ->method("getStudyPlan")
                ->willReturn($plan);
        
        return $this->front;
    }
    
}
