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
interface CoursesAdminManager {

    public function getAllCourses(?array $filters = []): Collection;
    public function addCourse(Course $course): void;
    public function removeCourse(int $courseId): bool;
    public function updateCourse(Course $course): void;
}
