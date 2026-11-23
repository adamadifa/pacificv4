@php
    $canHarga = auth()->user()->can('pembelian.harga');
    $total_pembelian = 0;
    foreach ($detail as $d) {
        $subtotal = $d->jumlah * $d->harga;
        $total = $subtotal + $d->penyesuaian;
        $total_pembelian += $total;
    }
@endphp

<form action="{{ route('pembelian.storeapprovegdl', Crypt::encrypt($pembelian->no_bukti)) }}" id="formApprovegdl" method="POST" class="space-y-4">
    @csrf

    <!-- 1. DOKUMEN RINGKASAN STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 shadow-2xs">
        <div class="flex items-center justify-between pb-2.5 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <i data-lucide="shield-check" class="w-4 h-4 text-indigo-600 dark:text-indigo-400"></i>
                <h5 class="text-xs font-bold text-slate-800 dark:text-slate-100">Persetujuan Penerimaan Gudang Logistik (GDL)</h5>
            </div>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-900/60">
                {{ $pembelian->no_bukti }}
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Tanggal Bukti</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block font-mono-num">{{ DateToIndo($pembelian->tanggal) }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Nama Supplier</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block truncate">{{ $pembelian->nama_supplier }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Asal Pengajuan</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block">
                    {{ array_key_exists($pembelian->kode_asal_pengajuan, $asal_pengajuan) ? $asal_pengajuan[$pembelian->kode_asal_pengajuan] : ($pembelian->kode_asal_pengajuan ?? '-') }}
                </span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Status PPN</span>
                <span class="font-bold mt-0.5 block {{ $pembelian->ppn == '1' ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500' }}">
                    {{ $pembelian->ppn == '1' ? 'PPN' : 'Non PPN' }}
                </span>
            </div>
        </div>
    </div>

    <!-- 2. TABEL RINCIAN BARANG -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="{{ $canHarga ? 8 : 4 }}" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="package" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Barang yang Akan Diterima</span>
                                </div>
                                <span class="text-[10.5px] text-white/80 font-mono-num font-semibold">
                                    Total: {{ count($detail) }} Item
                                </span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[180px]">NAMA BARANG</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[160px]">KETERANGAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-20">QTY</th>
                        @if ($canHarga)
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[100px]">HARGA</th>
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[100px]">SUBTOTAL</th>
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[90px]">PENY</th>
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[110px]">TOTAL</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @foreach ($detail as $d)
                        @php
                            $subtotal = $d->jumlah * $d->harga;
                            $total = $subtotal + $d->penyesuaian;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                                {{ $d->kode_barang }}
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-slate-100">
                                {{ textCamelCase($d->nama_barang) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                {{ textCamelCase($d->keterangan) }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-800 dark:text-slate-200">
                                {{ formatAngkaDesimal($d->jumlah) }}
                            </td>
                            @if ($canHarga)
                                <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                    {{ formatAngkaDesimal($d->harga) }}
                                </td>
                                <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                    {{ formatAngkaDesimal($subtotal) }}
                                </td>
                                <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                    {{ formatAngkaDesimal($d->penyesuaian) }}
                                </td>
                                <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                                    {{ formatAngkaDesimal($total) }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. FORM INPUT TANGGAL PENERIMAAN -->
    <div class="space-y-3 pt-1">
        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Penerimaan Barang <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_approvegdl" placeholder="Pilih Tanggal Penerimaan" readonly
                    class="flatpickr-date w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
        </div>

        <button type="submit" id="btnSubmitApprovegdl"
            class="w-full h-[36px] px-4 rounded-lg bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            <span>Konfirmasi Terima Barang GDL</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Initialize flatpickr on loaded content
        if (typeof flatpickr !== 'undefined') {
            $(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: new Date()
            });
        }

        // Form Submit Handler with Loading Spinner
        $("#formApprovegdl").on("submit", function(e) {
            const $form = $(this);
            const $tgl = $form.find("#tanggal_approvegdl");
            const val = $tgl.val().trim();

            $form.find(".field-error-msg").remove();
            $tgl.removeClass("border-rose-500 ring-1 ring-rose-500");

            if (!val) {
                e.preventDefault();
                $tgl.addClass("border-rose-500 ring-1 ring-rose-500");
                $tgl.closest("div.relative").after(`
                    <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                        <span>Tanggal penerimaan wajib diisi</span>
                    </p>
                `);
                if (typeof lucide !== 'undefined') lucide.createIcons();
                $tgl.focus();
                return false;
            }

            const $btn = $form.find("#btnSubmitApprovegdl");
            $btn.prop("disabled", true).addClass("opacity-75 cursor-not-allowed pointer-events-none");
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Memproses Penerimaan...</span>
            `);
        });

        $("#tanggal_approvegdl").on("input change", function() {
            $(this).removeClass("border-rose-500 ring-1 ring-rose-500");
            $(this).closest(".space-y-3").find(".field-error-msg").remove();
        });
    });
</script>
