<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Feature\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Front;
use App\Models\Course;
use App\Models\User;
use App\Services\Interfaces\UserFrontManager;
use App\Services\Implementations\StudyPlanManagerImpl;
use App\Services\Interfaces\StudyPlanBuilder;
use App\Domain\StudyPlan;
use App\Services\Interfaces\YearCalculator;
use Carbon\Carbon;

/**
 * Description of StudyPlanManagerImplTest
 *
 * @author piero
 */
class StudyPlanManagerImplTest extends TestCase{
    
    use RefreshDatabase;
    
    public function test_getStudyPlan_with_course_not_set(){
        $front = new Front(["user_id" => 3]);
        $userFrontManager = $this->createMock(UserFrontManager::class);
        app()->instance(UserFrontManager::class, $userFrontManager);
        
        $userFrontManager->expects($this->once())
                ->method("setUserId")
                ->with(3)
                ->willReturn($userFrontManager);
        
        $userFrontManager->expects($this->once())
                ->method("getStudyPlanBuilder")
                ->willReturn(null);
        
        $manager = new StudyPlanManagerImpl($front);
        
        $plan = $manager->getStudyPlan();
        
        $this->assertNull($plan);
    }
    
    public function test_getStudyPlan_success(){
        $plan = new StudyPlan(collect([]));
        $front = $this->setupStudyPlan($plan);
        
        $manager = new StudyPlanManagerImpl($front);
        
        $result = $manager->getStudyPlan();
        
        $this->assertSame($plan, $result);
    }
    
    public function test_getAcademicYear(){
        $front = new Front();
        $calculator = $this->createMock(YearCalculator::class);
        app()->instance(YearCalculator::class, $calculator);
        $date = Carbon::now();
        $year = $date->format("Y");
        $month = $date->format("m");
        $calculator->expects($this->once())
                ->method("getAcademicYear")
                ->with($month,$year)
                ->willreturn(1498);

        $manager = new StudyPlanManagerImpl($front);
        
        $result = $manager->getAcademicYear();
        
        $this->assertEquals(1498, $result);
    }
    
    public function test_getCourseYear_failure(){
        $user = User::factory()->create();
        $front = Front::create(["user_id" => $user->id]);
        $userFrontManager = $this->createMock(UserFrontManager::class);
        app()->instance(UserFrontManager::class, $userFrontManager);
        
        $userFrontManager->expects($this->once())
                ->method("setUserId")
                ->with($user->id)
                ->willReturn($userFrontManager);
        
        $userFrontManager->expects($this->once())
                ->method("getStudyPlanBuilder")
                ->willReturn(null);        

        $yearCalculator = $this->createMock(YearCalculator::class);
        app()->instance(YearCalculator::class, $yearCalculator);
        $yearCalculator->expects($this->never())
                ->method("getCourseYear");
        
        $manager = new StudyPlanManagerImpl($front);
        
        $this->assertNull($manager->getCourseYear());
        
    }
    
    public function test_getCourseYear_success(){
        $plan = $this->createMock(StudyPlan::class);
        $front = $this->setupStudyPlan($plan);
        $yearCalculator = $this->createMock(YearCalculator::class);
        app()->instance(YearCalculator::class, $yearCalculator);
        $yearCalculator->expects($this->once())
                ->method("getCourseYear")
                ->with($front->course, $plan)
                ->willReturn(2);
        
        $manager = new StudyPlanManagerImpl($front);
        
        $result = $manager->getCourseYear();
        
        $this->assertEquals(2, $result);
    }
    
    private function setupStudyPlan(?StudyPlan $plan){
        $course = Course::factory()->create();
        $user = User::factory()->create();
        $front = Front::create(["user_id" => $user->id]);
        $front->course()->associate($course);
        $front->save();
        $userFrontManager = $this->createMock(UserFrontManager::class);
        app()->instance(UserFrontManager::class, $userFrontManager);
        
        $userFrontManager->expects($this->once())
                ->method("setUserId")
                ->with($user->id)
                ->willReturn($userFrontManager);
        
        $builder = $this->createMock(StudyPlanBuilder::class);
        
        $userFrontManager->expects($this->once())
                ->method("getStudyPlanBuilder")
                ->willReturn($builder);
        
        $builder->expects($this->once())
                ->method("getStudyPlan")
                ->willReturn($plan);
        
        return $front;
    }
    
}
