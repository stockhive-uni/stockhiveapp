@props(['items'])
<div class="flex justify-between items-center gap-8 my-4 border-grey bg-stockhive-grey rounded-lg p-4 border-2 m-auto w-[90%] text-right">
    {{ $items->appends(request()->except('page'))->links()}}
    <x-primary-button>Create Order</x-primary-button>
</div>