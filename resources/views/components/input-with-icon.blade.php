@props([
    'icon' => '',
    'name' => '',
    'label' => '',
    'value' => '',
    'readonly' => false,
    'type' => 'text',
    'disabled' => false,
])
<div class="form-group mb-3">
    <div class="input-group input-group-merge">
        <span class="input-group-text" id="basic-addon-search31"><i class="{{ $icon }}"></i></span>
        <input type="{{ $type }}" class="form-control" id="{{ $name }}" name="{{ $name }}"
            placeholder="{{ $label }}" {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}
            autocomplete="off" aria-autocomplete="none" value="{{ $value }}">
    </div>
</div>
