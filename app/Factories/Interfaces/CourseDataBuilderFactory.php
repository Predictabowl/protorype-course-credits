<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Factories\Interfaces;

use App\Mappers\Interfaces\ExamBlockMapper;
use App\Models\Course;
use App\Services\Interfaces\CourseDataBuilder;

/**
 *
 * @author piero
 */
interface CourseDataBuilderFactory {
    
    public function get(Course $course): CourseDataBuilder;
}
