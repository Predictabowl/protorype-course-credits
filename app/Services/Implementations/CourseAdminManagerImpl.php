<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Services\Implementations;

use App\Models\Exam;
use App\Models\ExamBlock;
use App\Repositories\Interfaces\CourseRepository;
use App\Services\Interfaces\CourseAdminManager;
use Illuminate\Support\Collection;
use TheSeer\Tokenizer\Exception;
use function collect;

/**
 * Description of CourseAdminManagerImpl
 *
 * @author piero
 */
class CourseAdminManagerImpl implements CourseAdminManager{

    private CourseRepository $courseRepo;

    public function __construct(CourseRepository $courseRepo) {
        $this->courseRepo = $courseRepo;
    }

    public function getAll(): Collection {
        return collect($this->courseRepo->getAll());
    }

    public function saveExam(Exam $exam, $examBlockId): bool {
        throw new Exception("Method not yet implemented");
    }

    public function saveExamBlock(ExamBlock $examBlock): bool {
        throw new Exception("Method not yet implemented");
    }
}
