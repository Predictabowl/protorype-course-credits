<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Support\Seeders\GenerateSSD;
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
        $ssd = "INF/01";
        
        $id = GenerateSSD::getSSDId($ssd);
        
        $this->assertEquals(10, $id);
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
