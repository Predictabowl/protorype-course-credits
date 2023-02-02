<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of EditCoursePageTest
 *
 * @author piero
 */
class EditCoursePageTest extends DuskTestCase{
    
    use DatabaseMigrations;
    
    //Incomplete test
    public function test_components(){
        
        $this->browse(function(Browser $browser){
            $browser->loginAs($this->getAdminId())
                    ->visit(route("courseNew"))
                    ->assertSeeLink(__("Cancel"))
                    ->clickLink(__("Cancel"))
                    ->assertRouteIs("courseIndex")
                    ->logout();
        });
    }
    
        private function getAdminId(): int{
        $roleAdmin = Role::create([
            "name" => Role::ADMIN
        ]);
        $admin = User::factory()->create();
        $admin->roles()->attach($roleAdmin);
        return $admin->id;
    }
}
