@php

@endphp
<form action="{{ route('bpb.serahterimabpbstore') }}" method="POST" id="formSerahTerima" autocomplete="off">
    @csrf

    <!-- Hidden wajib -->
    <input type="hidden" name="kode_dept" value="{{ $bpb->kode_dept }}">
    <input type="hidden" name="kode_cabang" value="{{ $bpb->kode_cabang }}">
    <input type="hidden" name="no_ref" value="{{ $bpb->no_bpb }}">

    <!-- INFORMASI BPB -->
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong>Informasi BPB</strong>
        </div>
        <div class="card-body p-2">
            <table class="table table-sm mb-0">
                <tr>
                    <th width="25%">No. BPB</th>
                    <td>{{ $bpb->no_bpb }}</td>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($bpb->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td>{{ $bpb->nama_dept }}</td>
                    <th>Cabang</th>
                    <td>{{ $bpb->nama_cabang }}</td>
                </tr>
                <tr>
                    <th>User Input</th>
                    <td>{{ $bpb->nama_user }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- DETAIL BARANG -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <strong>Detail Barang</strong>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle">
                    <thead class="table-secondary text-center">
                        <tr>
                            <th style="width:3%">No</th>
                            <th style="width:10%">Kode</th>
                            <th style="width:25%">Nama Barang</th>
                            <th style="width:8%" class="text-end">Jumlah</th>
                            <th style="width:8%" class="text-end">Diterima</th>
                            <th style="width:8%" class="text-end">Sisa</th>
                            @if (Auth::user()->id == '67')
                                <th style="width:10%" class="text-end">Serahkan</th>
                            @endif
                            <th style="width:6%">Satuan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalSisa = 0;
                        @endphp
                        @foreach ($detail as $d)
                            @php
                                $sudahDiserahkan = $diserahkanTotal[$d->kode_barang] ?? 0;
                                $sisa = $d->jumlah - $sudahDiserahkan;
                                $totalSisa += $sisa;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $d->kode_barang }}</td>
                                <td>{{ textCamelCase($d->nama_barang) }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($d->jumlah) }}</td>
                                <td class="text-end text-success">{{ formatAngkaDesimal($sudahDiserahkan) }}</td>
                                <td class="text-end text-danger">{{ formatAngkaDesimal($sisa) }}</td>
                                @if (Auth::user()->id == '67')
                                    <td style="width:120px">
                                        <input type="number" name="diserahkan[{{ $d->kode_barang }}]"
                                            class="form-control form-control-sm text-end diserahkan"
                                            data-sisa="{{ $sisa }}" min="0"
                                            {{ $sisa <= 0 || $bpb->approve_gudang == 0 ? 'readonly' : '' }}>

                                    </td>
                                @endif
                                <td>{{ $d->satuan }}</td>
                                <td>{{ $d->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    {{-- <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="5" class="text-end">TOTAL SISA</td>
                            <td class="text-end text-danger">
                                {{ formatAngkaDesimal($totalSisa) }}
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot> --}}
                </table>
            </div>
        </div>
    </div>

    <!-- ACTION BAR -->
    @if (Auth::user()->id == '67' && $totalSisa > 0)
        <div class="card mt-3 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <x-input-with-icon icon="ti ti-calendar" label="Tanggal Serah Terima" name="tanggal_diserahkan"
                    value="{{ date('Y-m-d') }}" datepicker="flatpickr-date" />

                <button class="btn btn-success px-4">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
        </div>
    @endif
</form>

<!-- HISTORY -->
<div class="card mt-4 shadow-sm">
    <div class="card-header bg-secondary text-white mb-3">
        <strong>History Serah Terima</strong>
    </div>
    <div class="card-body ">
        @forelse ($serahTerima as $s)
            <div class="border rounded p-2 mb-3">
                <div class="mb-2">
                    {{ $s->no_bukti }}<br>
                    {{ DateToIndo($s->tanggal) }}
                </div>
                <div class="mb-2">
                    @if ($s->diterima == 0)
                        <select class="form-select form-select-sm terimaSurat" data-no_bukti="{{ $s->no_bukti }}">
                            <option value="">-- Pilih --</option>
                            <option value="1" @selected($s->diterima == 1)>Terima</option>
                        </select>
                    @else
                        <a href="#"
                            class="btn btn-sm btn-success">{{ $s->diterima == '1' ? 'Sudah Diterima' : '' }}</a>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:3%">No</th>
                                <th style="width:12%">Kode</th>
                                <th style="width:20%">Nama Barang</th>
                                <th style="width:10%" class="text-end">Jumlah</th>
                                <th style="width:8%">Satuan</th>
                                <th>Keterangan</th>
                                @if ($s->diterima == 0)
                                    <th style="width:15%" class="text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historyDetail[$s->no_bukti] ?? [] as $h)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $h->kode_barang }}</td>
                                    <td>{{ $h->nama_barang }}</td>
                                    <td class="text-end">
                                        <input type="text" class="form-control form-control-sm editJumlah"
                                            value="{{ $h->jumlah }}" data-no_bukti="{{ $h->no_bukti }}"
                                            data-kode_barang="{{ $h->kode_barang }}"
                                            {{ $totalSisa <= 0 ? 'readonly' : '' }}>
                                    </td>
                                    <td>{{ $h->satuan }}</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm editKeterangan"
                                            value="{{ $h->keterangan }}" data-no_bukti="{{ $h->no_bukti }}"
                                            data-kode_barang="{{ $h->kode_barang }}">
                                    </td>
                                    @if ($s->diterima == 0)
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-danger deleteHistoryBtn"
                                                data-no_bukti="{{ $h->no_bukti }}"
                                                data-kode_barang="{{ $h->kode_barang }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="text-center text-muted">Belum ada history serah terima.</div>
        @endforelse
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }, ]
        });

        $(".editJumlah").on("change", function() {

            let jumlah = $(this).val();
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            $.post("{{ route('bpb.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                kode_barang: kode_barang,
                jumlah: jumlah
            }, function(resp) {

                Swal.fire({
                    icon: "success",
                    title: "Jumlah diperbarui",
                    timer: 900,
                    showConfirmButton: false
                });

            });

        });


        // ===========================
        // AUTO UPDATE KETERANGAN
        // ===========================
        $(".editKeterangan").on("change", function() {

            let keterangan = $(this).val();
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            $.post("{{ route('bpb.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                kode_barang: kode_barang,
                keterangan: keterangan
            }, function(resp) {

                Swal.fire({
                    icon: "success",
                    title: "Keterangan diperbarui",
                    timer: 900,
                    showConfirmButton: false
                });

            });

        });

        $(".terimaSurat").on("change", function() {

            let no_bukti = $(this).data("no_bukti");

            $.post("{{ route('bpb.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                diterima: 1
            }, function(resp) {

                Swal.fire({
                    icon: "success",
                    title: "Keterangan diperbarui",
                    timer: 900,
                    showConfirmButton: false
                });

            });

        });


        // DELETE
        $(".deleteHistoryBtn").on("click", function() {
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            Swal.fire({
                title: "Hapus Data?",
                text: "Data serah terima akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ route('bpb.deleteSerahTerima') }}", {
                        _token: "{{ csrf_token() }}",
                        no_bukti: no_bukti,
                        kode_barang: kode_barang
                    }, function(res) {
                        location.reload();
                    });
                }
            });
        });

        $(".diserahkan").on("input", function() {

            let row = $(this).closest("tr");
            let sisa = parseFloat($(this).data("sisa")) || 0;
            let val = parseFloat($(this).val()) || 0;

            if (val > sisa) {
                Swal.fire({
                    icon: "error",
                    title: "Tidak Valid!",
                    text: "Jumlah diserahkan (" + val + ") melebihi sisa (" + sisa + ")",
                    confirmButtonColor: "#d33"
                });

                $(this).val(sisa); // set input ke sisa
                row.addClass("table-danger");
            } else {
                row.removeClass("table-danger");
            }
        });

    });
</script>
