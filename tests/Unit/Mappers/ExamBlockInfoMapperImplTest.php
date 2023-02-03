<?php

use App\Domain\NewExamBlockInfo;
use App\Mappers\Implementations\ExamBlockInfoMapperImpl;
use App\Models\ExamBlock;
use PHPUnit\Framework\TestCase;

/**
 * Description of ExamInfoMapperImplTest
 *
 * @author piero
 */
class ExamBlockInfoMapperImplTest extends TestCase{

    private ExamBlockInfoMapperImpl $sut;
    
    protected function setUp(): void {
        parent::setUp();
        
        $this->sut = new ExamBlockInfoMapperImpl();
    }

    public function test_mapper(){
        $ebInfo = new NewExamBlockInfo(7, 11, 1);
        
        $examBlock = $this->sut->map($ebInfo, 5);
        
        $this->assertEquals(new ExamBlock([
            "max_exams" => 7,
            "course_id" => 5,
            "cfu" => 11,
            "courseYear" => 1
            ]),
            $examBlock);
    }
}
