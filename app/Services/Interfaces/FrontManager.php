<?php

namespace App\Services\Interfaces;

use Illuminate\Support\Collection;
use App\Domain\TakenExamDTO;

interface FrontManager {

    /**
     * Must be called to initialize the instance.
     * Set up the currently used front.
     * If the front can't be found the instance will not be initialized.
     * 
     * @param int $id
     * @return int 1 if successful, 0 otherwise
     */
    //public function setFront(int $id): int;

    
    /**
     * Must be called to initialize the instance.
     * Set up the front associated with the user.
     * If the front doesn't exist and $created is true one will be created 
     * with not course associated.
     * 
     * @param int $userId
     * @param bool $create if set to true the front will be created
     * @return int 1 if successful, 0 otherwise
     */
    public function setUser(int $userId, bool $create = true): int;
    
    /**
     * Create a new front.
     * If the front already exist it won't create a new one, but will use
     * the existing one changing the course.
     * If $courseId is not specified will create one without an associated
     * course.
     * 
     * @param type $courseId
     * @return int the id of the front
     */
    public function createFront($courseId = null): int;

    /**
     * Set the course of the current front.
     * If a course is already set it will be changed.
     * 
     * @param type $courseId
     * @return int 0 if the front doesn't exists
     */
    public function setCourse($courseId): int;

    //public function deleteActiveFront(): int;

    public function getActiveFrontId(): ?int;

    public function getExamBlocks(): Collection;

    public function getTakenExams(): Collection;

    public function getExamOptions(): Collection;

    public function saveTakenExam(TakenExamDTO $exam);

    public function deleteTakenExam($id);
    
    /**
     * Wipe all attached TakenExams from the front
     */
    //public function wipeTakenExams();
}
