<?php

namespace App\Services\Implementations;

use App\Domain\ApprovedExam;
use App\Domain\StudyPlan;
use App\Services\Interfaces\ExamDistance;
use App\Models\Front;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\StudyPlanBuilder;
use Illuminate\Support\Collection;

/**
 * 
 */
class StudyPlanBuilderImpl implements StudyPlanBuilder {

    private $frontManager;
    private $studyPlan;
    private $eDistance;
    private $declaredExams;
    private $examOptions;

    function __construct(FrontManager $frontManager, ExamDistance $eDistance) {
        $this->frontManager = $frontManager;
        $this->studyPlan = new StudyPlan();
        $this->eDistance = $eDistance;
        $this->examOptions = [];
        $this->examBlocks = [];
    }

    public function getStudyPlan(): StudyPlan {
        return $this->studyPlan;
    }

    // private function getBestFit(DeclaredExam $declaredExam){
    // 	$exams = $this->frontManager->getExamsBySsd($declaredExam->getSsd());
    // 	$exams->mapWithKeys(function (ApprovedExam $exam) use ($declaredExam){
    // 		return [levenshtein($exam->getName(), $declaredExam->getName()) => $exam];
    // 	});
    // }
    

    public function getApprovedExams() {
        return $this->examOptions;
    }

    public function refreshStudyPlan() {
        $this->declaredExams = $this->frontManager->getTakenExams();
        $this->examOptions = $this->frontManager->getExamOptions();
        $this->buildStudyPlan();
    }
    
    public function setFront(Front $front) {
        $this->frontManager->setFront($front);
        $this->refreshStudyPlan();
    }

    public function getTakenExams(): Collection {
        return $this->declaredExams;
    }

    private function buildStudyPlan() {
        $this->studyPlan = new StudyPlan();
        $this->assignBySsd();
    }

    private function assignBySsd() {
        $this->declaredExams->each(function ($takenExam) {
            $eligibles = $this->getOptionsBySsdSorted($takenExam)->toArray();
            foreach ($eligibles as $eligible) {
                
            }
        });
    }
    
    private function getOptionsBySsdSorted($takenExam){
        return $this->examOptions
            ->filter(fn($option) => $option->getSsd() === $takenExam->getSsd())
            ->map(fn($option) => [
                "object" => $option,
                "distance" => $this->eDistance->calculateDistance($option, $takenExam)])
            ->sortBy("distance")
            ->map(fn($item) => $item["object"]);
    }
    
    public function testAssignBySsd() {
        $takenExam = $this->declaredExams->first();
        return $this->examOptions
            ->map(fn($option) => [
                "object" => $option,
                "distance" => $this->eDistance->calculateDistance($option, $takenExam)])
            ->sortBy("distance")
            ->map(fn($item) => $item["object"])->toArray();
    }
    
}
