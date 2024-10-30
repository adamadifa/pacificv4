<form action="{{ route('users.update', Crypt::encrypt($user->id)) }}" id="formeditUser" method="POST">
    {{-- {{ var_dump($user) }} --}}
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-user" label="Nama User" name="name" value="{{ $user->name }}" />
    <x-input-with-icon icon="ti ti-user" label="Username" name="username" value="{{ $user->username }}" />
    <x-input-with-icon icon="ti ti-mail" label="Email" name="email" value="{{ $user->email }}" />
    <x-input-with-icon icon="ti ti-key" label="Password" name="password" type="password" />
    <x-select label="Role" name="role" :data="$roles" key="name" textShow="name" selected="" select2="select2Role" upperCase="true" />
    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" selected="{{ $user->kode_cabang }}" />

    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept" selected="{{ $user->kode_dept }}" />
    <x-select label="Regional" name="kode_regional" :data="$regional" key="kode_regional" textShow="nama_regional"
        selected="{{ $user->kode_regional }}" />

    @foreach ($deptchunks as $deptchunk)
        <div class="row">

            @foreach ($deptchunk as $dept)
                <div class="col-6">
                    <input class="form-check-input" {{ in_array($dept->kode_dept, $dept_access) ? 'checked' : '' }} name="dept_access[]"
                        value="{{ $dept->kode_dept }}" type="checkbox" id="{{ $dept->kode_dept }}">
                    <label class="form-check-label" for="{{ $dept->kode_dept }}">{{ $dept->nama_dept }} </label>
                </div>
            @endforeach
        </div>
    @endforeach
    <div class="form-group mt-3">
        <select name="status" id="status" class="form-select">
            <option value="">Status</option>
            <option value="1" {{ $user->status == '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $user->status == '0' ? 'selected' : '' }}>Non Aktif</option>
        </select>
    </div>
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="repeat-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>

<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/users/edit.js') }}"></script>
<script>
    $(document).ready(function() {
        const select2Role = $(".select2Role");
        if (select2Role.length > 0) {
            select2Role.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Role',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>
