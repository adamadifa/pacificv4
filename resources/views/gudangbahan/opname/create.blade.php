@extends('layouts.app')
@section('titlepage', 'Buat Opname Gudang Bahan')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Opname Gudang Bahan /</span> Buat Opname
@endsection

<div class="row">
    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('opgudangbahan.store') }}" method="POST" id="formCreateopname">
                    @csrf
                    <div class="row">
                        <div class="col-12">

                            <div class="row">
                                <div class="form-group mb-3">
                                    <select name="bulan" id="bulan" class="form-select">
                                        <option value="">Bulan</option>
                                        @foreach ($list_bulan as $d)
                                            <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <select name="tahun" id="tahun" class="form-select">
                                        <option value="">Tahun</option>
                                        @for ($t = $start_year; $t <= date('Y'); $t++)
                                            <option value="{{ $t }}">{{ $t }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <a href="#" class="btn btn-success w-100" id="getsaldo">
                                        <i class="ti  ti-badges me-1"></i> Get Saldo
                                    </a>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th>Qty Unit</th>
                                            <th>Qty Berat</th>
                                        </tr>
                                    </thead>
                                    <tbody id="loaddetailsaldo">
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary w-100" type="submit">
                                    <ion-icon name="send-outline" class="me-1"></ion-icon>
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@push('myscript')
<script>
    $(function() {

        //Mendapatkan Data Detail Saldo
        function loaddetailsaldo() {
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Bulan !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#bulan").focus();
                    },
                });
                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Tahun !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#tahun").focus();
                    },
                });
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('opgudangbahan.getdetailsaldo') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun
                    },
                    cache: false,
                    success: function(respond) {
                        if (respond === '1') {
                            Swal.fire({
                                title: "Oops!",
                                text: "Saldo Awal Bulan Ini Belum Diset !",
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    $("#bulan").focus();
                                },
                            });
                            $("#loaddetailsaldo").html("");
                        } else {
                            $("#loaddetailsaldo").html(respond);
                        }
                    }
                });
            }
        }

        $("#getsaldo").click(function(e) {
            e.preventDefault();
            loaddetailsaldo();
        });

        $("#formCreateopname").submit(function(e) {
            const form = $("#formCreateopname");
            if (form.find('#loaddetailsaldo tr').length == 0) {
                Swal.fire({
                    title: "Oops!",
                    text: "Silakan Get Saldo Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formCreate.find("#kode_barang").focus();
                    },
                });

                return false;
            }
        });
    });
</script>
@endpush
