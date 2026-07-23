@extends('layouts.app')
@section('titlepage', 'BPPB')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Detail BPPB</h4>
            <small class="text-muted">Detail pengajuan Bukti Permintaan & Penerimaan Barang (BPPB) Pembelian.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Pembelian</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('bpbpembelian.index') }}"><i class="ti ti-receipt me-1"></i>BPPB</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-file-text me-1"></i>Detail BPPB</li>
            </ol>
        </nav>
    </div>
@endsection

@php
    $totalJumlah = 0;
    $totalDiserahkan = 0;
    $totalSisa = 0;
    foreach ($detail as $d) {
        $sudahDiserahkan = $diserahkanTotal[$d->kode_barang] ?? 0;
        $sisa = $d->jumlah - $sudahDiserahkan;
        $totalJumlah += $d->jumlah;
        $totalDiserahkan += $sudahDiserahkan;
        $totalSisa += $sisa;
    }

    // Overall completion percentage
    $percentComplete = $totalJumlah > 0 ? round(($totalDiserahkan / $totalJumlah) * 100) : 0;

    // BPPB Handover status
    if ($totalSisa <= 0) {
        $statusBadgeClass = 'bg-success text-white';
        $statusText = 'Selesai';
    } elseif ($totalDiserahkan > 0) {
        $statusBadgeClass = 'bg-warning text-dark';
        $statusText = 'Sebagian';
    } else {
        $statusBadgeClass = 'bg-secondary text-white';
        $statusText = 'Belum Diserahkan';
    }
