@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'hideLabel' => false,
])
<div class="form-group mb-3">
    @if (!$hideLabel)
        <label class="form-label fw-bold" for="{{ $name }}">{{ $label }}</label>
    @endif
    <textarea class="form-control" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $label }}"
        rows="2">{{ $value }}</textarea>
</div>
