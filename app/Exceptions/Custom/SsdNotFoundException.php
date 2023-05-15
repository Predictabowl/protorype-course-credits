<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Exceptions\Custom;

use App\Http\Controllers\Support\ControllerHelpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Description of SsdNotFoundException
 *
 * @author piero
 */
class SsdNotFoundException extends Exception{
    
    public function render (Request $request): Response{
        return ControllerHelpers::flashResponse(
                [$this->getMessage()], 422);
    }
}
