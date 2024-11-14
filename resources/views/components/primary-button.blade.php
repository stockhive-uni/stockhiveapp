<button {{ $attributes->merge(['type' => 'submit', 'class' => 'text-center my-2 hover:scale-105 hover:opacity-55 transition-all rounded-lg py-2 px-4 bg-accent font-bold text-white bg-accent text-xl']) }}>
    {{ $slot }}
</button>
