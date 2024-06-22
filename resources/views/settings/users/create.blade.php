<form action="{{ route('users.store') }}" id="formcreateUser" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-user" label="Nama User" name="name" />
    <x-input-with-icon icon="ti ti-user" label="Username" name="username" />
    <x-input-with-icon icon="ti ti-mail" label="Email" name="email" />
    <x-input-with-icon icon="ti ti-key" label="Password" name="password" type="password" />
    <x-select label="Role" name="role" :data="$roles" key="name" textShow="name" />
    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" />
    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept" />
    <x-select label="Regional" name="kode_regional" :data="$regional" key="kode_regional" textShow="nama_regional" />

    @foreach ($deptchunks as $deptchunk)
        <div class="row">

            @foreach ($deptchunk as $dept)
                <div class="col-6">
                    <input class="form-check-input" name="dept_access[]" value="{{ $dept->kode_dept }}" type="checkbox" id="{{ $dept->kode_dept }}">
                    <label class="form-check-label" for="{{ $dept->kode_dept }}">{{ $dept->nama_dept }} </label>
                </div>
            @endforeach
        </div>
    @endforeach
    </div>
    </div>
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>

<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/users/create.js') }}"></script>
