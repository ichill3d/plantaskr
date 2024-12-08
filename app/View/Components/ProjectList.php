<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProjectList extends Component
{
    public $ajaxUrl;

    /**
     * Create a new component instance.
     *
     * @param string $ajaxUrl
     */
    public function __construct($ajaxUrl)
    {
        $this->ajaxUrl = $ajaxUrl;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.project-list');
    }
}
