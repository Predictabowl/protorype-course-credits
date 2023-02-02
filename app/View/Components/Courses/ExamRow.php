<?php

namespace App\View\Components\Courses;

use Illuminate\View\Component;

class ExamRow extends Component
{
    public $exam;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($exam)
    {
        $this->exam = $exam;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.courses.exam-row');
    }
}
