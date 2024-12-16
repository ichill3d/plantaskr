<?php

namespace App\Livewire;

use Livewire\Component;

class TestCheckbox extends Component
{
    public $selected = [];

    public function updated($propertyName)
    {
        dd("Updated property: $propertyName", $this->$propertyName);
    }

    public function render()
    {
        return view('livewire.test-checkbox');
    }
}
