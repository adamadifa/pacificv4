@props(['name', 'label', 'data', 'key', 'textShow', 'selected' => ''])



<div class="form-group mb-3">
    <select name="{{ $name }}" id="{{ $name }}" class="form-select">
        <option value="">{{ $label }}</option>
        @foreach ($data as $d)
            <option {{ $d->$key == $selected ? 'selected' : '' }} value="{{ $d->$key }}">
                {{ ucwords(strtolower($d->$textShow)) }}
            </option>
        @endforeach
    </select>
</div>
