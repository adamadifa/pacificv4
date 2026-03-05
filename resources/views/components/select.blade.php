@props([
    'name',
    'id' => '',
    'label' => '',
    'data',
    'key',
    'textShow',
    'selected' => '',
    'upperCase' => false,
    'select2' => '',
    'showKey' => false,
    'disabled' => false,
    'multiple' => false,
    'hideLabel' => false,
    'allOption' => false,
    'allOptionLabel' => '',
])



@php
    $derivedId = str_replace('[]', '', $name);
    $id = !empty($id) ? $id : $derivedId;
@endphp
<div class="form-group mb-3">
    @if (!$hideLabel)
        <label class="form-label fw-bold" for="{{ $id }}">{{ $label }}</label>
    @endif
    <select name="{{ $name }}" id="{{ $id }}" class="form-select {{ $select2 }}" {{ $disabled ? 'disabled' : '' }} {{ $multiple ? 'multiple' : '' }}>
        @if (!$multiple)
            <option value="">{{ $label }}</option>
        @endif
        @if ($allOption)
            <option value="all"
                {{ is_array($selected) && in_array('all', $selected) ? 'selected' : '' }}>
                {{ !empty($allOptionLabel) ? $allOptionLabel : 'Semua ' . $label }}
            </option>
        @endif
        @foreach ($data as $d)
            @php
                $isSelected = false;
                if ($multiple && is_array($selected)) {
                    $isSelected = in_array($d->$key, $selected);
                } else {
                    $isSelected = $d->$key == $selected;
                }
            @endphp
            <option {{ $isSelected ? 'selected' : '' }} value="{{ $d->$key }}">
                {{ $showKey ? $d->$key . ' | ' : '' }}
                {{ $upperCase ? strtoupper(strtolower($d->$textShow)) : ucwords(strtolower($d->$textShow)) }}
            </option>
        @endforeach
    </select>
</div>
