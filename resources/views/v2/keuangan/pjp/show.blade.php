<div class="space-y-4">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-start">
        <!-- Kolom Kiri: Profil & Histori Pembayaran -->
        <div class="lg:col-span-5 space-y-3">
            <!-- Profil Pinjaman & Karyawan -->
            <div class="p-3 sm:p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40">
                <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
                    <i data-lucide="user" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Peminjam</h6>
                </div>
                <dl class="space-y-1.5 text-xs">
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500 dark:text-slate-400">No. Pinjaman</dt>
                        <dd class="font-mono-num font-bold text-slate-800 dark:text-slate-100 text-right">{{ $pjp->no_pinjaman }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500 dark:text-slate-400">Tanggal Pengajuan</dt>
                        <dd class="font-mono-num font-semibold text-slate-700 dark:text-slate-300 text-right">{{ DateToIndo($pjp->tanggal) }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500 dark:text-slate-400">NIK</dt>
                        <dd class="font-mono-num font-semibold text-slate-700 dark:text-slate-300 text-right">{{ $pjp->nik }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500 dark:text-slate-400">Nama Karyawan</dt>
                        <dd class="font-bold text-slate-800 dark:text-slate-100 text-right">{{ textUpperCase($pjp->nama_karyawan) }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500 dark:text-slate-400">Jabatan</dt>
                        <dd class="font-semibold text-slate-700 dark:text-slate-300 text-right">{{ $pjp->nama_jabatan }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500 dark:text-slate-400">Departemen</dt>
                        <dd class="font-semibold text-slate-700 dark:text-slate-300 text-right">{{ $pjp->nama_dept }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500 dark:text-slate-400">Kantor</dt>
                        <dd class="font-semibold text-slate-700 dark:text-slate-300 text-right">{{ $pjp->nama_cabang }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500 dark:text-slate-400">Masa Kerja</dt>
                        <dd class="font-semibold text-slate-700 dark:text-slate-300 text-right">
                            @php
                                $masakerja = hitungMasakerja($pjp->tanggal_masuk, $pjp->tanggal);
                            @endphp
                            {{ $masakerja['tahun'] }} Thn {{ $masakerja['bulan'] }} Bln
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Histori Pembayaran Card -->
            <div class="p-3 sm:p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
                <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="receipt" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                        <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Histori Pembayaran</h6>
                    </div>
                    @can('pembayaranpjp.create')
                        @if ($pjp->status == '1')
                            <button type="button" id="btnBayarPjpModal" data-nopinjam="{{ Crypt::encrypt($pjp->no_pinjaman) }}"
                                class="h-[28px] px-2.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-[11px] font-bold transition flex items-center gap-1 shadow-xs cursor-pointer">
                                <i data-lucide="plus" class="w-3 h-3"></i>
                                <span>Input Bayar</span>
                            </button>
                        @endif
                    @endcan
                </div>

                <div class="overflow-x-auto max-h-[220px]">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead class="bg-[#1e3a78] text-white">
                            <tr>
                                <th class="py-1.5 px-2.5 text-[10px] font-bold uppercase tracking-wider text-center">NO. BUKTI</th>
                                <th class="py-1.5 px-2.5 text-[10px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                                <th class="py-1.5 px-2.5 text-[10px] font-bold uppercase tracking-wider text-right">JUMLAH</th>
                                <th class="py-1.5 px-2.5 text-[10px] font-bold uppercase tracking-wider text-center w-8">#</th>
                            </tr>
                        </thead>
                        <tbody id="loadhistoribayar" class="divide-y divide-slate-100 dark:divide-slate-800 font-medium"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Rencana Cicilan & Finansial -->
        <div class="lg:col-span-7 space-y-3">
            <!-- Ringkasan Angka Finansial -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                <div class="p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 text-center">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pinjaman</span>
                    <span class="block text-xs font-black font-mono-num text-slate-800 dark:text-slate-100 mt-0.5">{{ formatAngka($pjp->jumlah_pinjaman) }}</span>
                </div>
                <div class="p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 text-center">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tenor / Cicilan</span>
                    <span class="block text-xs font-black font-mono-num text-slate-800 dark:text-slate-100 mt-0.5">{{ $pjp->angsuran }} Bulan</span>
                </div>
                <div class="p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 text-center">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Terbayar</span>
                    <span class="block text-xs font-black font-mono-num text-emerald-600 dark:text-emerald-400 mt-0.5">{{ formatAngka($pjp->totalpembayaran) }}</span>
                </div>
                <div class="p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 text-center">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sisa Tagihan</span>
                    <span class="block text-xs font-black font-mono-num text-rose-600 dark:text-rose-400 mt-0.5">{{ formatAngka($pjp->jumlah_pinjaman - $pjp->totalpembayaran) }}</span>
                </div>
            </div>

            <!-- Tabel Rencana Cicilan -->
            <div class="p-3 sm:p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
                <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <i data-lucide="calendar-range" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Jadwal Rencana Cicilan</h6>
                </div>

                <div class="overflow-x-auto max-h-[380px]">
                    <table class="w-full text-left text-xs whitespace-nowrap" id="tabelrencanacicilan">
                        <thead class="bg-[#1e3a78] text-white sticky top-0 z-10">
                            <tr>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-12">CICILAN</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">BULAN / TAHUN</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">TAGIHAN</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">BAYAR</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">SISA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const no_pinjaman = "{{ $pjp->no_pinjaman }}";

        // Load Rencana Cicilan
        function loadRencanacicilan() {
            $.ajax({
                type: "POST",
                url: "{{ route('pjp.getrencanacicilan') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    no_pinjaman: no_pinjaman
                },
                cache: false,
                success: function(respond) {
                    $("#tabelrencanacicilan tbody").html(respond);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        // Load Histori Bayar
        function loadHistoribayar() {
            $.ajax({
                type: "POST",
                url: "{{ route('pembayaranpjp.gethistoribayar') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    no_pinjaman: no_pinjaman
                },
                cache: false,
                success: function(respond) {
                    $("#loadhistoribayar").html(respond);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        loadRencanacicilan();
        loadHistoribayar();

        // Delete pembayaran pjp single
        $(document).on('click', '.btnDeletebayar', function(e) {
            e.preventDefault();
            const no_bukti = $(this).attr('no_bukti');
            Swal.fire({
                title: 'Hapus Pembayaran?',
                text: 'Data pembayaran ini akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#284c9a',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('pembayaranpjp.delete') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            no_bukti: no_bukti
                        },
                        cache: false,
                        success: function(respond) {
                            Swal.fire('Berhasil', 'Pembayaran berhasil dihapus', 'success');
                            loadHistoribayar();
                            loadRencanacicilan();
                        }
                    });
                }
            });
        });
    });
</script>
