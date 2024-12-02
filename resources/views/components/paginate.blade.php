@props(['items'])
<div class="bg-stockhive-grey-dark rounded-lg px-4">
    {{ $items->appends(request()->except('page'))->links()}}
</div>