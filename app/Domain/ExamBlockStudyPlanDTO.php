<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain;

use App\Domain\ExamStudyPlanDTO;
use Illuminate\Support\Collection;
use Serializable;
use function collect;

/**
 * Description of ExamBlockDTO
 *
 * @author piero
 */
class ExamBlockStudyPlanDTO implements Serializable{

    private int $id;
    private Collection $approvedExams;
    private int $numExams;
    private int $cfu;
    private ?int $courseYear;
    private Collection $compatibleOptions;

    public function __construct(int $id, int $numExams, int $cfu, ?int $courseYear) {
        $this->id = $id;
        $this->approvedExams = collect([]);
        $this->numExams = $numExams;
        $this->cfu = $cfu;
        $this->courseYear = $courseYear;
        $this->compatibleOptions = collect([]);
    }

    public function getId() {
        return $this->id;
    }

    public function setOption(ExamStudyPlanDTO $option) {
        $this->approvedExams[$option->getId()] = $option;
        return $this;
    }

    public function removeOption(ExamStudyPlanDTO $option) {
        unset($this->approvedExams[$option->getId()]);
        return $this;
    }

    public function getNumExams() {
        return $this->numExams;
    }

    public function getExamOptions() {
        return $this->approvedExams;
    }

    public function getExamOption($id): ExamStudyPlanDTO {
        return $this->approvedExams[$id];
    }

    public function getIntegrationValue(): int{

        return $this->getTotalCfu() - $this->getRecognizedCredits();
    }

    public function getRecognizedCredits(): int{

        return $this->approvedExams->map(fn(ExamStudyPlanDTO $exam) =>
                    $exam->getTakenExams()->map(fn(TakenExamDTO $taken)=>
                        $taken->getActualCfu()
                    )
                )->flatten()->sum();
    }

    public function getTotalCfu(): int {
        return $this->cfu * $this->numExams;
    }

    public function getCfu(): int {
        return $this->cfu;
    }

    public function getCourseYear(): ?int {
        return $this->courseYear;
    }

    public function getCompatibleOptions(): Collection {
        return $this->compatibleOptions;
    }
    
    public function addCompatibleOption(string $ssd){
        $this->compatibleOptions->push($ssd);
    }

    public function setCompatibleOptions(Collection $compatibleOptions): void {
        $this->compatibleOptions = $compatibleOptions;
    }

                /**
     * Return the number of exams that can actually be used,
     * which is the maximum number of options minus the ones already taken,
     * even if the taken options are not completely integrated
     */
    public function getNumSlotsAvailable(): int{
        return $this->getNumExams() -
                $this->approvedExams->map(fn(ExamStudyPlanDTO $exam) =>
                        $exam->getTakenExams()->isEmpty() ? 0 : 1)
                ->sum();
    }

    public function serialize(): string {
        return serialize($this->__serialize());
    }

    public function unserialize(string $serialized): void {
        $this->__unserialize(unserialize($serialized));
    }
    
    public function __serialize(): array {
        return [
            "id" => $this->id,
            "numExams" => $this->numExams,
            "cfu" => $this->cfu,
            "approvedExams" => $this->approvedExams,
            "compatibleOptions" => $this->compatibleOptions,
            "courseYear" => $this->courseYear
        ];
    }

    public function __unserialize(array $data) {
        $this->id = $data["id"];
        $this->numExams = $data["numExams"];
        $this->cfu = $data["cfu"];
        $this->courseYear = $data["courseYear"];
        $this->approvedExams = $data["approvedExams"]->map(function (ExamStudyPlanDTO $option){
                $option->setBlock($this);
                return $option;
            });
        $this->compatibleOptions = $data["compatibleOptions"];
    }

}
