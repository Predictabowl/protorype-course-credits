<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Domain;

/**
 * Description of CourseExamBlockDTO
 *
 * @author piero
 */
class NewExamBlockInfo {
    
    private int $maxExams;
    private int $cfu;
    private ?int $courseYear;
    
    public function __construct(int $maxExams, int $cfu, ?int $courseYear) {
        $this->maxExams = $maxExams;
        $this->cfu = $cfu;
        $this->courseYear = $courseYear;
    }
                
    public function getMaxExams(): int {
        return $this->maxExams;
    }

    public function getCfu(): int {
        return $this->cfu;
    }

    public function getCourseYear(): ?int {
        return $this->courseYear;
    }
}
