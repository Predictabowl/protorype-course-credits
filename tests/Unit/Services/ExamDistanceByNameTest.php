<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\Implementations\ExamDistanceByName;
use App\Domain\ExamOptionDTO;
use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;

/**
 * Description of ExamDistanceByNameTest
 *
 * @author piero
 */
class ExamDistanceByNameTest extends TestCase{
    
    private $eDistance;
    private $block;
    
    protected function setUp(): void {
        $this->eDistance = new ExamDistanceByName();
        $this->block = new ExamBlockDTO(1,1,10,null);
    }
    
    public function test_name_distance_insertion() {
        $option = new ExamOptionDTO(1,"nome insegnamento", $this->block, "ssd");
        $taken = new TakenExamDTO(1,"nome dell'insegnamento","ssd",6, 19);
        
        $dist = $this->eDistance->calculateDistance($option, $taken);
        
        $this->assertEquals(5, $dist);
    }
    
    public function test_name_distance_deletion() {
        $option = new ExamOptionDTO(1,"Nome Insegnamento", $this->block, "ssd");
        $taken = new TakenExamDTO(1,"nome insegna","ssd",6, 23);
        
        $dist = $this->eDistance->calculateDistance($option, $taken);
        
        $this->assertEquals(5, $dist);
    }
    
    public function test_name_distance_replacement() {
        $option = new ExamOptionDTO(1,"Nome insegnamento", $this->block, "ssd");
        $taken = new TakenExamDTO(1,"nome insegnamenti","ssd",6, 26);
        
        $dist = $this->eDistance->calculateDistance($option, $taken);
        
        $this->assertEquals(1, $dist);
    }
}
