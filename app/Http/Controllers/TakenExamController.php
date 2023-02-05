<?php

namespace App\Http\Controllers;

use App\Models\Front;
use App\Services\Interfaces\FrontManager;
use Illuminate\Validation\Rule;
use function back;
use function request;

class TakenExamController extends Controller
{
    
    private FrontManager $frontManager;
    
    public function __construct(FrontManager $frontManager) {
        $this->middleware(["auth","verified"]);
        $this->frontManager = $frontManager;
    }

    /* Effectively the SSD validation that check it's existence is 
     * semantic and not syntactic. It basically skip every layer to check
     * directly on the Data Layer from the the Presentantion layer,
     * which is one of the worst pratices imho.
     * But the alternative here is to manually build all the scaffolding
     * in a proper layered structure, which will take considerable time
     * for such a simple task, so il take the pre baked solution even if 
     * I think is bad.
     * 
     */
    public function create(Front $front) {
        $this->authorize("create",$front);
        
        $inputs = request()->all();
        $inputs["ssd"] = strtoupper($inputs["ssd"]);
        request()->replace($inputs);
        $attributes = request()->validate([
            "name" => ["required", "string", "max:255"],
            "cfu" => ["required", "numeric", "min:1", "max:24"],
            "ssd" => ["required", Rule::exists("ssds", "code")],
            "grade" => ["required", "numeric", "min:18", "max:30"]
        ]);
        $this->frontManager->saveTakenExam($attributes, $front->id);
        return back()->with("success", "Aggiunto: ".$attributes["name"]);
    }
    
    public function delete(Front $front){
        $this->authorize("delete",$front);
        
        $exam = unserialize(request()->get("exam"));
        $this->frontManager->deleteTakenExam($exam->getId());
        
        return back()->with("success", "Eliminato: ".$exam->getExamName());
    }
    
    public function deleteFromFront(Front $front){
        $this->authorize("delete",$front);
        
        $this->frontManager->deleteAllTakenExams($front->id);
        
        return back()->with("success", "Eliminati tutti gli esami");
    }
    
}
