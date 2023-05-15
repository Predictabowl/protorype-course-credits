<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Factories\Implementations;

use App\Factories\Interfaces\CourseDataBuilderFactory;
use App\Mappers\Interfaces\ExamBlockMapper;
use App\Models\Course;
use App\Services\Implementations\CourseDataBuilderImpl;
use App\Services\Interfaces\CourseDataBuilder;

/**
 * Description of CourseDatabuilderFactoryImpl
 *
 * @author piero
 */
class CourseDatabuilderFactoryImpl implements CourseDataBuilderFactory{
    
    private ExamBlockMapper $ebMapper;
    
    public function __construct(ExamBlockMapper $ebMapper) {
        $this->ebMapper = $ebMapper;
    }

    
    public function get(Course $course): CourseDataBuilder {
        return new CourseDataBuilderImpl($course, $this->ebMapper);
    }

}
