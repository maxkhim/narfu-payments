<?php

declare(strict_types=1);

namespace Narfu\Payments\Http\Livewire\ui\kit;

use Livewire\Component;
use function view;

class NarfuSelect extends Component
{
    public $items;

    public $selected = null;
    public ?int $selectedIndex = null;
    public ?string $defaultId = null;
    public string $search = "";

    public $label;

    public $open = false;

    public function mount($items, $defaultId)
    {
        try {
            foreach ($items as $index => $item) {
                if (($item["id"] == $defaultId) && $defaultId) {
                    $this->open = true;
                    $this->select($index);
                }
            }
        } catch (\Exception $exception) {
        }
    }
    public function toggle()
    {
        $this->open = !$this->open;
    }

    public function select($index)
    {
        if (!$this->open) {
            return;
        }

        $this->selectedIndex = $this->selectedIndex !== $index ? $index : null;
        $this->selected = $this->selectedIndex !== $index ? $index : null;
        $this->open = false;
        $this->emit('itemSelect', (string) ($this->items[$this->selectedIndex]["id"] ?? null));
    }

    public function render()
    {
        return view('narfu-payments::components.ui.kit.select');
    }
}
