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
    public function save(ExamBlock $examBlock): ExamBlock;
    public function update(ExamBlock $examBlock): ExamBlock;
    public function delete(int $id): bool;
}
