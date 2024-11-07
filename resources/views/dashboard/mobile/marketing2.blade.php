@extends('layouts.app')
@section('titlepage', 'Dashboard')
@section('content')
    {{-- <div class="row">
        <div class="col-xl-12">
            @include('dashboard.welcome')
        </div>
    </div> --}}
    <div class="row">
        <form action="#" id="formTarget">
            @hasanyrole($roles_show_cabang)
                <div class="form-group mb-3">
                    <select name="kode_cabang" id="kode_cabang" class="form-select select2Kodecabangpenjualan">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            @endrole
            <div class="form-group">
                <select name="kode_salesman" id="kode_salesman" class="select2Kodesalesman form-select">
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>
                            {{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}
                        </option>
                    @endfor
                </select>
            </div>
        </form>
        <div class="mt-3" id="loadtarget"></div>
    </div>
    <x-modal-form id="modal" show="loadmodal" title="" size="" />
@endsection
@push('myscript')
    <script>
        $(function() {
            const formTarget = $('#formTarget');

            function getsalesmanbyCabang() {
                var kode_cabang = formTarget.find("#kode_cabang").val();
                //alert(selected);
                $.ajax({
                    type: 'POST',
                    url: '/salesman/getsalesmanbycabang',
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cabang: kode_cabang
                    },
                    cache: false,
                    success: function(respond) {
                        console.log(respond);
                        formTarget.find("#kode_salesman").html(respond);
                    }
                });
            }

            getsalesmanbyCabang();
            formTarget.find("#kode_cabang").change(function(e) {
                getsalesmanbyCabang();
            });

            function loadtarget() {
                const kode_cabang = formTarget.find("#kode_cabang").val();
                const kode_salesman = formTarget.find("#kode_salesman").val();
                const bulan = formTarget.find("#bulan").val();
                const tahun = formTarget.find("#tahun").val();
                $.ajax({
                    type: "POST",
                    url: "/targetkomisi/gettarget",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_cabang: kode_cabang,
                        kode_salesman: kode_salesman
                    },
                    cache: false,
                    success: function(respond) {
                        $("#loadtarget").html(respond);
                    }
                });
            }



            loadtarget();


            formTarget.find("#kode_cabang,#kode_salesman,#bulan,#tahun").change(function() {
                loadtarget();
            });



        });
    </script>
@endpush
