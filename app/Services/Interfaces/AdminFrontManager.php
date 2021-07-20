<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\FrontInfoManager;

interface AdminFrontManager {

    public function createFront($userId, $courseId): int;

    public function deleteFront($userId): int;

    public function getFrontId($userId): ?int;
    
    public function getFrontInfoManager($userId): ?FrontInfoManager;
}
