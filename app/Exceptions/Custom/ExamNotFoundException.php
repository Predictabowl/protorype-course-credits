<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Exceptions\Custom;

use Exception;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Request;
use function abort;

class ExamNotFoundException extends Exception{
    
    public function render (Request $request): Response{
        abort(404, $this->getMessage());
    }
}