@endphp

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <form action="{{ route('bpbpembelian.serahterimabpbstore') }}" method="POST" id="formSerahTerima"
            autocomplete="off">
            @csrf

            <!-- Hidden wajib -->
            <input type="hidden" name="kode_dept" value="{{ $bpb->kode_dept }}">
            <input type="hidden" name="kode_cabang" value="{{ $bpb->kode_cabang }}">
            <input type="hidden" name="no_ref" value="{{ $bpb->no_bpb }}">

            <!-- INFORMASI & SUMMARY BPB -->
            <div class="row mb-4">
                <!-- Informasi BPB Card -->
                <div class="col-md-8 col-sm-12">
                    <div class="card h-100 border shadow-sm">
                        <div class="card-header py-3"
                            style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-white d-flex align-items-center gap-2">
                                    <i class="ti ti-receipt fs-4"></i>
                                    <span>Informasi BPPB</span>
                                </h6>
                                <div class="d-flex align-items-center gap-2">
                                    @if (Auth::user()->id == '54' && $bpb->approve_gudang == '1')
                                        <a href="{{ route('bpbpembelian.cetak', Crypt::encrypt($bpb->no_bpb)) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-light d-flex align-items-center gap-1 shadow-sm font-semibold text-primary"
                                            style="font-size: 13px;">
                                            <i class="ti ti-printer fs-5"></i> <span>Cetak</span>
                                        </a>
                                    @endif
                                    <span class="badge rounded-pill {{ $statusBadgeClass }} shadow-sm px-3 py-1_5 fs-7">
                                        {{ $statusText }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded"
                                        style="background-color: #fafbfc; border-left: 4px solid #002e65 !important;">
                                        <small class="text-muted d-block text-uppercase fw-bold mb-1"
                                            style="font-size: 11px; letter-spacing: 0.5px;">No. BPPB</small>
                                        <span class="fs-5 fw-bold text-dark">{{ $bpb->no_bpb }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded"
                                        style="background-color: #fafbfc; border-left: 4px solid #0ea5e9 !important;">
                                        <small class="text-muted d-block text-uppercase fw-bold mb-1"
                                            style="font-size: 11px; letter-spacing: 0.5px;">Tanggal</small>
                                        <span class="fs-5 fw-bold text-dark">{{ DateToIndo($bpb->tanggal) }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded"
                                        style="background-color: #fafbfc; border-left: 4px solid #10b981 !important;">
                                        <small class="text-muted d-block text-uppercase fw-bold mb-1"
                                            style="font-size: 11px; letter-spacing: 0.5px;">Departemen</small>
                                        <span class="fs-5 fw-semibold text-dark">{{ $bpb->nama_dept }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 border rounded"
                                        style="background-color: #fafbfc; border-left: 4px solid #f59e0b !important;">
                                        <small class="text-muted d-block text-uppercase fw-bold mb-1"
                                            style="font-size: 11px; letter-spacing: 0.5px;">Cabang</small>
                                        <span class="fs-5 fw-semibold text-dark">{{ $bpb->nama_cabang }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-2 mt-1 px-1">
                                        <i class="ti ti-user text-secondary"></i>
                                        <span class="text-muted small fw-semibold">User Input: <strong
                                                class="text-dark">{{ $bpb->nama_user }}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progres Serah Terima Card -->
                <div class="col-md-4 col-sm-12 mt-3 mt-md-0">
                    <div class="card h-100 border shadow-sm">
                        <div class="card-header py-3"
                            style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                            <h6 class="m-0 fw-bold text-white d-flex align-items-center gap-2">
                                <i class="ti ti-chart-pie fs-4"></i>
                                <span>Progres Penyerahan</span>
                            </h6>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between pt-3">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1 mt-1">
                                    <span class="text-muted fw-semibold">Status Serah Terima</span>
                                    <span class="fw-bold text-success fs-5">{{ $percentComplete }}%</span>
                                </div>
                                <div class="progress mb-4" style="height: 12px; border-radius: 6px;">
                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                        role="progressbar" style="width: {{ $percentComplete }}%"
                                        aria-valuenow="{{ $percentComplete }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2 text-center mb-1">
                                <div class="col-4">
                                    <div class="p-2 border rounded"
                                        style="background-color: #fafbfc; border-top: 3px solid #002e65 !important;">
                                        <h5 class="mb-0 fw-bold text-primary">{{ formatAngkaDesimal($totalJumlah) }}
                                        </h5>
                                        <small class="text-muted fw-semibold" style="font-size: 10px;">Total
                                            Order</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 border rounded"
                                        style="background-color: #fafbfc; border-top: 3px solid #10b981 !important;">
                                        <h5 class="mb-0 fw-bold text-success">
                                            {{ formatAngkaDesimal($totalDiserahkan) }}</h5>
                                        <small class="text-muted fw-semibold"
                                            style="font-size: 10px;">Diserahkan</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 border rounded"
                                        style="background-color: #fafbfc; border-top: 3px solid #ef4444 !important;">
                                        <h5 class="mb-0 fw-bold text-danger">{{ formatAngkaDesimal($totalSisa) }}</h5>
                                        <small class="text-muted fw-semibold" style="font-size: 10px;">Sisa</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETAIL BARANG -->
            <div class="card shadow-sm border mb-4">
                <div class="card-header py-3"
                    style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                    <h6 class="m-0 fw-bold text-white d-flex align-items-center gap-2">
                        <i class="ti ti-box fs-4"></i>
                        <span>Detail Barang Permintaan</span>
                    </h6>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead style="background-color: #002e65;">
                            <tr>
                                <th class="text-white text-center" style="width: 5%">No</th>
                                <th class="text-white" style="width: 15%">Kode</th>
                                <th class="text-white">Nama Barang</th>
                                <th class="text-white text-end" style="width: 10%">Jumlah</th>
                                <th class="text-white text-end" style="width: 10%">Diterima</th>
                                <th class="text-white text-end" style="width: 10%">Sisa</th>
                                <th class="text-white text-center" style="width: 15%">Progres</th>
                                @if (Auth::user()->id == '54' || Auth::user()->id == '2')
                                    <th class="text-white text-end" style="width: 12%">Serahkan</th>
                                @endif
                                <th class="text-white" style="width: 10%">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $totalSisa = 0;
                            @endphp
                            @foreach ($detail as $d)
                                @php
                                    $sudahDiserahkan = $diserahkanTotal[$d->kode_barang] ?? 0;
                                    $sisa = $d->jumlah - $sudahDiserahkan;
                                    $totalSisa += $sisa;
                                    $itemProgress = $d->jumlah > 0 ? round(($sudahDiserahkan / $d->jumlah) * 100) : 0;
                                @endphp
                                <tr>
                                    <td class="text-center fw-semibold">{{ $loop->iteration }}</td>
                                    <td><span
                                            class="badge bg-secondary text-white font-monospace shadow-xs">{{ $d->kode_barang }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ textCamelCase($d->nama_barang) }}
                                            ({{ $d->satuan }})
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        {{ formatAngkaDesimal($d->jumlah) }}
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        {{ formatAngkaDesimal($sudahDiserahkan) }}
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        {{ formatAngkaDesimal($sisa) }}
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center gap-2 justify-content-center">
                                            <div class="progress w-100 shadow-xs"
                                                style="height: 6px; min-width: 60px; border-radius: 3px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $itemProgress }}%"></div>
                                            </div>
                                            <small class="text-muted fw-bold">{{ $itemProgress }}%</small>
                                        </div>
                                    </td>
                                    @if (Auth::user()->id == '54' || Auth::user()->id == '2')
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="diserahkan[{{ $d->kode_barang }}]"
                                                    class="form-control text-end diserahkan shadow-none"
                                                    data-sisa="{{ $sisa }}" min="0" placeholder="0"
                                                    style="border-color: #cbd5e1;"
                                                    {{ $sisa <= 0 || $bpb->approve_gudang == 0 ? 'readonly' : '' }}>
                                            </div>
                                        </td>
                                    @endif
                                    <td><span class="text-muted small">{{ $d->keterangan ?: '-' }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ACTION BAR -->
            @if (Auth::user()->id == '54' || (Auth::user()->id == '2' && $totalSisa > 0))
                <div class="card shadow-sm mb-4 border" style="border-left: 4px solid #10b981 !important;">
                    <div class="card-body py-3">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3 col-sm-12">
                                <x-input-with-icon icon="ti ti-calendar" label="Tanggal Serah Terima"
                                    name="tanggal_diserahkan" required value="{{ date('Y-m-d') }}"
                                    datepicker="flatpickr-date" />
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label fw-semibold text-dark mb-1">Pilih Supplier</label>
                                    <select name="kode_supplier" class="form-select select2KodeSupplier w-100"
                                        required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach ($supplier as $s)
                                            <option value="{{ $s->kode_supplier }}">
                                                {{ textUpperCase($s->nama_supplier) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label fw-semibold text-dark mb-1">PPN</label>
                                    <select name="ppn" id="ppn" class="form-select" required>
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12 text-end">
                                <button type="submit"
                                    class="btn btn-success px-4 shadow-sm w-100 d-flex align-items-center justify-content-center gap-2"
                                    style="height: 38px;">
                                    <i class="ti ti-device-floppy"></i> <span>Simpan Serah Terima</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </form>

        <!-- HISTORY -->
        <div class="card shadow-sm border mt-4">
            <div class="card-header py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white d-flex align-items-center gap-2">
                    <i class="ti ti-history fs-4"></i>
                    <span>History Serah Terima</span>
                </h6>
            </div>
            <div class="card-body pt-3">
                @forelse ($serahTerima as $s)
                    @php
                        $cardBorderLeft =
                            $s->diterima == 1
                                ? 'border-left: 4px solid #10b981 !important;'
                                : 'border-left: 4px solid #f59e0b !important;';
                    @endphp
                    <div class="card border mb-3 shadow-xs" style="{{ $cardBorderLeft }}">
                        <div class="card-header py-2 px-3 d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom"
                            style="background-color: #fafbfc;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-file-text text-primary fs-5"></i>
                                <span class="fw-bold text-dark">{{ $s->no_bukti }}</span>
                                <span class="text-muted mx-1">|</span>
                                <i class="ti ti-calendar text-secondary"></i>
                                <span class="text-muted small fw-semibold">{{ DateToIndo($s->tanggal) }}</span>
                            </div>
                            <div>
                                @if ($s->diterima == 0)
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-warning text-dark shadow-sm px-2 py-1"><i
                                                class="ti ti-clock me-1" style="font-size:12px"></i>Menunggu
                                            Konfirmasi</span>
                                        <select class="form-select form-select-sm terimaSurat border-primary py-0 px-2"
                                            data-no_bukti="{{ $s->no_bukti }}"
                                            style="width: auto; height: 26px; font-size: 12px; border-color: #002e65 !important;">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" @selected($s->diterima == 1)>Terima</option>
                                        </select>
                                    </div>
                                @else
                                    <span class="badge bg-success text-white shadow-sm px-3 py-1_5"><i
                                            class="ti ti-circle-check me-1"></i>Sudah Diterima</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead style="background-color: #475569;">
                                        <tr>
                                            <th class="text-white text-center" style="width: 5%; padding: 6px 12px;">
                                                No</th>
                                            <th class="text-white" style="width: 15%; padding: 6px 12px;">Kode</th>
                                            <th class="text-white" style="padding: 6px 12px;">Nama Barang</th>
                                            <th class="text-white text-end" style="width: 15%; padding: 6px 12px;">
                                                Jumlah</th>
                                            <th class="text-white" style="width: 10%; padding: 6px 12px;">Satuan</th>
                                            <th class="text-white" style="padding: 6px 12px;">Keterangan</th>
                                            @if ($s->diterima == 0)
                                                <th class="text-white text-center"
                                                    style="width: 10%; padding: 6px 12px;">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($historyDetail[$s->no_bukti] ?? [] as $h)
                                            <tr>
                                                <td class="text-center fw-semibold">{{ $loop->iteration }}</td>
                                                <td><span
                                                        class="badge bg-secondary text-white font-monospace shadow-xs">{{ $h->kode_barang }}</span>
                                                </td>
                                                <td class="fw-semibold text-dark">{{ $h->nama_barang }}</td>
                                                <td class="text-end" style="max-width: 100px;">
                                                    <input type="text"
                                                        class="form-control form-control-sm text-end editJumlah px-2 shadow-none"
                                                        value="{{ $h->jumlah }}"
                                                        data-no_bukti="{{ $h->no_bukti }}"
                                                        data-kode_barang="{{ $h->kode_barang }}"
                                                        style="border-color: #cbd5e1;"
                                                        {{ $s->diterima != 0 || $totalSisa <= 0 ? 'readonly' : '' }}>
                                                </td>
                                                <td><span
                                                        class="badge bg-info text-white shadow-xs">{{ $h->satuan }}</span>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        class="form-control form-control-sm editKeterangan shadow-none"
                                                        value="{{ $h->keterangan }}"
                                                        data-no_bukti="{{ $h->no_bukti }}"
                                                        data-kode_barang="{{ $h->kode_barang }}"
                                                        placeholder="Edit keterangan..."
                                                        style="border-color: #cbd5e1;"
                                                        {{ $s->diterima != 0 ? 'readonly' : '' }}>
                                                </td>
                                                @if ($s->diterima == 0)
                                                    <td class="text-center">
                                                        <a href="#"
                                                            class="deleteHistoryBtn text-danger d-inline-block p-1"
                                                            data-no_bukti="{{ $h->no_bukti }}"
                                                            data-kode_barang="{{ $h->kode_barang }}" title="Hapus">
                                                            <i class="ti ti-trash fs-5"></i>
                                                        </a>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted border border-dashed rounded"
                        style="background-color: #fafbfc; border-color: #cbd5e1 !important;">
                        <i class="ti ti-history-off fs-1 d-block mb-2 text-secondary"></i>
                        <span class="fw-semibold">Belum ada history serah terima untuk BPPB ini.</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        $(".flatpickr-date").flatpickr({
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }, ]
        });

        $('.select2KodeSupplier')
            .wrap('<div class="position-relative"></div>')
            .select2({
                placeholder: 'Pilih Supplier',
                allowClear: true,
                dropdownParent: $('.select2KodeSupplier').parent()
            });

        $(".editJumlah").on("change", function() {
            let jumlah = $(this).val();
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            $.post("{{ route('bpbpembelian.updateSerahTerima') }}", {
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

        $(".editKeterangan").on("change", function() {
            let keterangan = $(this).val();
            let no_bukti = $(this).data("no_bukti");
            let kode_barang = $(this).data("kode_barang");

            $.post("{{ route('bpbpembelian.updateSerahTerima') }}", {
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

            $.post("{{ route('bpbpembelian.updateSerahTerima') }}", {
                _token: "{{ csrf_token() }}",
                no_bukti: no_bukti,
                diterima: 1
            }, function(resp) {
                Swal.fire({
                    icon: "success",
                    title: "Status serah terima diperbarui",
                    timer: 900,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            });
        });

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
                    $.post("{{ route('bpbpembelian.deleteSerahTerima') }}", {
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

                $(this).val(sisa);
                $(this).addClass("is-invalid");
                row.addClass("table-danger");
            } else {
                $(this).removeClass("is-invalid");
                row.removeClass("table-danger");
            }
        });
    });
</script>
@endpush
