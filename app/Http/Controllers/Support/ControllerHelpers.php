<?php

namespace App\Http\Controllers\Support;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as ResponseFacade;

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of AppHelpers
 *
 * @author piero
 */
class ControllerHelpers {
    
    public static function flashResponse(array $data , int $statusCode): Response {
         return ResponseFacade::view("components.courses.flash-error",
                 ["flashErrors" => $data],
                 $statusCode);
    }
    
}
