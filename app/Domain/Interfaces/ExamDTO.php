<?php

namespace App\Domain\Interfaces;

/**
 *
 * @author piero
 */
interface ExamDTO {

    public function getId();
    
    public function getExamName(): string;
    
    public function getCfu(): int;
    
    public function getSsd(): ?string;
}
