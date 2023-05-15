<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Exceptions\Custom;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function abort;

class ExamBlockNotFoundException extends Exception{
    
    public function render (Request $request): Response{
        abort(404, $this->getMessage());
    }
}
