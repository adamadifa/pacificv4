<form action="{{ route('pembayarangiro.approvestore', Crypt::encrypt($giro->kode_giro)) }}" method="POST" id="formApprovegiro">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode Giro</th>
                    <td>{{ $giro->kode_giro }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($giro->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Kode Pelanggan</th>
                    <td>{{ $giro->kode_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Nama Pelanggan</th>
                    <td>{{ $giro->nama_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Bank Pengirim</th>
                    <td>{{ $giro->bank_pengirim }}</td>
                </tr>
                <tr>
                    <th>Jatuh Tempo</th>
                    <td>{{ DateToIndo($giro->jatuh_tempo) }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($giro->status == '1')
                            <span class="badge bg-success">{{ DateToIndo($giro->tanggal_diterima) }}</span>
                        @elseif($giro->status == '2')
                            <i class="ti ti-square-rounded-x text-danger"></i>
                        @else
                            <i class="ti ti-hourglass-empty text-warning"></i>
                        @endif
                    </td>
                </tr>
                @if ($giro->status == '1')
                    <tr>
                        <th>No. Bukti Ledger</th>
                        <td>{{ $giro->no_bukti }}</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No. Faktur</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        <tr>
                            <td><a href="{{ route('penjualan.show', Crypt::encrypt($d->no_faktur)) }}" target="_blank">{{ $d->no_faktur }}</a></td>
                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div class="form-group mb-3">
                <select name="status" id="status" class="form-select">
                    <option value="">Status</option>
                    <option value="0">Pending</option>
                    <option value="1">Diterima</option>
                    <option value="2">Ditolak</option>
                </select>
            </div>
            <div class="form-group mb-3" id="tgl">
                <x-input-with-icon label="Tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" name="tanggal" />
            </div>
            <div class="form-group mb-3" id="bank">
                <select name="kode_bank" id="kode_bank" class="form-select select2Kodebank">
                    <option value="">Bank Penerima</option>
                    @foreach ($bank as $d)
                        <option value="{{ $d->kode_bank }}">
                            {{ !empty($d->nama_bank_alias) ? $d->nama_bank_alias : $d->nama_bank }}
                            {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row" id="omset">
        <div class="col">
            <div class="divider text-start">
                <div class="divider-text">
                    <i class="ti ti-sun me-2"></i> Omset
                </div>
            </div>
            <div class="row mt-2">
                <div class="col">
                    <div class="form-group mb-3">
                        <select name="omset_bulan" id="omset_bulan" class="form-select">
                            <option value="">Omset Bulan</option>
                            @foreach ($list_bulan as $d)
                                <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>{{ $d['nama_bulan'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <select name="omset_tahun" id="omset_tahun" class="form-select">
                            <option value="">Tahun</option>
                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                <option value="{{ $t }}" {{ $t == date('Y') ? 'selected' : '' }}>
                                    {{ $t }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/js/alert.js') }}"></script>
<script>
    $(function() {
        const form = $("#formApprovegiro");

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }

        function buttonEnable() {
            $("#btnSimpan").prop('disabled', false);
            $("#btnSimpan").html(`<i class="ti ti-send me-1"></i>Submit`);
        }
        $(".flatpickr-date").flatpickr();

        const select2Kodebank = $('.select2Kodebank');
        if (select2Kodebank.length) {
            select2Kodebank.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Bank Penerima',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function loadstatus() {
            const status = form.find("#status").val();
            if (status === '1') {
                $("#omset").show();
                $("#bank").show();
                $("#tgl").show();
            } else if (status === '2') {
                $("#bank").hide();
                $("#tgl").show();
                $("#omset").hide();
            } else {
                $("#bank").hide();
                $("#tgl").hide();
                $("#omset").hide();
            }
        }

        loadstatus();

        form.find("#status").change(function(e) {
            loadstatus();
        });

        form.submit(function(e) {

            const status = form.find("#status").val();
            const tanggal = form.find("#tanggal").val();
            const kode_bank = form.find("#kode_bank").val();
            const omset_bulan = $("#omset_bulan").val();
            const omset_tahun = $("#omset_tahun").val();
            if (status === '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Status Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#status").focus();
                    },
                });
                return false;
            } else if (status === '1' && tanggal === '' || status === '2' && tanggal === '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Tanggal Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (status === '1' && kode_bank === '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Bank Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#kode_bank").focus();
                    },
                });
                return false;
            } else if (status === '1' && omset_bulan === '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Omset Bulan Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#omset_bulan").focus();
                    },
                });
                return false;
            } else if (status === '1' && omset_tahun === '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Omset Tahun Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#omset_tahun").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
                return true;
            }
        });
    });
</script>
