@extends('layouts.app')
@section('titlepage', 'Edit Opname Gudang Logistik')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Opname Gudang Logistik /</span> Edit Opname
@endsection

<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('opgudanglogistik.store') }}" method="POST" id="formOpname">
                    @csrf
                    <div class="row">
                        <div class="col-12">

                            <div class="row">
                                <div class="form-group mb-3">
                                    <select name="bulan" id="bulan" class="form-select">
                                        <option value="">Bulan</option>
                                        @foreach ($list_bulan as $d)
                                            <option value="{{ $d['kode_bulan'] }}" {{ $opname->bulan == $d['kode_bulan'] ? 'selected' : '' }}>
                                                {{ $d['nama_bulan'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <select name="tahun" id="tahun" class="form-select">
                                        <option value="">Tahun</option>
                                        @for ($t = $start_year; $t <= date('Y'); $t++)
                                            <option value="{{ $t }}" {{ $opname->tahun == $t ? 'selected' : '' }}>{{ $t }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <x-select label="Kategori" selected="{{ $opname->kode_kategori }}" name="kode_kategori" :data="$kategori"
                                    key="kode_kategori" textShow="nama_kategori" upperCase="true" />
                                <div class="form-group mb-3">
                                    <a href="#" class="btn btn-success w-100" id="getsaldo">
                                        <i class="ti  ti-refresh me-1"></i> Refresh Saldo
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
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody id="loaddetailsaldo">
                                        @foreach ($detail as $d)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
                                                    {{ $d->kode_barang }}
                                                </td>
                                                <td>{{ $d->nama_barang }}</td>
                                                <td class="text-end">
                                                    <input type="text" name="jumlah[]" value="{{ formatAngkaDesimal($d->jumlah) }}"
                                                        style="text-align: right" class="noborder-form number-separator">
                                                </td>
                                            </tr>
                                        @endforeach
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
            var kode_kategori = $("#kode_kategori").val();
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
            } else if (kode_kategori == "") {
                Swal.fire({
                    title: "Oops!",
                    text: "Silahkan Pilih dulu Kategori !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_kategori").focus();
                    },
                });
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('opgudanglogistik.getdetailsaldo') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun,
                        kode_kategori: kode_kategori
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

        $("#formOpname").submit(function(e) {
            const form = $("#formOpname");
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
