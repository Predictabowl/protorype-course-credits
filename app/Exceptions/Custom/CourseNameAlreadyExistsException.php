<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Exceptions\Custom;

use Exception;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CourseNameAlreadyExistsException extends Exception{
    
    public function render(): Response{
        throw ValidationException::withMessages(["name" => $this->getMessage()]);
    }
}
