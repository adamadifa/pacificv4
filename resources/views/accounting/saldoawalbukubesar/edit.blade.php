@extends('layouts.app')
@section('titlepage', 'Buat Saldo Awal Buku Besar')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Saldo Awal Buku Besar /</span> Buat Saldo Awal
@endsection

<div class="row">
    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            {{-- <div class="card-header">
                @can('saldoawalbukubesar.create')
                    @if ($cek_saldo_awal == 0)
                        <a href="#" class="btn btn-primary"><i class="ti ti-plus me-1"></i>Input Saldo Awal</a>
                    @endif

                @endcan
            </div> --}}
            <div class="card-body">
                <form action="{{ route('saldoawalbukubesar.store') }}" method="POST" id="formCreatesaldoawal"
                    aria-autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <table class="table">
                                <tr>
                                    <th>Kode</th>
                                    <td>{{ $saldoawalbukubesar->kode_saldo_awal }}</td>
                                </tr>
                                <tr>
                                    <th>Bulan</th>
                                    <td>{{ $nama_bulan[$saldoawalbukubesar->bulan] }}</td>
                                </tr>
                                <tr>
                                    <th>Tahun</th>
                                    <td>{{ $saldoawalbukubesar->tahun }}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <select name="kode_akun" id="kode_akun" class="form-select select2Kodeakun">
                                    <option value="">Pilih Akun</option>
                                    @foreach ($coa as $d)
                                        <option value="{{ $d['kode_akun'] }}">{{ $d['kode_akun'] }} -
                                            {{ $d['nama_akun'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <input type="text" name="jumlah" id="jumlah" class="form-control text-end"
                                    placeholder="Jumlah">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12">
                            <div class="form-group mb-3">
                                <button type="button" class="btn btn-primary" id="addsaldoawal">
                                    <i class="ti ti-plus me-1"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nama Akun</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody id="loaddetailsaldo">
                                        @foreach ($detailsaldoawalbukubesar as $d)
                                            <tr id="idx-{{ $d->kode_akun }}">
                                                <td>
                                                    <input type="hidden" name="kode_akun[]" value="{{ $d->kode_akun }}"/>
                                                    {{ $d->nama_akun }}
                                                </td>
                                                <td class="text-end">
                                                    <input type="hidden" name="jumlah[]" value="{{ $d->jumlah }}"/>
                                                    {{ $d->jumlah }}
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
        $("#jumlah").maskMoney();
        const select2Kodeakun = $(".select2Kodeakun");
        if (select2Kodeakun.length) {
            select2Kodeakun.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Akun',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }
        $("#addsaldoawal").click(function(e) {
            e.preventDefault();
            const kode_akun = $("#kode_akun").val();
            const nama_akun = $("#kode_akun").select2('data')[0].text;
            const jumlah = $("#jumlah").val();
            if (kode_akun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Kode Akun Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#kode_akun").focus();
                    },
                });
                return false;
            } else if (jumlah == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Jumlah Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        $("#jumlah").focus();
                    },
                });
                return false;
            } else {
                let checkKodeAkun = $("#loaddetailsaldo tr").filter(function() {
                    return $(this).attr("id") == `idx-${kode_akun}`;
                }).length;
                if (checkKodeAkun > 0) {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Kode Akun Sudah Ada !',
                        icon: "warning",
                        showConfirmButton: true,
                    });
                } else {
                    $("#loaddetailsaldo").append(`
                    <tr id="idx-${kode_akun}">
                        <td>
                            <input type="hidden" name="kode_akun[]" value="${kode_akun}"/>
                            ${nama_akun}
                        </td>
                        <td class="text-end">
                            <input type="hidden" name="jumlah[]" value="${jumlah}"/>
                            ${jumlah}
                        </td>
                    </tr>
                    `);
                }
            }
        });
    })

    $(document).on('submit', '#formCreatesaldoawal', function(e) {
        let bulan = $("#bulan").val();
        let tahun = $("#tahun").val();
        let jmldata = $("#loaddetailsaldo tr").length;
        if(bulan == "" || tahun == "") {
            Swal.fire({
                title: "Oops!",
                text: 'Bulan dan Tahun Harus Diisi !',
                icon: "warning",
                showConfirmButton: true,
                didClose: (e) => {
                    $("#bulan").focus();
                },
            });
            return false;
        } else if(jmldata == 0) {
            Swal.fire({
                title: "Oops!",
                text: 'Data Saldo Harus Diisi !',
                icon: "warning",
                showConfirmButton: true,
                didClose: (e) => {
                    $("#kode_akun").focus();
                },
            });
            return false;
        } 
        
    })
</script>
@endpush
