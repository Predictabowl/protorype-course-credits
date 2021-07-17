<?php

namespace App\Repositories\Interfaces;

use App\Domain\ExamBlockDTO;
use Illuminate\Support\Collection;

/**
 *
 * @author piero
 */
interface ExamBlockRepository {
    
    public function get($id): ?ExamBlockDTO;
    public function getFromFront($frontId): Collection;
}
