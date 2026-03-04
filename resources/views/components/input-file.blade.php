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
    <input class="form-control" type="file" id="{{ $name }}" name="{{ $name }}">
</div>
