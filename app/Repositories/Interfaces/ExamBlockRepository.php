<?php

namespace App\Repositories\Interfaces;

use App\Models\ExamBlock;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
interface ExamBlockRepository {
    
    public function get($id): ?ExamBlock;
    public function getFilteredByCourse($courseId): Collection;
    public function save(ExamBlock $examBlock): bool;
}
