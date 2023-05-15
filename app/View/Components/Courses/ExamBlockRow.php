<?php

namespace App\View\Components\Courses;

use App\Models\ExamBlock;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use function view;

class ExamBlockRow extends Component
{
    public ExamBlock $examBlock;
    public Collection $ssds;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(ExamBlock $examBlock, Collection $ssds)
    {
        $this->examBlock = $examBlock;
        $this->ssds = $ssds;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.courses.exam-block-row');
    }
}
