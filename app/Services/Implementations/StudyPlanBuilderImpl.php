<?php

namespace App\Services\Implementations;

use App\Domain\LinkedTakenExam;
use App\Domain\TakenExamDTO;
use App\Domain\ExamOptionDTO;
use App\Domain\StudyPlan;
use App\Domain\ExamBlockLinker;
use App\Services\Interfaces\ExamDistance;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\CourseManager;
use App\Services\Interfaces\StudyPlanBuilder;
use Illuminate\Support\Collection;

/**
 * 
 */
class StudyPlanBuilderImpl implements StudyPlanBuilder {

    private $frontManager;
    private $courseManager;
    private $studyPlan;
    private $eDistance;
    private $declaredExams;
    private $examOptions;
    private $blockLinkers; //keep tracks of wich options in a block are linked

    function __construct(FrontManager $frontManager, CourseManager $courseManager) {
        $this->frontManager = $frontManager;
        $this->courseManager = $courseManager;
        $this->eDistance = app()->make(ExamDistance::class);
        $this->examOptions = [];
        $this->blockLinkers = [];
    }

    public function getStudyPlan(): StudyPlan {
        $this->refreshStudyPlan();
        if (!isset($this->studyPlan)){
            $this->buildStudyPlan();
        }
        return $this->studyPlan;
    }

//    public function setFront(int $id): StudyPlanBuilder {
//        $this->frontManager->setFront($id);
//        $this->refreshStudyPlan();
//        return $this;
//    }
    
    public function getApprovedExams() {
        return $this->examOptions;
    }
    
    public function refreshStudyPlan(): StudyPlanBuilder {
        $this->blockLinkers = $this->courseManager->getExamBlocks()
            ->mapWithKeys(fn ($block)=>
                [$block->getId() => new ExamBlockLinker($block)]);
        $this->declaredExams = $this->frontManager->getTakenExams()
                ->map(fn ($taken)=> new LinkedTakenExam($taken));
        $this->examOptions = $this->courseManager->getExamOptions();
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
        return $this->sortOptions(
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
        return $this->sortOptions(
                $this->examOptions->filter(fn(ExamOptionDTO $option) => 
                    $option->getCompatibleOptions()->contains(
                        fn(string $ssd) => $ssd === $takenExam->getSsd()))
                ,$takenExam);
    }
    
    /**
     * Link the takenExam to the examOptions sequentially, until all
     * the credits of takenExam are linked or the options are exhausted.
     * 
     * @param type $options
     * @param LinkedTakenExam $linkedExam
     * @return int leftover cfu from takenExam
     */
    
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
    
    /**
     * Sort ascending base on examDistance value
     * 
     * @param Collection $options
     * @param TakenExamDTO $takenExam
     * @return type
     */
    
    private function sortOptions(Collection $options, TakenExamDTO $takenExam){
        return $options->map(fn($option) => [
                "object" => $option,
                "distance" => $this->eDistance->calculateDistance($option, $takenExam)])
            ->sortBy("distance")
            ->map(fn($item) => $item["object"]);
    }
    
}
