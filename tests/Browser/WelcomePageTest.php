<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Providers\RouteServiceProvider;

class WelcomePageTest extends DuskTestCase
{
    
    use DatabaseMigrations;
    
    
    public function test_welcome_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Valutazione Carriera')
                    ->assertSee("Giurisprudenza")
                    ->assertSee("ACCEDI")
                    ->press("#login-link")
                    ->assertPathIs("/login");
        });
    }
    
    public function test_welcome_page_link_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee("REGISTRATI")
                    ->press("#register-link")
                    ->assertPathIs("/register");
        });
    }
}
