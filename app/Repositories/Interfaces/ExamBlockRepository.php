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
    public function getFromFront($frontId): Collection;
}
