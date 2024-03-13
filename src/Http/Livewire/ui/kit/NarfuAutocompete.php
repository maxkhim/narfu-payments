<?php

declare(strict_types=1);

namespace Narfu\Payments\Http\Livewire\ui\kit;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Narfu\Payments\Models\PaymentCategory;
use function view;

class NarfuAutocompete extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public array $currentTenant = [];

    public array $tenants = [];
    public array $myTenants = [];

    public array $attrs = [];

    public string $data="xxx";
    public int $selectedTenant = 0;

    public ?string $search = '';
    public ?string $filterOutput = '';
    public ?bool $displaySearch = false;

    public const SEARCH_APPEAR_SIZE = 2;

    public function mount()
    {
        $this->selectedTenant = -1;
        $this->currentTenant["title"] = app('currentTenant')->name;
        $this->currentTenant["full_title"] = app('currentTenant')->full_name;

        $this->myTenants = PaymentCategory::query()
            ->pluck("id")
            ->toArray();

        $qtyTenants = PaymentCategory::select(["id"])
            ->whereIn("id", $this->myTenants)
            ->count();

        if ($qtyTenants >= self::SEARCH_APPEAR_SIZE) {
            $this->displaySearch = true;
        }
    }

    /**
     * Возвращает отфильтрованные тенанты.
     */
    protected function filtered()
    {
        $query = PaymentCategory::query();
        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function ($query) use ($search) {
                $query
                    ->where('title', 'LIKE', '%' . $search . '%');
            });
        }
        $query->whereIn("id", $this->myTenants);
        $query->orderBy('title');
        return $query;
    }

    public function render()
    {
        $this->tenants = $this->filtered()
            ->get()
            ->toArray();

        if ($this->selectedTenant >= count($this->tenants)) {
            $this->selectedTenant = count($this->tenants) - 1;
        }

        if (count($this->tenants) == 1) {
            $this->selectedTenant = 0;
        }

        return view('narfu-payments::components.ui.kit.autocomplete');
    }

    public function selectNext()
    {
        if ($this->selectedTenant < count($this->tenants)) {
            $this->selectedTenant++;
        } else {
            $this->selectedTenant = count($this->myTenants)-1;
        }
    }

    public function selectPrevious()
    {
        if ($this->selectedTenant > 0) {
            $this->selectedTenant--;
        } else {
            $this->selectedTenant = 0;
        }
    }

    public function selectTenant()
    {
        /*if (!isset($this->tenants[$this->selectedTenant])) {
            return;
        }

        return redirect($this->tenants[$this->selectedTenant]["redirect"]);*/

    }
}
