<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\ExamBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LearningQueryTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        //$response = $this->get('/');

        //$response->assertStatus(200);
        $this->seed();
        $blocks = ExamBlock::first()->examBlockOptions()->first()->examApproved;


        dd($blocks);
    }
}
