@props([
    'icon' => '',
    'name' => '',
    'label' => '',
    'value' => 0,
    'readonly' => false,
    'type' => 'text',
    'align' => '',
    'disabled' => false,
    'money' => false,
    'numberFormat' => false,
    'datepicker' => '',
    'height' => '',
    'placeholder' => '',
    'hideLabel' => false,
])

<div class="input-group mb-1">
    @if (!$hideLabel)
        <span class="input-group-text" id="basic-addon11">{{ $label }}</span>
    @endif
   <input type="text" class="form-control {{ $money ? 'money' : '' }}" name="{{ $name }}" placeholder="{{ $placeholder }}" {{ $readonly ? 'readonly' : '' }} aria-describedby="basic-addon11"
      id="{{ $name }}" value="{{ $value }}" style="text-align: {{ $align }}; height:{{ $height }}">
</div>
