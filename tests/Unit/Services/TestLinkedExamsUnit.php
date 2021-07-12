<?php

namespace App\Services\Implementations;

use App\Domain\ExamAssignedValue;
use App\Models\TakenExam;
use App\Models\Exam;
use PHPUnit\Framework\TestCase;

class TestLinkedExamsUnit  extends TestCase
{

    const FIXTURE_MAX_CFU = 12;

    private $examLink;
    private $exam;

    /**
     * Class Constructor
     */
    /*public function __construct()
    {    
        parent::__construct();
        $this->setUpClass();
    }

    private function setUpClass()
    {      
    }*/

    protected function setUp(): void 
    {

        $this->exam = new Exam();
        $this->exam->setAttribute("name","Diritto Privato");
        $this->exam->setAttribute("ssd_id","IUS/01");
        $this->exam->setAttribute("cfu",self::FIXTURE_MAX_CFU);  

        $this->examLink = new LinkedExamsUnit($this->exam);
    }

    public function test_integration_value_within_range()
    {
        $this->examLink->addTakenExam($this->stubTakenExam(1,5));

        $this->assertEquals($this->examLink->addTakenExam($this->stubTakenExam(3,3)),3);
        $this->assertEquals(
            $this->examLink->getIntegrationValue(),
            self::FIXTURE_MAX_CFU-5-3);
    }

    public function test_exam_is_not_added_when_cfu_contribution_is_zero()
    {
        $stub = $this->stubTakenExam(1,self::FIXTURE_MAX_CFU);
        $this->examLink->addTakenExam($stub);
        
        $this->assertEquals($this->examLink->addTakenExam($this->stubTakenExam(2,1)),0);
        $this->assertEquals($this->examLink->getTakenExams()->count(),1);
        $this->assertEquals($this->examLink->getIntegrationValue(),0);
    }


    private function stubTakenExam(int $id, int $cfu): TakenExam
    {
        $takenExam = new TakenExam();
        $takenExam->setAttribute("id",$id);
        $takenExam->setAttribute("cfu",$cfu);
        return $takenExam;
    }


}