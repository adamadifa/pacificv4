@extends('layouts.app')
@section('titlepage', 'Buat Saldo Awal Piutang')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Saldo Awal Piutang /</span> Buat Saldo Awal
@endsection

<div class="row">
    <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
        <form action="{{ route('sapiutang.store') }}" method="POST" id="formCreatesaldoawal">
            @csrf
            <input type="hidden" name="data_saldo" id="data_saldo">
            <div class="card shadow-sm border mb-3">
                <div class="card-header border-bottom py-2" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                    <h6 class="m-0 fw-bold text-white" style="font-size: 0.85rem;"><i class="ti ti-calendar me-2"></i>Pilih Periode</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3 mt-1">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <select name="tahun" id="tahun" class="form-select">
                                    <option value="">Tahun</option>
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                        <option value="{{ $t }}">{{ $t }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <a href="#" class="btn btn-success w-100" id="getsaldo">
                                    <i class="ti ti-badges me-1"></i> Get Saldo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border">
                <div class="card-header border-bottom py-2" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                    <h6 class="m-0 fw-bold text-white" style="font-size: 0.85rem;"><i class="ti ti-list me-2"></i>Rincian Saldo Akhir Piutang</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 50vh; overflow-y: auto;">
                        <table class="table table-bordered table-striped table-sm mb-0">
                            <thead class="sticky-top">
                                <tr>
                                    <th style="background-color: #002e65; color: white;">No. Faktur</th>
                                    <th style="background-color: #002e65; color: white;">Tanggal</th>
                                    <th style="background-color: #002e65; color: white;">Pelanggan</th>
                                    <th class="text-end" style="background-color: #002e65; color: white; width: 25%">Saldo</th>
                                </tr>
                            </thead>
                            <tbody id="loaddetailsaldo">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-3 border-top" style="background-color: #e8f2ff;">
                    <div class="d-flex justify-content-between align-items-center px-2 mb-3">
                        <h5 class="mb-0 fw-bold text-primary"><i class="ti ti-calculator me-2"></i>TOTAL PIUTANG</h5>
                        <h5 class="mb-0 fw-bold text-primary" id="total_piutang" style="font-size: 1.5rem;">0</h5>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="ti ti-send me-1"></i> Simpan Saldo Awal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@push('myscript')
<script>
    $(function() {

        function calculateTotal() {
            let total = 0;
            $('.jumlah-piutang').each(function() {
                let val = $(this).val();
                total += parseFloat(val);
            });
            $('#total_piutang').text(new Intl.NumberFormat('id-ID').format(total));
        }

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
                    url: "{{ route('sapiutang.getdetailsaldo') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        bulan: bulan,
                        tahun: tahun
                    },
                    cache: false,
                    beforeSend: function() {
                        $("#loaddetailsaldo").html('<tr><td colspan="4" class="text-center"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div> Memuat data...</td></tr>');
                    },
                    success: function(respond) {
                        $("#loaddetailsaldo").html(respond);
                        calculateTotal();
                    }
                });
            }
        }

        $("#getsaldo").click(function(e) {
            e.preventDefault();
            loaddetailsaldo();
        });

        $("#formCreatesaldoawal").submit(function(e) {
            const form = $("#formCreatesaldoawal");
            if (form.find('#loaddetailsaldo tr').length == 0 || form.find('#loaddetailsaldo tr td').hasClass('text-center')) {
                Swal.fire({
                    title: "Oops!",
                    text: "Silakan Get Saldo Terlebih Dahulu !",
                    icon: "warning",
                    showConfirmButton: true
                });
                return false;
            }

            // Serialize data to JSON
            let data = [];
            $('.jumlah-piutang').each(function() {
                let no_faktur = $(this).closest('tr').find('.no_faktur').val();
                let jumlah = $(this).val();
                data.push({
                    no_faktur: no_faktur,
                    jumlah: jumlah
                });
            });
            $('#data_saldo').val(JSON.stringify(data));
        });
    });
</script>
@endpush
