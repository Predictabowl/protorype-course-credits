<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace App\Factories\Interfaces;

use App\Services\Interfaces\UserFrontManager;

/**
 *
 * @author piero
 */
interface UserFrontManagerFactory {
    public function get(int $userId): UserFrontManager;
}
