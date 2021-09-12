<?php

namespace App\Services\Interfaces;

use App\Domain\StudyPlan;

interface StudyPlanBuilder {

    public function getStudyPlan(): StudyPlan;


}
