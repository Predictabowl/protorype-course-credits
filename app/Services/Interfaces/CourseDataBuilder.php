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
interface CourseDataBuilder {
 
    public function getExamBlocks(): Collection;
    public function getExamOptions(): Collection;
    public function getCourse(): Course;
}
