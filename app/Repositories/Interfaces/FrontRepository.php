<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Interfaces;

use App\Models\Front;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Description of FrontRepository
 *
 * @author piero
 */
interface FrontRepository {

    public function get($id): ?Front;

    public function getFromUser($id): ?Front;

    public function getAll(array $filters, int $numInPage): Paginator;

    /**
     * Create a new Front if not present.
     * If the front is already present will update the course
     * If there's already the same user_id present then the front can't
     * be saved.
     *
     * @return Front|null the saved Front, null if can't be saved
     */
    public function save(Front $front): ?Front;

    /**
     * Update the course of an existing front.
     * Return null if the update fails
     *
     * @param $id
     * @param $courseId
     * @return Front|null the updated front.
     */
    public function updateCourse($id, $courseId): ?Front;

    public function delete($id): bool;
}
