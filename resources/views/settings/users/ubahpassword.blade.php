@extends('layouts.app')
@section('titlepage', 'Ubah Password')

@section('content')
@section('navigasi')
    <span>Ubah Password</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.updateprofile') }}" id="formeditUser" method="POST">
                    {{-- {{ var_dump($user) }} --}}
                    @csrf
                    @method('PUT')
                    <x-input-with-icon icon="ti ti-user" label="Nama User" name="name" value="{{ $user->name }}" />
                    @error('name')
                        <div class="text-danger small mb-3 mt-n2">{{ $message }}</div>
                    @enderror

                    <x-input-with-icon icon="ti ti-user" label="Username" name="username" value="{{ $user->username }}" />
                    @error('username')
                        <div class="text-danger small mb-3 mt-n2">{{ $message }}</div>
                    @enderror

                    <x-input-with-icon icon="ti ti-mail" label="Email" name="email" value="{{ $user->email }}" />
                    @error('email')
                        <div class="text-danger small mb-3 mt-n2">{{ $message }}</div>
                    @enderror

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold" for="password">Password</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti ti-key"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off" aria-autocomplete="none">
                            <span class="input-group-text cursor-pointer" id="togglePassword" style="cursor: pointer;">
                                <i class="ti ti-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>
                    @error('password')
                        <div class="text-danger small mb-3 mt-n2">{{ $message }}</div>
                    @enderror

                    <!-- Realtime Password Strength Indicator -->
                    <div id="password-requirements" class="mb-3 p-3 bg-light border rounded d-none" style="border-radius: 10px; background-color: #f8f9fa;">
                        <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.85rem;"><i class="ti ti-shield-check me-1 text-primary"></i> Persyaratan Password:</h6>
                        <ul class="list-unstyled mb-0" style="font-size: 0.8rem; padding-left: 0;">
                            <li id="req-length" class="text-danger mb-1 d-flex align-items-center gap-1">
                                <i class="ti ti-circle-x text-danger" style="font-size: 1.1rem;"></i> Minimal 8 karakter
                            </li>
                            <li id="req-uppercase" class="text-danger mb-1 d-flex align-items-center gap-1">
                                <i class="ti ti-circle-x text-danger" style="font-size: 1.1rem;"></i> Mengandung huruf besar (A-Z)
                            </li>
                            <li id="req-lowercase" class="text-danger mb-1 d-flex align-items-center gap-1">
                                <i class="ti ti-circle-x text-danger" style="font-size: 1.1rem;"></i> Mengandung huruf kecil (a-z)
                            </li>
                            <li id="req-number" class="text-danger mb-1 d-flex align-items-center gap-1">
                                <i class="ti ti-circle-x text-danger" style="font-size: 1.1rem;"></i> Mengandung angka (0-9)
                            </li>
                            <li id="req-symbol" class="text-danger d-flex align-items-center gap-1">
                                <i class="ti ti-circle-x text-danger" style="font-size: 1.1rem;"></i> Mengandung simbol/karakter spesial (@$!%*#?&)
                            </li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary w-100" type="submit">
                            <ion-icon name="repeat-outline" class="me-1"></ion-icon>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection



@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script src="{{ asset('assets/js/pages/users/edit.js') }}"></script>
<script>
    $(function() {
        $("#btncreateRole").click(function(e) {
            $('#mdlcreateRole').modal("show");
            $("#loadcreateRole").load('/roles/create');
        });

        $(".editRole").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdleditRole').modal("show");
            $("#loadeditRole").load('/roles/' + id + '/edit');
        });

        // Realtime Password Validator
        const passwordInput = $('#password');
        const requirementsContainer = $('#password-requirements');

        const reqs = {
            length: { regex: /.{8,}/, el: $('#req-length') },
            uppercase: { regex: /[A-Z]/, el: $('#req-uppercase') },
            lowercase: { regex: /[a-z]/, el: $('#req-lowercase') },
            number: { regex: /[0-9]/, el: $('#req-number') },
            symbol: { regex: /[@$!%*#?&]/, el: $('#req-symbol') }
        };

        passwordInput.on('focus', function() {
            requirementsContainer.removeClass('d-none');
        });

        passwordInput.on('input', function() {
            const val = $(this).val();
            
            if (val === '') {
                requirementsContainer.addClass('d-none');
                return;
            } else {
                requirementsContainer.removeClass('d-none');
            }

            for (const key in reqs) {
                const req = reqs[key];
                if (req.regex.test(val)) {
                    req.el.removeClass('text-danger').addClass('text-success');
                    req.el.find('i').removeClass('ti-circle-x').addClass('ti-circle-check').removeClass('text-danger').addClass('text-success');
                } else {
                    req.el.removeClass('text-success').addClass('text-danger');
                    req.el.find('i').removeClass('ti-circle-check').addClass('ti-circle-x').removeClass('text-success').addClass('text-danger');
                }
            }
        });

        // Toggle Password Visibility
        $('#togglePassword').on('click', function() {
            const passwordField = $('#password');
            const eyeIcon = $('#eyeIcon');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            
            if (type === 'password') {
                eyeIcon.removeClass('ti-eye-off').addClass('ti-eye');
            } else {
                eyeIcon.removeClass('ti-eye').addClass('ti-eye-off');
            }
        });
    });
</script>
@endpush
