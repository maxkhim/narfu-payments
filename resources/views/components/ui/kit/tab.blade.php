@props(
[
    "label" => "",
    "currentId" => null,
    "tabKey" => "item",
    "id" => ""
])
<div>
    <a href="?{{$tabKey}}={{$id}}"
       class="px-3 py-2 text-base leading-5 font-medium rounded-full text-gray-600 hover:text-gray-800 focus:outline-none
       focus:text-gray-800 focus:bg-gray-200 @if ( $id == $currentId ) bg-indigo-200 @else hover:bg-gray-100 @endif focus:bg-gray-300 font-semibold mr-4">
    @if ($label)
            {{$label}} dd
    @endif
    </a>
</div>
