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
        $this->eDistance = $eDistance;
        $this->examOptions = [];
        $this->blockLinkers = [];
    }

    public function getStudyPlan(): StudyPlan {
        if (!isset($this->studyPlan)){
            $this->buildStudyPlan();
        }
        return $this->studyPlan;
    }

    public function setFront(Front $front): StudyPlanBuilder {
        $this->frontManager->setFront($front);
        $this->refreshStudyPlan();
        return $this;
    }
    
    public function getApprovedExams() {
        return $this->examOptions;
    }
    
    public function refreshStudyPlan(): StudyPlanBuilder {
        $this->blockLinkers = $this->frontManager->getExamBlocks()
            ->mapWithKeys(fn ($block)=>
                [$block->getId() => new ExamBlockLinker($block)]);
        $this->declaredExams = $this->frontManager->getTakenExams()
                ->map(fn ($taken)=> new LinkedTakenExam($taken));
        $this->examOptions = $this->frontManager->getExamOptions();
        $this->studyPlan = null;
        
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
        $leftover = $this->declaredExams->map(fn($linkedExam) =>
            $this->linkExam(
                 $this->getOptionsBySsd($linkedExam->getTakenExam())
                 ,$linkedExam)
        )->sum();
        
        if($leftover > 0){
            $this->declaredExams->map(fn($linkedExam) =>
                $this->linkExam(
                    $this->getOptionsByCompatibility($linkedExam->getTakenExam())
                    ,$linkedExam)
            );
        }
    }
    
    public function getOptionsBySsd(TakenExamDTO $takenExam){
        return $this->getOptionsSorted(
                $this->examOptions->filter(fn($option) => 
                        $option->getSsd() === $takenExam->getSsd())
                ,$takenExam);
    }
    
//    public function getOptionsByCompatibility(TakenExamDTO $takenExam){
//        return $this->getOptionsSorted(
//                $this->examOptions->filter(fn($option) => 
//                    $option->getSsd() === $takenExam->getSsd())
//                        ->map(fn($option) => $option->getCompatibleOptions())
//                        ->flatten()->unique(),$takenExam);
//    }
    
    public function getOptionsByCompatibility(TakenExamDTO $takenExam){
        return $this->getOptionsSorted(
                $this->examOptions->filter(fn(ExamOptionDTO $option) => 
                    $option->getCompatibleOptions()->contains(
                        fn(string $ssd) => $ssd === $takenExam->getSsd()))
                ,$takenExam);
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
        return $linkedExam->getActualCfu();
    }
    
    private function getOptionsSorted(Collection $options, TakenExamDTO $takenExam){
        return $options->map(fn($option) => [
                "object" => $option,
                "distance" => $this->eDistance->calculateDistance($option, $takenExam)])
            ->sortBy("distance")
            ->map(fn($item) => $item["object"]);
    }
    
}
