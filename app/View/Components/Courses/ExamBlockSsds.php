<?php

namespace App\View\Components\Courses;

use App\Models\ExamBlock;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use function view;

class ExamBlockSsds extends Component
{
    public ExamBlock $examBlock;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(ExamBlock $examBlock) {
        $this->examBlock = $examBlock;
    }
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.courses.exam-block-ssds');
    }
}
