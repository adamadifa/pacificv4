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
                    <x-input-with-icon icon="ti ti-user" label="Username" name="username" value="{{ $user->username }}" />
                    <x-input-with-icon icon="ti ti-mail" label="Email" name="email" value="{{ $user->email }}" />
                    <x-input-with-icon icon="ti ti-key" label="Password" name="password" type="password" />

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
    });
</script>
@endpush
