<?php

namespace App\Domain;

use App\Models\TakenExam;
use InvalidArgumentException;

/**
 * 
 */
class ApprovedExam
{
    private $name;
    private $cfu;
    private $declaredExams;


    /**
     * Class Constructor
     * @param    $name   
     * @param    $declaredExams   
     */
    public function __construct($name, int $cfu)
    {
        $this->name = $name;
        $this->validateAndSetCfu($cfu);
        $this->declaredExams = [];
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeclaredExams()
    {
        return $this->declaredExams;
    }

    /**
     * The object will be added only if there's no decifit in the
     * Integration value.
     * 
     * @param DeclaredExam $declaredExams
     *
     * @return Integration Value decifit.
     */
    public function addDeclaredExams(DeclaredExam $declaredExam): int
    {
        $value = $this->getIntegrationValue() - $declaredExam->getDistributedCfu();
        if ($value >= 0){
            $this->declaredExams[] = $declaredExam;
        }

        return $value;
    }

    public function addTakenExam(TakenExam $exam, int $cfuValue = 0): int
    {
        $integrationValue = $this->getIntegrationValue();
        if ($integrationValue < 1){
            return 0;
        } elseif ($integrationValue < $cfuValue){
            $cfuValue = $integrationValue;
        }
        $takenExam = new ExamAssignedValue($exam,$cfuValue);
        $this->takenExams[$exam->id] = $takenExam;
        return $takenExam->getCfuValue();
    }


    /**
     * @return mixed
     */
    public function getCfu(): int
    {
        return $this->cfu;
    }

    /**
     * @param mixed $cfu
     *
     * @return self
     */
    public function setCfu(int $cfu)
    {
        $this->validateAndSetCfu($cfu);

        return $this;
    }

    private function validateAndSetCfu(int $value)
    {
        if ($value <= 0){
            throw new InvalidArgumentException("The cfu value must be positive");
        }

        $this->cfu = $value;

    }

    public function getIntegrationValue(): int
    {
        return $this->cfu - collect($this->declaredExams)
            ->map(fn ($item) => $item->getDistributedCfu())
            ->sum();
    }
}