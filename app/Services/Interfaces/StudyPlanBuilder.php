<?php

namespace App\Services\Interfaces;

use App\Domain\StudyPlan;
use Illuminate\Support\Collection;

interface StudyPlanBuilder {

    public function getStudyPlan(): StudyPlan;

    /**
     * It returns the declared exams with unused leftover credits
     * 
     * @return Collection
     */
    public function getTakenExams(): Collection;

//    public function refreshStudyPlan(): StudyPlanBuilder;

}
