<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Laravel\Dusk\Chrome;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Providers\RouteServiceProvider;

class ExampleTest extends DuskTestCase
{
    
    use DatabaseMigrations;
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });
    }
    
    public function test_login_example(){
        $user = User::factory()->create([
           "email"  => 'prova@email.com'
        ]);
        
        $this->browse(function($browser) use($user){
            $browser->visit("/login")
                    ->type("email",$user->email)
                    ->type("password","password")
                    ->press("LOG IN")
                    ->assertPathIs(RouteServiceProvider::HOME);
        });
        
//        $this->browse(function(Browser $browser) use($user){
//            $browser->visit("/login")
//                    ->assertSee("LOG IN");
//        });
    }
}
