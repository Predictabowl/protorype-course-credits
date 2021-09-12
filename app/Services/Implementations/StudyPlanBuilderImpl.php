<?php

namespace App\Services\Implementations;

use App\Domain\TakenExamDTO;
use App\Domain\ExamOptionDTO;
use App\Domain\StudyPlan;
use App\Services\Interfaces\ExamDistance;
use App\Services\Interfaces\FrontManager;
use App\Services\Interfaces\CourseManager;
use App\Services\Interfaces\StudyPlanBuilder;
use Illuminate\Support\Collection;

    /**
     *  The problem in the current building process is that it doesn't 
     * distinguish between Obligatory blocks and multiple choices blocks, so
     * it can happen a situation similar to priority inversion deadlock.
     * 
     * An exam could take a slot in a multi choice block ignoring a single 
     * option block, this will disable that block and further exams cannot 
     * be assigned to it, let's make a practical example:
     * 
     * Let's say we want integrate 2 exams with SSD IUS/01 and IUS/02
     * 1) Exam1 IUS/01
     * 2 Exam2 ISU/02
     *               
     * The study plan have the following blocks       
     * |Block1 | obligatory | IUS/01 | 
     * -------------------------------
     * |Block2 | option1    | IUS/01 |
     * |1 exam | option2    | IUS/02 |
     * -------------------------------
     * 
     * Exam1 is assigned to Block2-option2
     * Now Exam2 cannot be assigned because Block2 is full, and so exam2 is lost.
     * If we decided to take the block 1, both exam1 and exam2 would have been
     * integrated.
     * 
     * Problem is not this simple, here some critical points:
     * - The name of the exam matter, shouldn't be better to choose the closest 
     *   name regardless?
     * - Study Plans are not a random list of exams with random SSD, most 
     *   are made specifically to avoid these weird situations
     * - Every exam have a list of compatibility options that it seem to be
     *   meant to address this problem by design.
     */
class StudyPlanBuilderImpl implements StudyPlanBuilder {

    private $frontManager;
    private $courseManager;
    private $studyPlan;
    private $eDistance;
    public $declaredExams;
    private $examOptions;

    function __construct(FrontManager $frontManager, CourseManager $courseManager) {
        $this->frontManager = $frontManager;
        $this->courseManager = $courseManager;
        $this->eDistance = app()->make(ExamDistance::class);
        $this->examOptions = [];
    }

    public function getStudyPlan(): StudyPlan {
        $this->refreshStudyPlan()->buildStudyPlan();
        return $this->studyPlan;
    }
    
    public function refreshStudyPlan(): StudyPlanBuilder {
        $examBlocks = $this->courseManager->getExamBlocks();
        $this->declaredExams = $this->frontManager->getTakenExams();
        $this->examOptions = $this->courseManager->getExamOptions();
        $maxCfu = $this->courseManager->getCourse()->maxRecognizedCfu;
        $this->studyPlan = new StudyPlan($examBlocks, $maxCfu);
        return $this;
    }
    

    
    private function buildStudyPlan() {
        // First it checks direct ssd corrispondency
        $leftover = $this->declaredExams->map(fn($linkedExam) =>
            $this->linkExam($this->getOptionsBySsd($linkedExam)
                    ,$linkedExam)
        )->sum();
        
        //we could actually interrupt the search if the course's allotted credits are
        //exhuasted, but this is only an optimization, will think about when is finished.
        
        // If there's credits left to assign it checks the option's compatibility list
        if($leftover > 0){
            $leftover = $this->declaredExams->map(fn($linkedExam) =>
                $this->linkExam($this->getOptionsByCompatibility($linkedExam)
                            ,$linkedExam))->sum();
        }
        
        // Free choice exams are reserved for last
        if($leftover > 0){
            $this->declaredExams->map(fn($linkedExam) =>
                $this->linkExam($this->getFreeChoiceOptions($linkedExam)
                            ,$linkedExam));
        }
        
        //setting leftover exams and credit
        $this->studyPlan->setLeftoverExams($this->declaredExams->filter(
                fn(TakenExamDTO $exam) => $exam->getActualCfu() > 0));

    }
    
    /**
     * Return the ordered list of exams with the same ssd as the takenExam.
     * The order is ascending based on the distance calculated by the object eDistance.
     * @param TakenExamDTO $takenExam
     * @return type
     */
    public function getOptionsBySsd(TakenExamDTO $takenExam){
        return $this->sortOptions(
                $this->examOptions->filter(fn($option) => 
                        $option->getSsd() === $takenExam->getSsd())
                ,$takenExam);
    }
    
    /**
     * Return the ordered list of exams where their ssd appears in the option
     * compatibility list of ssds.
     * The order is ascending based on the distance calculated by the object eDistance.
     * 
     * @param TakenExamDTO $takenExam
     * @return type
     */
    public function getOptionsByCompatibility(TakenExamDTO $takenExam){
        return $this->sortOptions(
                $this->examOptions->filter(fn(ExamOptionDTO $option) => 
                    $option->getCompatibleOptions()->contains(
                            fn(string $ssd) => $ssd === $takenExam->getSsd()))
                ,$takenExam);
    }
    
    /**
     * Return the ordered list of exams with null ssd, as those are considered
     * by default as "any ssd will fit".
     * The order is ascending based on the distance calculated by the object eDistance.
     * @param TakenExamDTO $takenExam
     * @return type
     */
    public function getFreeChoiceOptions(TakenExamDTO $takenExam){
        return $this->sortOptions(
                $this->examOptions->filter(fn($option) => 
                        $option->getSsd() === null)
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
    
    private function linkExam($options, TakenExamDTO $linkedExam): int{
        foreach ($options as $option) {
            $this->studyPlan->addExamLink($option, $linkedExam);
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
