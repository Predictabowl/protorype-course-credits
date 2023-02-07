<?php

namespace App\Http\Controllers;

use App\Domain\SsdCode;
use App\Http\Controllers\Support\ControllerHelpers;
use App\Models\Course;
use App\Services\Interfaces\ExamBlockManager;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use function request;
use function view;

class ExamBlockSsdController extends Controller
{
    private ExamBlockManager $ebManager;
    
    public function __construct(ExamBlockManager $ebSsdManager) {
        $this->middleware(["auth","verified"]);
        $this->ebManager = $ebSsdManager;
    }
    
        
    public function put(int $examBlockId){
        $this->authorize("create", Course::class);
        
        
        $ssdCode = new SsdCode($this->attributeValidation()["ssd"]);
        $this->ebManager->addSsd($examBlockId, $ssdCode);
        $examBlock = $this->ebManager->getExamBlockWithSsds($examBlockId);
        
        return view("components.courses.exam-block-ssds",[
            "examBlock" => $examBlock
        ]);
    }
    
    public function delete(int $examBlockId, int $ssdId){
        $this->authorize("delete", Course::class);
        
        $this->ebManager->removeSsd($examBlockId, $ssdId);
        
        $examBlock = $this->ebManager->getExamBlockWithSsds($examBlockId);
        return view("components.courses.exam-block-ssds",[
            "examBlock" => $examBlock
        ]);
    }
    
    private function attributeValidation(): array{
        $validationRules = ["ssd" => ["string"]];
        $validator = Validator::make(request()->all(), $validationRules);
        if($validator->fails()){
            throw new ValidationException($validator, 
                ControllerHelpers::flashResponse(
                    $validator->errors()->all(), 422));
        }
        return $validator->getData();
    }
    
}
