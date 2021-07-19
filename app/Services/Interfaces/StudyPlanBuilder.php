<?php

namespace App\Services\Interfaces;

use App\Domain\StudyPlan;
use Illuminate\Support\Collection;

interface StudyPlanBuilder {

    public function getStudyPlan(): StudyPlan;

    public function getTakenExams(): Collection;

    public function refreshStudyPlan(): StudyPlanBuilder;

    public function setFront(int $id): StudyPlanBuilder;
}
