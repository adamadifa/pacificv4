@php
    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 0, ',', '.');
    };
@endphp

<div class="space-y-4">

    <!-- HEADER INFO STRIP & DRIVER HELPER -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            
            <!-- Dokumen Information -->
            <div class="lg:col-span-7">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                    <div>
                        <span class="block text-[11px] font-semibold text-slate-400">No. DPB</span>
                        <span id="no_dpb_text" class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-sm">{{ $dpb->no_dpb }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-semibold text-slate-400">Tanggal Ambil</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100">{{ DateToIndo($dpb->tanggal_ambil) }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-semibold text-slate-400">Tanggal Kembali</span>
                        <div>
                            @if (!empty($dpb->tanggal_kembali))
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ DateToIndo($dpb->tanggal_kembali) }}</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Belum Kembali
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="block text-[11px] font-semibold text-slate-400">Salesman</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $dpb->nama_salesman }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-semibold text-slate-400">Cabang</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100">{{ textUpperCase($dpb->nama_cabang) }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-semibold text-slate-400">No. Kendaraan</span>
                        <span class="font-mono-num font-semibold text-slate-800 dark:text-slate-100">{{ $dpb->no_polisi }}</span>
                    </div>
                    <div class="sm:col-span-3">
                        <span class="block text-[11px] font-semibold text-slate-400">Tujuan</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ $dpb->tujuan }}</span>
                    </div>
                </div>
            </div>

            <!-- Driver & Helper Summary -->
            <div class="lg:col-span-5 border-t lg:border-t-0 lg:border-l border-slate-200/80 dark:border-slate-800 pl-0 lg:pl-4">
                <div class="rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden bg-white dark:bg-slate-900 shadow-2xs">
                    <div class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between text-[11px]">
                        <span class="font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300">Driver & Helper</span>
                        <span class="text-slate-500 dark:text-slate-400 font-medium">
                            Driver: <b>{{ $driver != null ? $driver->nama_driver_helper : '-' }}</b>
                        </span>
                    </div>
                    <div class="max-h-28 overflow-y-auto">
                        <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                @forelse ($driverhelper as $dh)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                                        <td class="py-1 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400 w-24">{{ $dh->kode_driver_helper }}</td>
                                        <td class="py-1 px-3 text-slate-700 dark:text-slate-200">{{ $dh->nama_driver_helper }}</td>
                                        <td class="py-1 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100 w-20">{{ formatAngkaDesimal3($dh->jumlah) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-2 px-3 text-center text-slate-400 text-[11px]">Tidak ada helper terdaftar</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- TABS NAVIGATION (V2 HORIZONTAL PILLS) -->
    <div class="flex items-center gap-1.5 border-b border-slate-200/80 dark:border-slate-800 pb-2">
        <button type="button" class="tab-dpb-btn active inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer bg-[#284c9a] text-white shadow-xs"
            data-target="#tab-pane-detaildpb">
            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
            <span>Detail Pengambilan & Pengembalian</span>
        </button>
        @can('mutasidpb.index')
            <button type="button" class="tab-dpb-btn inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400"
                data-target="#tab-pane-mutasidpb">
                <i data-lucide="repeat" class="w-3.5 h-3.5"></i>
                <span>Log Mutasi DPB</span>
            </button>
        @endcan
    </div>

    <!-- TAB 1: DETAIL PENGAMBILAN & PENGEMBALIAN -->
    <div id="tab-pane-detaildpb" class="tab-dpb-pane space-y-4">
        
        <!-- Tabel Detail DPB -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
            <div class="overflow-x-auto max-h-[380px]">
                <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                    <thead class="sticky top-0 z-10">
                        <!-- Row 1: Header Category Groupings -->
                        <tr class="bg-[#284c9a] text-white">
                            <th rowspan="2" class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white border-r border-blue-800/60">NO</th>
                            <th rowspan="2" class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white border-r border-blue-800/60">KODE</th>
                            <th rowspan="2" class="py-2 px-3 font-bold uppercase tracking-wider text-white border-r border-blue-800/60">NAMA PRODUK</th>
                            <th colspan="3" class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white border-r border-blue-800/60 bg-[#284c9a]">
                                PENGAMBILAN ({{ DateToIndo($dpb->tanggal_ambil) }})
                            </th>
                            <th colspan="3" class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white border-r border-blue-800/60 bg-emerald-800">
                                PENGEMBALIAN ({{ !empty($dpb->tanggal_kembali) ? DateToIndo($dpb->tanggal_kembali) : 'Waiting' }})
                            </th>
                            <th colspan="3" class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white bg-rose-900">
                                BARANG KELUAR
                            </th>
                        </tr>
                        <!-- Row 2: Sub-units (Dus, Pack, Pcs) -->
                        <tr class="bg-[#1e3a78] text-white text-[10.5px] border-none font-bold">
                            <th class="py-1 px-2 text-right w-18">DUS</th>
                            <th class="py-1 px-2 text-right w-18">PACK</th>
                            <th class="py-1 px-2 text-right w-18 border-r border-blue-800/60">PCS</th>
                            
                            <th class="py-1 px-2 text-right w-18 bg-emerald-900">DUS</th>
                            <th class="py-1 px-2 text-right w-18 bg-emerald-900">PACK</th>
                            <th class="py-1 px-2 text-right w-18 bg-emerald-900 border-r border-blue-800/60">PCS</th>
                            
                            <th class="py-1 px-2 text-right w-18 bg-rose-950">DUS</th>
                            <th class="py-1 px-2 text-right w-18 bg-rose-950">PACK</th>
                            <th class="py-1 px-2 text-right w-18 bg-rose-950">PCS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                        @forelse ($detail as $d)
                            @php
                                $jml_dus_ambil = floor($d->jml_ambil / $d->isi_pcs_dus);
                                $sisa_dus_ambil = $d->jml_ambil % $d->isi_pcs_dus;
                                if (!empty($d->isi_pack_dus)) {
                                    $jml_pack_ambil = floor($sisa_dus_ambil / $d->isi_pcs_pack);
                                    $sisa_pack_ambil = $sisa_dus_ambil % $d->isi_pcs_pack;
                                } else {
                                    $jml_pack_ambil = 0;
                                    $sisa_pack_ambil = $sisa_dus_ambil;
                                }
                                $jml_pcs_ambil = $sisa_pack_ambil;

                                $jml_dus_kembali = floor($d->jml_kembali / $d->isi_pcs_dus);
                                $sisa_dus_kembali = $d->jml_kembali % $d->isi_pcs_dus;
                                if (!empty($d->isi_pack_dus)) {
                                    $jml_pack_kembali = floor($sisa_dus_kembali / $d->isi_pcs_pack);
                                    $sisa_pack_kembali = $sisa_dus_kembali % $d->isi_pcs_pack;
                                } else {
                                    $jml_pack_kembali = 0;
                                    $sisa_pack_kembali = $sisa_dus_kembali;
                                }
                                $jml_pcs_kembali = $sisa_pack_kembali;

                                $jml_dus_keluar = floor($d->jml_penjualan / $d->isi_pcs_dus);
                                $sisa_dus_keluar = $d->jml_penjualan % $d->isi_pcs_dus;
                                if (!empty($d->isi_pack_dus)) {
                                    $jml_pack_keluar = floor($sisa_dus_keluar / $d->isi_pcs_pack);
                                    $sisa_pack_keluar = $sisa_dus_keluar % $d->isi_pcs_pack;
                                } else {
                                    $jml_pack_keluar = 0;
                                    $sisa_pack_keluar = $sisa_dus_keluar;
                                }
                                $jml_pcs_keluar = $sisa_pack_keluar;
                            @endphp
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                                <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                                <td class="py-1.5 px-3 text-center font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</td>
                                <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">{{ $d->nama_produk }}</td>
                                
                                <!-- Pengambilan -->
                                <td class="py-1.5 px-2 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $numFormat($jml_dus_ambil) }}</td>
                                <td class="py-1.5 px-2 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $numFormat($jml_pack_ambil) }}</td>
                                <td class="py-1.5 px-2 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100 border-r border-slate-200 dark:border-slate-800">{{ $numFormat($jml_pcs_ambil) }}</td>
                                
                                <!-- Pengembalian -->
                                <td class="py-1.5 px-2 text-right font-mono-num font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50/20 dark:bg-emerald-950/10">{{ $numFormat($jml_dus_kembali) }}</td>
                                <td class="py-1.5 px-2 text-right font-mono-num font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50/20 dark:bg-emerald-950/10">{{ $numFormat($jml_pack_kembali) }}</td>
                                <td class="py-1.5 px-2 text-right font-mono-num font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50/20 dark:bg-emerald-950/10 border-r border-slate-200 dark:border-slate-800">{{ $numFormat($jml_pcs_kembali) }}</td>
                                
                                <!-- Barang Keluar -->
                                <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">{{ $numFormat($jml_dus_keluar) }}</td>
                                <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">{{ $numFormat($jml_pack_keluar) }}</td>
                                <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">{{ $numFormat($jml_pcs_keluar) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="py-6 text-center text-slate-400">Tidak ada detail produk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Matriks Detail Mutasi DPB (Retur, Hutang Kirim, Penjualan, dll.) -->
        <div id="loaddetailmutasidpb" class="mt-4">
            <div class="flex items-center justify-center py-8 gap-2 text-slate-400">
                <div class="w-6 h-6 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                <span class="text-xs font-medium">Memuat matriks rincian mutasi...</span>
            </div>
        </div>

    </div>

    @can('mutasidpb.index')
        <!-- TAB 2: LOG MUTASI DPB -->
        <div id="tab-pane-mutasidpb" class="tab-dpb-pane hidden space-y-3">
            
            <!-- Action & Filter Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                @can('mutasidpb.create')
                    <button type="button" id="btnCreatemutasidpb"
                        class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah Data Mutasi</span>
                    </button>
                @endcan

                <!-- Filter Jenis Mutasi -->
                <div class="w-full sm:w-64 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="jenis_mutasi_search" id="jenis_mutasi_search"
                        class="w-full h-[34px] pl-8 pr-7 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Semua Jenis Mutasi</option>
                        @foreach ($jenis_mutasi as $jm)
                            <option value="{{ $jm->kode_jenis_mutasi }}">{{ strtoupper($jm->jenis_mutasi) }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- Tabel Log Mutasi DPB -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-[#284c9a] text-white">
                                <th colspan="4" class="px-4 py-2 bg-[#284c9a] text-white border-0">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="repeat" class="w-3.5 h-3.5 text-blue-200"></i>
                                        <span class="text-xs font-bold uppercase tracking-wider text-white">Daftar Mutasi DPB Terdaftar</span>
                                    </div>
                                </th>
                            </tr>
                            <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-44">NO. MUTASI</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-36">TANGGAL</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">JENIS MUTASI</th>
                                <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-20">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="loadmutasidpb" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-4 h-4 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                                        <span>Memuat riwayat mutasi...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @endcan

</div>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const no_dpb = $("#no_dpb_text").text().trim();
        const no_dpb_enc = "{{ Crypt::encrypt($dpb->no_dpb) }}";

        // Tab Switcher
        $('.tab-dpb-btn').on('click', function(e) {
            e.preventDefault();
            $('.tab-dpb-btn').removeClass('active bg-[#284c9a] text-white shadow-xs')
                .addClass('bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300');
            $(this).addClass('active bg-[#284c9a] text-white shadow-xs')
                .removeClass('bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300');

            const target = $(this).data('target');
            $('.tab-dpb-pane').addClass('hidden');
            $(target).removeClass('hidden');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Load Detail Mutasi Matrix Table
        function getdetailmutasidpb() {
            $("#loaddetailmutasidpb").html(`
                <div class="flex items-center justify-center py-8 gap-2 text-slate-400">
                    <div class="w-6 h-6 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs font-medium">Memuat matriks rincian mutasi...</span>
                </div>
            `);
            $("#loaddetailmutasidpb").load(`/dpb/${no_dpb_enc}/getdetailmutasidpb`, function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        }

        // Load Mutasi DPB List Table
        function getmutasidpb() {
            const jenis_mutasi = $("#jenis_mutasi_search").val();
            const jm = jenis_mutasi ? jenis_mutasi : null;
            $("#loadmutasidpb").html(`
                <tr>
                    <td colspan="4" class="py-6 text-center text-slate-400">
                        <div class="flex items-center justify-center gap-2">
                            <div class="w-4 h-4 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
                            <span>Memuat riwayat mutasi...</span>
                        </div>
                    </td>
                </tr>
            `);
            $("#loadmutasidpb").load(`/mutasidpb/${no_dpb}/${jm}/getmutasidpb`, function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        }

        // Trigger loaders
        getdetailmutasidpb();
        getmutasidpb();

        $("#jenis_mutasi_search").on('change', function() {
            getmutasidpb();
        });

        // Mutasi DPB Action Triggers
        $("#btnCreatemutasidpb").on('click', function(e) {
            e.preventDefault();
            if (typeof window.openModalMutasi === 'function') {
                window.openModalMutasi(
                    'Tambah Mutasi DPB - ' + no_dpb,
                    'Input transaksi retur, penjualan, promo, atau ganti barang',
                    '/mutasidpb/create'
                );
            }
        });

        $(document).on('click', '.btnEditmutasidpb', function(e) {
            e.preventDefault();
            const no_mutasi = $(this).attr('no_mutasi');
            if (typeof window.openModalMutasi === 'function') {
                window.openModalMutasi(
                    'Edit Mutasi DPB',
                    'Perbarui kuantitas produk mutasi DPB',
                    `/mutasidpb/${no_mutasi}/edit`
                );
            }
        });

        $(document).on('click', '.btnShowmutasidpb', function(e) {
            e.preventDefault();
            const no_mutasi = $(this).attr('no_mutasi');
            if (typeof window.openModalMutasi === 'function') {
                window.openModalMutasi(
                    'Detail Mutasi DPB',
                    'Rincian kuantitas produk yang tercatat pada mutasi ini',
                    `/mutasidpb/${no_mutasi}/show`
                );
            }
        });

        $(document).on('click', '.btnDeletemutasidpb', function(e) {
            e.preventDefault();
            const no_mutasi = $(this).attr('no_mutasi');
            Swal.fire({
                title: 'Hapus Mutasi DPB?',
                text: 'Data mutasi ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Data!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '/mutasidpb/delete',
                        data: {
                            _token: "{{ csrf_token() }}",
                            no_mutasi: no_mutasi
                        },
                        success: function(respond) {
                            if (respond.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: respond.message,
                                    icon: 'success',
                                    confirmButtonColor: '#284c9a'
                                });
                                getmutasidpb();
                                getdetailmutasidpb();
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: respond.message,
                                    icon: 'error',
                                    confirmButtonColor: '#284c9a'
                                });
                            }
                        }
                    });
                }
            });
        });

        // Store Mutasi DPB via AJAX
        $(document).off('submit', '#formMutasiDPB').on('submit', '#formMutasiDPB', function(e) {
            e.preventDefault();
            const $formMutasi = $(this);
            const $btnSubmitMutasi = $formMutasi.find("#submitMutasiDpb");
            const tanggal = $formMutasi.find("#tanggal").val();
            const jenis_mutasi = $formMutasi.find("#jenis_mutasi").val();

            if (!tanggal) {
                Swal.fire({ title: 'Perhatian', text: 'Tanggal mutasi wajib diisi!', icon: 'warning', confirmButtonColor: '#284c9a' });
                return false;
            }
            if (!jenis_mutasi) {
                Swal.fire({ title: 'Perhatian', text: 'Jenis mutasi wajib dipilih!', icon: 'warning', confirmButtonColor: '#284c9a' });
                return false;
            }

            let kode_produk = [];
            let jml_dus = [];
            let jml_pack = [];
            let jml_pcs = [];
            let isi_pcs_dus = [];
            let isi_pcs_pack = [];

            $formMutasi.find(".kode_produk").each(function() { kode_produk.push($(this).val()); });
            $formMutasi.find(".jml_dus").each(function() { jml_dus.push($(this).val()); });
            $formMutasi.find(".jml_pack").each(function() { jml_pack.push($(this).val()); });
            $formMutasi.find(".jml_pcs").each(function() { jml_pcs.push($(this).val()); });
            $formMutasi.find(".isi_pcs_dus").each(function() { isi_pcs_dus.push($(this).val()); });
            $formMutasi.find(".isi_pcs_pack").each(function() { isi_pcs_pack.push($(this).val()); });

            $btnSubmitMutasi.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');

            $.ajax({
                type: "POST",
                url: "{{ route('mutasidpb.store') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    no_dpb: no_dpb,
                    tanggal: tanggal,
                    jenis_mutasi: jenis_mutasi,
                    kode_produk: kode_produk,
                    jml_dus: jml_dus,
                    jml_pack: jml_pack,
                    jml_pcs: jml_pcs,
                    isi_pcs_dus: isi_pcs_dus,
                    isi_pcs_pack: isi_pcs_pack
                },
                success: function(respond) {
                    if (respond.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: respond.message,
                            icon: 'success',
                            confirmButtonColor: '#284c9a'
                        });
                        if (typeof window.closeModalMutasi === 'function') {
                            window.closeModalMutasi();
                        }
                        getmutasidpb();
                        getdetailmutasidpb();
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: respond.message,
                            icon: 'error',
                            confirmButtonColor: '#284c9a'
                        });
                        $btnSubmitMutasi.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                    }
                },
                error: function() {
                    Swal.fire({ title: 'Error', text: 'Terjadi kesalahan sistem.', icon: 'error', confirmButtonColor: '#284c9a' });
                    $btnSubmitMutasi.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                }
            });
        });

        // Update Mutasi DPB via AJAX
        $(document).off('submit', '#formupdateMutasiDPB').on('submit', '#formupdateMutasiDPB', function(e) {
            e.preventDefault();
            const $formMutasi = $(this);
            const $btnSubmitMutasi = $formMutasi.find("#submitMutasiDpb");
            const no_mutasi = $formMutasi.find("#no_mutasi").val();
            const tanggal = $formMutasi.find("#tanggal").val();
            const jenis_mutasi = $formMutasi.find("#jenis_mutasi").val();

            let kode_produk = [];
            let jml_dus = [];
            let jml_pack = [];
            let jml_pcs = [];
            let isi_pcs_dus = [];
            let isi_pcs_pack = [];

            $formMutasi.find(".kode_produk").each(function() { kode_produk.push($(this).val()); });
            $formMutasi.find(".jml_dus").each(function() { jml_dus.push($(this).val()); });
            $formMutasi.find(".jml_pack").each(function() { jml_pack.push($(this).val()); });
            $formMutasi.find(".jml_pcs").each(function() { jml_pcs.push($(this).val()); });
            $formMutasi.find(".isi_pcs_dus").each(function() { isi_pcs_dus.push($(this).val()); });
            $formMutasi.find(".isi_pcs_pack").each(function() { isi_pcs_pack.push($(this).val()); });

            $btnSubmitMutasi.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');

            $.ajax({
                type: "POST",
                url: "{{ route('mutasidpb.update') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    no_mutasi: no_mutasi,
                    no_dpb: no_dpb,
                    tanggal: tanggal,
                    jenis_mutasi: jenis_mutasi,
                    kode_produk: kode_produk,
                    jml_dus: jml_dus,
                    jml_pack: jml_pack,
                    jml_pcs: jml_pcs,
                    isi_pcs_dus: isi_pcs_dus,
                    isi_pcs_pack: isi_pcs_pack
                },
                success: function(respond) {
                    if (respond.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: respond.message,
                            icon: 'success',
                            confirmButtonColor: '#284c9a'
                        });
                        if (typeof window.closeModalMutasi === 'function') {
                            window.closeModalMutasi();
                        }
                        getmutasidpb();
                        getdetailmutasidpb();
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: respond.message,
                            icon: 'error',
                            confirmButtonColor: '#284c9a'
                        });
                        $btnSubmitMutasi.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                    }
                },
                error: function() {
                    Swal.fire({ title: 'Error', text: 'Terjadi kesalahan sistem.', icon: 'error', confirmButtonColor: '#284c9a' });
                    $btnSubmitMutasi.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                }
            });
        });

    });
</script>
