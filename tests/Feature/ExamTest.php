<?php

namespace Tests\Feature;

use App\Models\Exam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     *
     * @test
     */
    public function test_get_exam_list()
    {
        $this->withoutExceptionHandling();
        $exams = Exam::factory()->create()->attributesToArray();
        //var_dump($exams->attributesToArray());

        $response = $this->get('/exams');
        $response->assertStatus(200);

        //$this->assertDatabaseHas("exams",$exams);
        $response->assertSee($exams["ssd"]);

    }
}
