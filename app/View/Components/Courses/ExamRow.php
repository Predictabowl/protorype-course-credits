<?php

namespace App\View\Components\Courses;

use App\Models\Exam;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use function view;

class ExamRow extends Component
{
    public Exam $exam;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.courses.exam-row');
    }
}
