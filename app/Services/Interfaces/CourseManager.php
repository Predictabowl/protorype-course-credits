<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services\Interfaces;

use App\Models\Course;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
interface CourseManager {
 
    public function getExamBlocks(bool $cached = true): Collection;
    public function getExamOptions(bool $cached = true): Collection;
    public function getCourse(bool $cached = true): Course;
}
