<?php

namespace Tests\Browser;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;

class LoginPageTest extends DuskTestCase
{
    
    use DatabaseMigrations;
    

    public function test_login_success(){
        $user = User::factory()->create([
           "email"  => 'prova@email.com'
        ]);

        $this->browse(function($browser) use($user){
            $browser->visit("/login")
                    ->type("email",$user->email)
                    ->type("password","password")
                    ->press("#login-button")
                    ->assertPathIs(RouteServiceProvider::HOME)
                    ->logout();
        });
    }
    
    public function test_login_failure(){
        $user = User::make([
            "id" => 2,
           "email"  => 'prova@email.com'
        ]);
        
        $this->browse(function($browser) use($user){
            $browser->visit("/login")
                    ->type("email",$user->email)
                    ->type("password","password")
                    ->press("#login-button")
                    ->assertPathIs("/login");
        });
    }
}
