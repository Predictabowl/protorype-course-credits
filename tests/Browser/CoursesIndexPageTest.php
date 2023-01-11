<?php

namespace Tests\Browser;

use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;

class CoursesIndexPageTest extends DuskTestCase
{
    
    use DatabaseMigrations;
    

    public function test_coursesLink_hide_forNormalUsers(){
        $user = User::factory()->create();

        $this->browse(function($browser) use($user){
            $browser->loginAs($user)
                    ->visit("/dashboard")
                    ->assertNotPresent("#courses-management-link")
                    ->logout();
        });
    }
    
    public function test_coursesLink_presentForAdmin(){

        $this->browse(function($browser){
            $linkId = "#courses-management-link";
            $browser->loginAs($this->getAdmin())
                    ->visit("/dashboard")
                    ->assertPresent($linkId)
                    ->click($linkId)
                    ->assertRouteIs("courseIndex")
                    ->logout();
        });
    }
    
    public function test_coursesList (){
        Course::factory(2)->create();
        $this->browse(function($browser){
            $courses = Course::all();
            $linkId = "#courses-management-link";
            $browser->loginAs($this->getAdmin())
                    ->visit(route("courseIndex"))
                    ->assertSee($courses->get(0)->name)
                    ->assertSee($courses->get(1)->name)
                    ->logout();
        });
    }
    
    private function getAdmin(): User{
        $roleAdmin = Role::create([
            "name" => Role::ADMIN
        ]);
        $admin = User::factory()->create();
        $admin->roles()->attach($roleAdmin);
        return $admin;
    }
}
