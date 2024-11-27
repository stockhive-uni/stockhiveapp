@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 bg-stockhive-grey rounded-lg hover:border-accent hover:shadow-accent hover:shadow-bxs focus:ring-accent transition-all text-white']) }}>
