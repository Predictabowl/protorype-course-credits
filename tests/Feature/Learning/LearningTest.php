<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Seeders\GenerateSSD;
use App\Models\User;
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
//        $authUser = new User();
//        $authUser->id = 1;
//        $this->actingAs($authUser);
        $obj = new TakenExamDTO(1, "test", "SSD1", 8);
        $newObj = $obj->split(4);
        
        $this->assertNotSame($obj, $newObj);
        $this->assertEquals($obj,$newObj);
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
