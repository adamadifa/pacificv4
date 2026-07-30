<form action="{{ route('permissions.updateAccess', Crypt::encrypt($permission->id)) }}" id="formPermissionAccess" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label text-primary font-weight-bold">Nama Permission</label>
        <div class="form-control-plaintext fs-5 font-weight-bold">{{ $permission->name }}</div>
    </div>

    <div class="row">
        <!-- Roles List -->
        <div class="col-md-6 mb-3">
            <h6 class="border-bottom pb-2 text-info"><i class="fa fa-users me-1"></i> Roles dengan Akses</h6>
            @if($roles->isEmpty())
                <p class="text-muted fs-7">Tidak ada role yang memiliki akses ke permission ini.</p>
            @else
                <div class="list-group max-height-300 overflow-auto" style="max-height: 250px; overflow-y: auto;">
                    @foreach($roles as $role)
                        <label class="list-group-item d-flex align-items-center cursor-pointer border-0 ps-0">
                            <input class="form-check-input me-2" type="checkbox" name="roles[]" value="{{ $role->id }}" checked>
                            <span>{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Users List -->
        <div class="col-md-6 mb-3">
            <h6 class="border-bottom pb-2 text-info"><i class="fa fa-user me-1"></i> Users dengan Akses Langsung</h6>
            @if($users->isEmpty())
                <p class="text-muted fs-7">Tidak ada user dengan akses langsung ke permission ini.</p>
            @else
                <div class="list-group max-height-300 overflow-auto" style="max-height: 250px; overflow-y: auto;">
                    @foreach($users as $user)
                        <label class="list-group-item d-flex align-items-center cursor-pointer border-0 ps-0">
                            <input class="form-check-input me-2" type="checkbox" name="users[]" value="{{ $user->id }}" checked>
                            <div>
                                <span class="d-block">{{ $user->name }}</span>
                                <small class="text-muted">{{ $user->username }}</small>
                            </div>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="form-group mt-3">
        <button class="btn btn-primary w-100" type="submit">
            <i class="fa fa-save me-1"></i> Simpan Perubahan Akses
        </button>
    </div>
</form>
