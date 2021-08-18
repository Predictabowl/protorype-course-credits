<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Support\Seeders\GenerateSSD;
use App\Models\User;
use App\Models\Exam;
use App\Models\Front;
use App\Domain\TakenExamDTO;
use App\Models\Ssd;
use Tests\TestCase;

/**
 * Description of LearningTest
 *
 * @author piero
 */
class LearningTest extends TestCase{
    
    use RefreshDatabase;
    
    public function test_example() {
        User::factory()->create([
            "name" => "mario"
        ]);
        User::factory()->create([
            "name" => "luigi"
        ]);
        User::factory()->create([
            "name" => "carlo"
        ]);
        Front::create(["user_id" => 1]);
        Front::create(["user_id" => 2]);
        Front::create(["user_id" => 3]);
        
        $query = Front::with("user","course");//->join("users","fronts.user_id","=","users.id");
//        $result = Front::with("user","course")->join("users","fronts.user_id","=","users.id")
//                ->where("users.name","like","%ca%")->get();
        //$query = $query->where("users.name","like","%ca%");
        dd($query->get()[1]->attributesToArray());
    }
   
    
    /*
    public function test_generateSSD() {
//        GenerateSSD::arrayCreate([
//            "ius" => 10,
//            "M-psi" => 8
//        ]);
        
        GenerateSSD::createAll();
        
        Ssd::all()->each(fn($ssd) => var_dump($ssd->code));
        
    }*/
}
