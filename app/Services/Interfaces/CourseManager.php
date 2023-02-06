<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Services\Interfaces;

use App\Models\Course;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
interface CourseManager {

    public function getCourse(int $coruseId): ?Course;
    public function getCourseFullDepth(int $courseId): ?Course;
    public function getAllCourses(?array $filters = []): Collection;
    public function addCourse(Course $course): Course;
    public function removeCourse(int $courseId): bool;
    public function updateCourse(Course $course): Course;
}
