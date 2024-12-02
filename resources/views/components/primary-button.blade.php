@props(['nameEnter' => null, 'idEnter' => null, 'classEnter' => null])
@php
$name = "main";
if ($nameEnter != null) {
    $name = $nameEnter;
}
$id = "main";
if ($idEnter != null) {
    $id = $idEnter;
}
$class = "main";
if ($classEnter != null) {
    $class = $classEnter;
}
@endphp

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'text-center my-2 hover:scale-105 hover:opacity-55 transition-all rounded-lg py-2 px-4 bg-accent font-bold text-xl text-white ' . $class, 'name' => $name, 'id' => $id]) }}>
    {{ $slot }}
</button>
