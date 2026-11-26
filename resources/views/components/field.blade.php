@props(['label', 'name', 'type' => 'text', 'value' => null, 'hint' => null])
<div class="field">
    <label for="{{ $name }}">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
        @if ($value !== null) value="{{ $value }}" @endif
        @error($name) aria-invalid="true" aria-describedby="form-error" @enderror
        {{ $attributes }}>
    @if ($hint)<small>{{ $hint }}</small>@endif
</div>
