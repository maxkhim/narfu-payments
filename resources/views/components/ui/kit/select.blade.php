<div x-data="{
  highlighted: 0,
  count: {{ count($items) }},
  next() {
    this.highlighted = (this.highlighted + 1) % this.count;
  },
  previous() {
    this.highlighted = (this.highlighted + this.count - 1) % this.count;
  },
  select() {
    this.$wire.call('select', this.highlighted)
  },
  close() {
    if (this.$wire.open) {
     this.$wire.open = false;
    }
  }
}"
     @click.outside="close()"
     @keydown.escape="close()"
     x-init="highlighted =  {{ $selectedIndex ?: 0 }}" class="mt-5">
    <label class="text-gray-700">
        {{ $label }} <sup class="text-red-500">*</sup>
    </label>
    <div class="relative text-xs mt-1">
        <button
                wire:click="toggle"
                @keydown.prevent.arrow-down="next()"
                @keydown.prevent.arrow-up="previous()"
                @keydown.prevent.enter.prevent="select()"
                class="w-full flex justify-between bg-white border border-gray-300 form-input rounded-lg overflow-hidden"
        >
            @if ($items[$selectedIndex]["title"]??null)
                <span style="width: 97%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="text-left">
                    {{ $items[$selectedIndex]["title"] }}
                </span>
            @else
                Выберите ...
            @endif

            <div class="text-gray-700">
                @if ($open)
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                @endif
            </div>
        </button>
        @if ($open)

            <ul class="bg-white absolute mt-1 z-10 border rounded-lg w-full overflow-y-scroll max-h-56">
                @if(count($items) > 5)
                    <div class="p-1.5">
                        <x-narfu-payments::ui.kit.input name="search" live="true" placeholder="Найти получателя платежа"></x-narfu-payments::ui.kit.input>
                    </div>
                @endif
                @foreach($items as $id => $item)
                    <li wire:click="select({{ $id }})"
{{--                            @class([--}}
{{--                                'px-3 py-2 cursor-pointer flex items-center justify-between',--}}
{{--                                'bg-indigo-500 text-white' => $selected === $loop->index,--}}
{{--                                'hover:bg-indigo-200 hover:text-white',--}}
{{--                            ])--}}
                            x-data="{ index: {{ $id }} }"
                        class="px-3 py-2 cursor-pointer flex items-center justify-between text-xs
{{ (trim($this->search ?? "") && (mb_stripos($item["title"], $this->search) === false)) ? "hidden" : "" }}"
                        :class="{'bg-blue-400 text-white': index === highlighted}"
                        @mouseover="highlighted = index"
                    >
                        {{ $item["title"] }}

                        @if (($items[$selectedIndex]["id"]??null) == $item["id"])
                            <div :class="index === highlighted ? 'text-white' : 'text-blue-500'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>