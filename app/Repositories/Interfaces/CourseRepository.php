<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Interfaces;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

/**
 *
 * @author piero
 */
interface CourseRepository {

    public function get(int $id, bool $fullDepth = false): ?Course;
    
    public function getFromName(string $name): ?Course;
    
    public function getAll(array $filters = []): Collection;

    public function save(Course $course): bool;

    public function update(Course $course): bool;

    public function delete(int $id): bool;
}
