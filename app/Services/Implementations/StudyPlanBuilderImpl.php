<?php

namespace App\Services\Implementations;

use App\Domain\LinkedTakenExam;
use App\Domain\TakenExamDTO;
use App\Domain\ExamOptionDTO;
use App\Domain\StudyPlan;
use App\Domain\ExamBlockLinker;
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
    private $blockLinkers;

    function __construct(FrontManager $frontManager, ExamDistance $eDistance) {
        $this->frontManager = $frontManager;
        $this->studyPlan = new StudyPlan();
        $this->eDistance = $eDistance;
        $this->examOptions = [];
        $this->blockLinkers = [];
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

    public function refreshStudyPlan(): StudyPlanBuilder {
        $this->blockLinkers = [];
        $this->declaredExams = $this->frontManager->getTakenExams()
                ->map(fn ($taken)=> new LinkedTakenExam($taken));
        $this->frontManager->getExamBlocks()->each(fn ($block)=>
            $this->blockLinkers[$block->getId()] = new ExamBlockLinker($block));
        $this->examOptions = $this->frontManager->getExamOptions();
        $this->buildStudyPlan();
        return $this;
    }
    
    public function setFront(Front $front): StudyPlanBuilder {
        $this->frontManager->setFront($front);
        $this->refreshStudyPlan();
        return $this;
    }

    public function getTakenExams(): Collection {
        return $this->declaredExams;
    }

    private function buildStudyPlan() {
        $this->studyPlan = new StudyPlan();
        $this->processAndInsert();
    }

    private function processAndInsert() {
        $leftover = $this->declaredExams->map(fn ($linkedExam) =>
            $this->processBySsd($linkedExam))->sum();
        if($leftover > 0){
            $this->declaredExams->each(fn($linkedExam) =>
                $this->processByCompatibility($linkedExam));
        }
    }
    
    public function processByCompatibility(LinkedTakenExam $linkedExam):int {
        $takenExam = $linkedExam->getTakenExam();
        //var_dump($takenExam);
        $eligibles = $this->getOptionsSorted(
                $this->examOptions->filter(function(ExamOptionDTO $option) use($takenExam){ 
                        $val = collect($option->getCompatibleOptions());
                        //$val = $val->contains(fn($option) => $option->getSsd() === $takenExam->getSsd());
                        $val = $val->contains(function($option) use($takenExam){
                            return $option->getSsd() === $takenExam->getSsd();
                        });
                        return $val;
                }),$takenExam);
        //var_dump($eligibles);
        return $this->linkExam($eligibles, $linkedExam);
    }
    
    public function processBySsd (LinkedTakenExam $linkedExam): int{
        $takenExam = $linkedExam->getTakenExam();
        $eligibles = $this->getOptionsSorted(
                $this->examOptions->filter(fn($option) => 
                        $option->getSsd() === $takenExam->getSsd())
                ,$takenExam);
        
        return $this->linkExam($eligibles, $linkedExam);
    }
    
    private function linkExam($options, LinkedTakenExam $linkedExam): int{
        foreach ($options as $option) {
            if ($this->blockLinkers[$option->getBlock()->getId()]->linkExam($option->getId())){
                $this->studyPlan->addExamLink($option, $linkedExam);
            }
            if ($linkedExam->getActualCfu() === 0){
                break;
            }
        }
        //var_dump($linkedExam->getActualCfu());
        return $linkedExam->getActualCfu();
    }
    
    private function getOptionsSorted(Collection $options, TakenExamDTO $takenExam){
        return $options->map(fn($option) => [
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
