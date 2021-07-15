<?php

namespace Tests\Feature;

use App\Services\Interfaces\ExamDistance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        
    }
    
    public function test_dependency_injection(){
        // How to get instance from laravel container
        $examDistance = app()->make(ExamDistance::class);
        
        $this->assertInstanceOf("App\Services\Implementations\ExamDistanceByName", $examDistance);
    }
}
