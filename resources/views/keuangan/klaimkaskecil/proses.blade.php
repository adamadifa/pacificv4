<style>
    .table-modal {
        height: auto;
        max-height: 550px;
        overflow-y: scroll;

    }
</style>
<form action="{{ route('klaimkaskecil.storeproses', Crypt::encrypt($klaim->kode_klaim)) }}" id="formProsesklaimkaskecil" method="POST">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th class="w-25">Kode Klaim</th>
                    <td>{{ $klaim->kode_klaim }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($klaim->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $klaim->keterangan }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td>{{ textUpperCase($klaim->nama_cabang) }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if (!empty($klaim->no_bukti))
                            <span class="badge bg-success">Sudah Di Proses</span>
                        @else
                            <span class="badge bg-danger">Belum Di Proses</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="table-modal">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Bukti</th>
                            <th class="w-25">Keterangan</th>
                            <th class="w-25">Akun</th>
                            <th>Penerimaan</th>
                            <th>Pengeluaran</th>
                            <th>Saldo</th>
                        </tr>
                        <tr>
                            <th colspan="6">Saldo Awal</th>
                            <td class="text-end">
                                @php
                                    $saldo_awal = $saldoawal ? $saldoawal->saldo_akhir : 0;
                                @endphp
                                {{ formatAngka($saldo_awal) }}
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $saldo = $saldo_awal;
                            $totalpenerimaan = 0;
                            $totalpengeluaran = 0;
                            $totalpenerimaannonpusat = 0;
                        @endphp
                        @foreach ($detail as $d)
                            @php
                                $penerimaan = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                                $pengeluaran = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                                $totalpenerimaan += $penerimaan;
                                $totalpengeluaran += $pengeluaran;
                                $saldo += $penerimaan - $pengeluaran;
                                if ($d->keterangan != 'Penerimaan Kas Kecil') {
                                    $totalpenerimaannonpusat += $penerimaan;
                                }
                            @endphp
                            <tr>
                                <td>{{ formatIndo2($d->tanggal) }}</td>
                                <td>{{ $d->no_bukti }}</td>
                                <td>{{ textCamelCase($d->keterangan) }}</td>
                                <td>{{ $d->kode_akun }} - {{ textCamelCase($d->nama_akun) }}</td>
                                <td class="text-end text-success">{{ formatAngka($penerimaan) }}</td>
                                <td class="text-end text-danger">{{ formatAngka($pengeluaran) }}</td>
                                <td class="text-end">{{ formatAngka($saldo) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">Total</th>
                            <td class="text-end" style="font-weight: bold;">{{ formatAngka($totalpenerimaan) }}</td>
                            <td class="text-end" style="font-weight: bold; ">{{ formatAngka($totalpengeluaran) }}</td>
                            <td class="text-end" style="font-weight: bold; ">{{ formatAngka($saldo) }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">Penggantian Kas Kecil</th>
                            <td class="text-end bg-white text-black" style="font-weight: bold" colspan="2">
                                @php
                                    $penggantian = $totalpengeluaran - $totalpenerimaannonpusat;
                                @endphp
                                {{ formatAngka($penggantian) }}
                            </td>
                            <td>Saldo Awal</td>
                            <td class="text-end bg-white text-black" style="font-weight:bold" colspan="2">
                                {{ formatAngka($saldoawal ? $saldoawal->saldo_akhir : 0) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">Terbilang</td>
                            <td class="text-end bg-white text-black" style="font-weight: bold" colspan="2">
                                <input type="hidden" name="jml_penggantian" value="{{ $penggantian }}">
                                <i>{{ textCamelCase(terbilang($penggantian)) }}</i>
                            </td>
                            <td>Penerimaan Pusat</td>
                            <td class="text-end bg-white text-black" style="font-weight: bold" colspan="2">{{ formatAngka($totalpenerimaan) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end bg-white text-black"></td>
                            <td>Total</td>
                            <td class="text-end bg-white text-black" style="font-weight: bold" colspan="2">
                                @php
                                    $total = $saldo_awal + $totalpenerimaan - $totalpenerimaannonpusat;
                                @endphp
                                {{ formatAngka($total) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end bg-white text-black"></td>
                            <td>Pengeluaran Kas Kecil</td>
                            <td class="text-end bg-white text-black" style="font-weight: bold" colspan="2">{{ formatAngka($totalpengeluaran) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end bg-white text-black"></td>
                            <td>Saldo Akhir</td>
                            <td class="text-end bg-white text-black" style="font-weight: bold" colspan="2">
                                <input type="hidden" name="saldo_akhir" value="{{ $saldo }}">
                                {{ formatAngka($saldo) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-2 col-md-12 col-sm-12">
            <x-input-with-icon label="Tanggal Proses" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" align="right" />
        </div>
        <div class="col-lg-5 col-md-12 com-sm-12">
            <x-input-with-icon label="Keterangan" name="keterangan" icon="ti ti-file-description" align="right" />
        </div>
        <div class="col-lg-3 col-md-12 col-sm-12">
            <div class="form-group mb-3">
                <select name="kode_bank" id="kode_bank" class="form-select select2Kodebank">
                    <option value="">Pilih Bank</option>
                    @foreach ($bank as $d)
                        <option value="{{ $d->kode_bank }}" {{ $d->kode_bank == 'BK054' ? 'selected' : '' }}>{{ $d->nama_bank }}
                            {{ !empty($d->no_rekening) ? '(' . $d->no_rekening . ')' : '' }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-2 col-md-12 col-sm-12">
            <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Proses Klaim</button>
        </div>
    </div>
</form>
<script>
    $(function() {
        const formProsesklaimkaskecil = $('#formProsesklaimkaskecil');

        function buttonDisable() {
            formProsesklaimkaskecil.find("#btnSimpan").prop("disabled", true);
            formProsesklaimkaskecil.find("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        $(".table-modal").freezeTable({
            'scrollable': true,
            'freezeColumn': false,
        });
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

        formProsesklaimkaskecil.submit(function(e) {
            const tanggal = formProsesklaimkaskecil.find("#tanggal").val();
            const keterangan = formProsesklaimkaskecil.find("#keterangan").val();
            const kode_bank = formProsesklaimkaskecil.find("#kode_bank").val();
            if (tanggal == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Tanggal  Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formProsesklaimkaskecil.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Keterangan Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formProsesklaimkaskecil.find("#keterangan").focus();
                    },
                });
                return false;
            } else if (kode_bank == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Bank Harus Diisi',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formProsesklaimkaskecil.find("#kode_bank").focus();
                    },
                });
                return false;
            } else {
                buttonDisable();
            }
        });
    });
</script>
