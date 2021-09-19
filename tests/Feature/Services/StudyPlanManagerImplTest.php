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
        $plan = new StudyPlan(collect([]));
        
        $userFrontManager->expects($this->once())
                ->method("getStudyPlanBuilder")
                ->willReturn($builder);
        
        $builder->expects($this->once())
                ->method("getStudyPlan")
                ->willReturn($plan);
        
        $manager = new StudyPlanManagerImpl($front);
        
        $result = $manager->getStudyPlan();
        
        $this->assertSame($plan, $result);
        
    }
    
}
