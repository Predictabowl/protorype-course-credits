<?php

namespace App\View\Components\Courses;

use Illuminate\View\Component;

class FlashError extends Component
{
    public $flashErrors;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($flashErrors)
    {
        $this->flashErrors = $flashErrors;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.courses.flash-error');
    }
}
