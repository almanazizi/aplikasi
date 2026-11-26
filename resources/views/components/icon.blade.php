@props(['name', 'class' => ''])
<svg class="icon {{ $class }}" viewBox="0 0 24 24" aria-hidden="true">
    <path d="{{ config('icons.' . $name, config('icons.file')) }}" />
</svg>
