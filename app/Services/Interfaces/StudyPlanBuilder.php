<?php

namespace App\Services\Interfaces;

use App\Domain\StudyPlan;
use App\Models\Front;
use Illuminate\Support\Collection;

interface StudyPlanBuilder {

    public function getStudyPlan(): StudyPlan;
    public function getTakenExams(): Collection;
    public function refreshStudyPlan();
    public function setFront(Front $front);
}
