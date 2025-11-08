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
                <form action="{{ route('saldoawalbukubesar.store') }}" method="POST" id="formCreatesaldoawal" aria-autocomplete="off">
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
                                        <i class="ti  ti-badges me-1"></i> <span id="getsaldo-text">Get Saldo</span>
                                        <span id="getsaldo-loading" class="spinner-border spinner-border-sm d-none" role="status"
                                            aria-hidden="true"></span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr>
                    {{-- <div class="row">
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
                    </div> --}}
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
                                        <tr id="loading-row" class="d-none">
                                            <td colspan="2" class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <p class="mt-2 mb-0 text-muted">Memuat saldo...</p>
                                            </td>
                                        </tr>
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

    $(document).on('click', '#getsaldo', function(e) {
        e.preventDefault();
        let bulan = $("#bulan").val();
        let tahun = $("#tahun").val();
        if (bulan == "" || tahun == "") {
            Swal.fire({
                title: "Oops!",
                text: 'Bulan dan Tahun Harus Diisi !',
            });
            return false;
        } else {
            // Tampilkan loading
            $("#getsaldo").addClass('disabled').css('pointer-events', 'none');
            $("#getsaldo-text").addClass('d-none');
            $("#getsaldo-loading").removeClass('d-none');
            $("#loading-row").removeClass('d-none');

            $.ajax({
                type: "POST",
                url: "{{ route('saldoawalbukubesar.getsaldo') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $("#loaddetailsaldo").html(respond);
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Terjadi kesalahan saat mengambil saldo!';

                    // Cek apakah response adalah JSON dengan pesan error
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {
                            // Jika bukan JSON, gunakan pesan default
                        }
                    }

                    Swal.fire({
                        title: "Oops!",
                        text: errorMessage,
                        icon: "error",
                    });
                    $("#loaddetailsaldo").html('');
                },
                complete: function() {
                    // Sembunyikan loading
                    $("#getsaldo").removeClass('disabled').css('pointer-events', 'auto');
                    $("#getsaldo-text").removeClass('d-none');
                    $("#getsaldo-loading").addClass('d-none');
                    $("#loading-row").addClass('d-none');
                }
            });
        }
    });

    $(document).on('submit', '#formCreatesaldoawal', function(e) {
        let bulan = $("#bulan").val();
        let tahun = $("#tahun").val();
        let jmldata = $("#loaddetailsaldo tr").length;
        if (bulan == "" || tahun == "") {
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
        } else if (jmldata == 0) {
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
