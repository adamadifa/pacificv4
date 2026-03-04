@props([
    'name',
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
])



<div class="form-group mb-3">
    @php
        $id = str_replace('[]', '', $name);
    @endphp
    @if ($label && !$hideLabel)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif
    <select name="{{ $name }}" id="{{ $id }}" class="form-select {{ $select2 }}" {{ $disabled ? 'disabled' : '' }} {{ $multiple ? 'multiple' : '' }}>
        @if (!$multiple)
            <option value="">{{ $label }}</option>
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
