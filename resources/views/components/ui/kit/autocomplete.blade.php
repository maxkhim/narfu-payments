<div class="mt-6">
    @if($currentTenant)
        <div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative">
            <div :class="open == true ? 'fill-indigo-600' : 'fill-gray-600'"
                 class="hidden lg:flex group flex items-center justify-center
                  text-gray-600 hover:text-indigo-600 hover:fill-indigo-600">
                <div :class="open == true ? 'bg-indigo-100' : 'bg-zinc-50'"
                     class="group-hover:bg-indigo-100 group-focus:bg-indigo-100
                      group-active:bg-indigo-100 group-focus:bg-indigo-100 text-sm text-indigo-600
                      rounded flex items-center py-1 px-3 justify-center cursor-pointer" @click="open = !open">
                    <div>
                        {{ $currentTenant['title'] }}
                    </div>
                </div>
                @if (count($tenants)>1 || $displaySearch)
                    <div class="mt-2 ml-2 cursor-pointer" @click="open = !open">
                        <x-tandem-support::icons.tandem.arrow-down class="w-3 h-3"/>
                    </div>
                @endif


                @if (count($tenants)>1 || $displaySearch)
                    <div x-show="open"
                         class="origin-top-left absolute top-0 mt-8 border-0 mr-4 z-10 w-80 rounded-md shadow" style="display: none;">
                        <div class="rounded-md bg-white border-0">
                            <div role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                @if($displaySearch)
                                    <div class="p-1.5">
                                        <x-narfu-payments::ui.kit.input name="search" placeholder="Найти"></x-narfu-payments::ui.kit.input>
                                    </div>
                                @endif

                                <div class="py-1 overflow-auto block scroll-tandem max-h-48">
                                    @forelse($tenants as $tenant)
                                        <a href="{{$tenant["title"]}}"
                                           class="whitespace-no-wrap block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-indigo-100 hover:text-indigo-600 focus:outline-none focus:bg-indigo-100 focus:text-indigo-600"
                                           role="menuitem">
                                            {{ $tenant['title']??"---" }}
                                        </a>
                                    @empty
                                        <div class="p-1.5 text-sm text-gray-700 py-2 px-4">
                                            {{ __("Тенанты не найдены") }}
                                        </div>
                                    @endforelse
                                </div>

                            </div>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    @endif
</div>
