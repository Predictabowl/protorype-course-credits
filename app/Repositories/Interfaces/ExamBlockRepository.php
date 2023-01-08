<?php

namespace App\Repositories\Interfaces;

use App\Models\ExamBlock;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
interface ExamBlockRepository {
    
    public function get(int $id): ?ExamBlock;
    public function getFilteredByCourse(int $courseId): Collection;
    public function save(ExamBlock $examBlock): bool;
    public function update(ExamBlock $examBlock);
    public function delete(int $id): bool;
}
