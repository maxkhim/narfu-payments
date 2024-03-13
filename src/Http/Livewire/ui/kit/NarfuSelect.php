<?php

declare(strict_types=1);

namespace Narfu\Payments\Http\Livewire\ui\kit;

use Livewire\Component;
use function view;

class NarfuSelect extends Component
{
    public $items;

    public $selected = null;

    public $label;

    public $open = false;

    public function toggle()
    {
        $this->open = !$this->open;
    }

    public function select($index)
    {
        if (!$this->open) {
            return;
        }
        $this->selected = $this->selected !== $index ? $index : null;
        $this->open = false;
        $this->emit('itemSelect', (string) $this->selected);
    }

    public function render()
    {
        return view('narfu-payments::components.ui.kit.select');
    }
}
