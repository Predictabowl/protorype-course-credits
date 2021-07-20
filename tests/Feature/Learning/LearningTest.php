<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Seeders\GenerateSSD;
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
        dd(route("frontIndex"));
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
