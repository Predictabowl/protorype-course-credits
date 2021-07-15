<?php

namespace App\Services\Implementations;

use App\Domain\ExamBlockDTO;
use App\Domain\TakenExamDTO;
use App\Models\Front;
use App\Services\Interfaces\FrontManager;
use Illuminate\Support\Collection;

/**
 * No Implemented Yet
 */
class FrontManagerImpl implements FrontManager {

    private $front;
    private $blocks;
    private $takenExams;

    function __construct(Front $front = null) {
        $this->front = $front;
        if (isset($front)) {
            $this->setUp();
        }
    }

    public function setFront(Front $front) {
        $this->front = $front;
        $this->setUp();
    }

    public function getExamBlocks(): Collection {
        return $this->blocks;
    }

    public function getTakenExams(): Collection {
        return $this->takenExams;
    }


    public function getExamOptions(): Collection {
        
    }

    
    private function setUp() {
        $course = $this->front->course->first()->with("examBlocks.examBlockOptions.examApproved")->first();
        $this->blocks = $course->examBlocks->map(function ($block) {
            return new ExamBlockDTO($block->id, $block->max_exams);
        });
        
        $this->takenExams = $this->front->takenExams->map(function ($taken) {
            return new TakenExamDTO(
                    $taken->id,
                    $taken->name,
                    $taken->ssd->code,
                    $taken->cfu);
        });
    }
}
