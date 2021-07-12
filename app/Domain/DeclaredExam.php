<?php

namespace App\Domain;

use InvalidArgumentException;

/**
 * 
 */
class DeclaredExam
{
    private $name;
    private $ssd;
    private $maxCfu;
    private $distributedCfu;


    /**
     * Class Constructor
     * @param    $name   
     * @param    $maxCfu   
     */
    public function __construct(string $name, string $ssd, int $maxCfu, int $distributedCfu = 0)
    {
        $this->name = $name;
        $this->ssd = $ssd;
        $this->distributedCfu = $maxCfu; //kind of dirty, only to pass the first validation.
        $this->validateAndSetCfu($maxCfu);
        $this->validateAndSetDistributedCfu($distributedCfu);
    }


    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getMaxCfu():int
    {
        return $this->maxCfu;
    }

    /**
     * @param mixed $name
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param mixed $maxCfu
     *
     * @return self
     */
    public function setMaxCfu(int $maxCfu)
    {
        $this->validateAndSetCfu($maxCfu);
        return $this;
    }


    /**
     * @return mixed
     */
    public function getDistributedCfu():int 
    {
        return $this->distributedCfu;
    }

    /**
     * @param mixed $distributedCfu
     *
     * @return self
     */
    public function setDistributedCfu(int $distributedCfu)
    {
        $this->validateAndSetDistributedCfu($distributedCfu);

        return $this;
    }

    public function split(int $distributedCfu): DeclaredExam
    {
        $this->setDistributedCfu($this->distributedCfu-$distributedCfu);
        return new DeclaredExam($this->name,$this->ssd,$this->maxCfu,$distributedCfu);
    }

    /**
     * @return mixed
     */
    public function getSsd(): string
    {
        return $this->ssd;
    }

    /**
     * @param mixed $ssd
     *
     * @return self
     */
    public function setSsd(string $ssd)
    {
        $this->ssd = $ssd;

        return $this;
    }


    private function validateAndSetCfu(int $value)
    {
        if ($value <= 0){
            throw new InvalidArgumentException("The max cfu value must be positive");
        } elseif ($value < $this->distributedCfu){
            throw new InvalidArgumentException("The max cfu value cannot be lower than the distributed value");
        }

        $this->maxCfu = $value;
    }   

    private function validateAndSetDistributedCfu(int $value)
    {
        if ($value == 0){
            $value = $this->maxCfu;
        }elseif ($value < 0){
            throw new InvalidArgumentException("The distributed cfu value must be positive");
        }elseif($value > $this->maxCfu){
            throw new InvalidArgumentException("The distributed cfu cannot be higher than the max cfu value");
        }
        $this->distributedCfu = $value;
    }   

}