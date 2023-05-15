<?php

namespace App\Repositories\Interfaces;

use App\Models\ExamBlock;

/**
 *
 * @author piero
 */
interface ExamBlockRepository {
    
    public function get(int $id): ?ExamBlock;
    public function getWithSsds(int $id): ?ExamBlock;
    public function getWithFullDepth(int $id): ?ExamBlock;
    public function save(ExamBlock $examBlock): ExamBlock;
    public function update(ExamBlock $examBlock): ExamBlock;
    public function delete(int $id): bool;
    public function attachSsd(int $examBlockId, int $ssdId): void;
    public function detachSsd(int $examBlockId, int $ssdId): void;
}
