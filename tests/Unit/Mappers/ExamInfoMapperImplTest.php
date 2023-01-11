<?php

use App\Domain\NewExamInfo;
use App\Mappers\Implementations\ExamInfoMapperImpl;
use App\Models\Exam;
use PHPUnit\Framework\TestCase;

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of ExamInfoMapperImplTest
 *
 * @author piero
 */
class ExamInfoMapperImplTest extends TestCase{

    private ExamInfoMapperImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->sut = new ExamInfoMapperImpl();
    }

    public function test_mapper(){
        $examInfo = new NewExamInfo("test name", "IUS/07", true);
        
        $exam = $this->sut->map($examInfo, 3, 5);
        
        $this->assertEquals(new Exam([
            "name" => "test name",
            "ssd_id" => 5,
            "free_choice" => true,
            "exam_block_id" => 3
            ]),
            $exam);
    }
}
