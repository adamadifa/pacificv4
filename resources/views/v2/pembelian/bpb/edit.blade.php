<form action="{{ route('bpbpembelian.update', Crypt::encrypt($bpb->no_bpb)) }}" method="POST" id="formeditbpb" class="space-y-3.5 p-4 sm:p-5">
    @csrf
    @method('PUT')

    <input type="hidden" name="cektutuplaporan" id="cektutuplaporan">

    <!-- DATA HEADER BPB -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-start">
        <!-- No. BPB (Readonly) -->
        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">No. BPPB</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="no_bpb" id="no_bpb_edit" value="{{ $bpb->no_bpb }}" readonly
                    class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-hidden font-mono-num cursor-not-allowed">
            </div>
        </div>

        <!-- Tanggal Pengajuan -->
        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal Pengajuan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_edit" value="{{ $bpb->tanggal }}" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date-edit w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
            </div>
            <p id="error_tanggal_edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                <i data-lucide="alert-circle" class="w-3 h-3 text-rose-500 shrink-0"></i>
                <span>Tanggal harus diisi!</span>
            </p>
        </div>
    </div>

    <!-- SEKSI INPUT ITEM BARANG (ANTI-LAYOUT SHIFT) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 p-3 sm:p-3.5 space-y-2.5">
        <div class="flex items-center gap-1.5 pb-1.5 border-b border-slate-200/80 dark:border-slate-800">
            <i data-lucide="box" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Tambah Barang ke Daftar</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
            <!-- Pilih Barang (Select2) -->
            <div class="sm:col-span-5 relative select2-container-v2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Pilih Barang <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="package" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_barang" id="kode_barang_edit" class="select2KodebarangEdit w-full">
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->kode_barang }}" data-nama="{{ textUpperCase($b->nama_barang) }}" data-satuan="{{ $b->satuan }}">
                                {{ $b->kode_barang }} | {{ textUpperCase($b->nama_barang) }} ({{ $b->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <p id="error_kode_barang_edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3 h-3 text-rose-500 shrink-0"></i>
                    <span>Silakan pilih barang terlebih dahulu!</span>
                </p>
            </div>

            <!-- Jumlah -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Jumlah <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calculator" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="jumlah" id="jumlah_edit" placeholder="0"
                        class="number-separator w-full h-[36px] pl-8 pr-3 text-xs font-semibold text-right rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
                <p id="error_jumlah_edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3 h-3 text-rose-500 shrink-0"></i>
                    <span>Jumlah harus diisi!</span>
                </p>
            </div>

            <!-- Keterangan -->
            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Keterangan</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="keterangan" id="keterangan_edit" placeholder="Keterangan (opsional)"
                        class="w-full h-[36px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>
            </div>

            <!-- Tombol Tambah (Dengan Label Spacer Transparan Anti-Shift) -->
            <div class="sm:col-span-2">
                <label class="hidden sm:block text-xs font-bold text-transparent mb-1 select-none pointer-events-none">&nbsp;</label>
                <button type="button" id="btnTambahItemEdit"
                    class="w-full h-[36px] px-3 bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152852] text-white font-bold rounded-lg text-xs flex items-center justify-center gap-1.5 transition shadow-2xs cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Tambah</span>
                </button>
            </div>
        </div>
    </div>

    <!-- TABEL DAFTAR BARANG YANG DIAJUKAN -->
    <div class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
        <div class="overflow-x-auto max-h-[300px]">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap" id="tabledetailEdit">
                <thead>
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white sticky top-0 z-10">
                        <th colspan="5" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="list" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Barang yang Diajukan</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num" id="badgeItemCountEdit">
                                    Total: {{ count($detail) }} Item
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Baris 2: Kolom Header -->
                    <tr class="bg-[#1e3a78] text-white sticky top-[33px] z-10">
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40" style="width: 15%">KODE</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40" style="width: 40%">NAMA BARANG</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-right border-r border-[#284c9a]/40" style="width: 15%">JUMLAH</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40" style="width: 20%">KETERANGAN</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center" style="width: 10%">AKSI</th>
                    </tr>
                </thead>
                <tbody id="loaddetailEdit" class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($detail as $d)
                        <tr id="index_edit_{{ $d->kode_barang }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800">
                                <input type="hidden" name="kode_barang[]" value="{{ $d->kode_barang }}">
                                <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $d->kode_barang }}</span>
                            </td>
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 font-semibold text-slate-800 dark:text-slate-100">
                                {{ textUpperCase($d->nama_barang) }}
                            </td>
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 text-right font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                <input type="hidden" name="jml[]" value="{{ $d->jumlah }}">
                                {{ formatAngkaDesimal($d->jumlah) }}
                            </td>
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 text-xs">
                                <input type="hidden" name="ket[]" value="{{ $d->keterangan }}">
                                {{ $d->keterangan ?: '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-center">
                                <button type="button" data-kode="{{ $d->kode_barang }}"
                                    class="btnDeleteRowEdit w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/50 inline-flex items-center justify-center transition cursor-pointer"
                                    title="Hapus Item">
                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRowEdit">
                            <td colspan="5" class="py-6 text-center text-xs text-slate-400 dark:text-slate-500 italic">
                                Belum ada barang yang diajukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <p id="error_table_edit" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 pl-1 flex items-center gap-1">
        <i data-lucide="alert-circle" class="w-3 h-3 text-rose-500 shrink-0"></i>
        <span>Rincian barang BPPB tidak boleh kosong! Minimal harus ada 1 barang.</span>
    </p>

    <!-- CHECKBOX KONFIRMASI -->
    <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" id="agreementEdit" name="agreement" value="1"
                class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-[#284c9a] focus:ring-[#284c9a]/20 cursor-pointer">
            <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">
                Yakin data permohonan BPPB ini sudah benar dan akan disimpan?
            </span>
        </label>
    </div>

    <!-- TOMBOL AKSI MODAL (RIGHT-ALIGNED) -->
    <div id="saveButtonContainerEdit" class="hidden flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
        <button type="button" id="btnCancelEditModal"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSubmitEdit"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] active:bg-[#152852] text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formeditbpb");
        
        // Inisialisasi Flatpickr Tanggal
        $form.find(".flatpickr-date-edit").flatpickr({
            dateFormat: "Y-m-d",
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }]
        });

        // Inisialisasi Separator Angka
        if (typeof easyNumberSeparator !== 'undefined') {
            easyNumberSeparator({
                selector: '.number-separator',
                separator: '.',
                decimalSeparator: ','
            });
        }

        // Cek Tutup Laporan
        function cektutuplaporan(tanggal, jenis_laporan) {
            $.ajax({
                type: "POST",
                url: "/tutuplaporan/cektutuplaporan",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal,
                    jenis_laporan: jenis_laporan
                },
                cache: false,
                success: function(respond) {
                    $form.find("#cektutuplaporan").val(respond);
                }
            });
        }

        $form.find("#tanggal_edit").on("change", function() {
            cektutuplaporan($(this).val(), "gudanglogistik");
            $form.find("#error_tanggal_edit").addClass("hidden");
            $form.find("#tanggal_edit").removeClass("!border-rose-500 !ring-1 !ring-rose-500");
        });

        // Inisialisasi Select2 Barang Edit
        const $select2Barang = $form.find('.select2KodebarangEdit');
        if ($select2Barang.length && $.fn.select2) {
            $select2Barang.select2({
                placeholder: 'Pilih Barang',
                allowClear: true,
                dropdownCssClass: 'select2-dropdown-v2'
            });

            // Live cleanup select2
            $select2Barang.on('select2:select change', function() {
                $form.find("#error_kode_barang_edit").addClass("hidden");
                $form.find(".select2-selection").removeClass("!border-rose-500 !ring-1 !ring-rose-500");
            });
        }

        // Live cleanup jumlah
        $form.find("#jumlah_edit").on("input change", function() {
            $form.find("#error_jumlah_edit").addClass("hidden");
            $(this).removeClass("!border-rose-500 !ring-1 !ring-rose-500");
        });

        // Update item count badge
        function updateItemCount() {
            const count = $form.find('#loaddetailEdit tr[id^="index_edit_"]').length;
            $form.find('#badgeItemCountEdit').text(`Total: ${count} Item`);
            if (count > 0) {
                $form.find('#emptyRowEdit').remove();
                $form.find('#error_table_edit').addClass('hidden');
            }
        }

        // Tambah Produk
        $form.find("#btnTambahItemEdit").on("click", function(e) {
            e.preventDefault();
            const $selected = $form.find("#kode_barang_edit option:selected");
            const kode_barang = $selected.val();
            const nama_barang = $selected.data("nama") || "";
            const satuan = $selected.data("satuan") || "";
            const jumlah = $form.find("#jumlah_edit").val();
            const keterangan = $form.find("#keterangan_edit").val();

            let hasError = false;

            if (!kode_barang) {
                $form.find("#error_kode_barang_edit").removeClass("hidden");
                $form.find(".select2-selection").addClass("!border-rose-500 !ring-1 !ring-rose-500");
                hasError = true;
            }

            if (!jumlah || parseFloat(jumlah.replace(/\./g, '').replace(',', '.')) <= 0) {
                $form.find("#error_jumlah_edit").removeClass("hidden");
                $form.find("#jumlah_edit").addClass("!border-rose-500 !ring-1 !ring-rose-500");
                hasError = true;
            }

            if (hasError) return;

            // Cek duplikasi
            if ($form.find(`#index_edit_${kode_barang}`).length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Barang Sudah Ada!',
                    text: 'Barang ini sudah ada di daftar permintaan. Silakan edit atau hapus terlebih dahulu.',
                    confirmButtonColor: '#284c9a'
                });
                return;
            }

            const row = `
                <tr id="index_edit_${kode_barang}" class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                    <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800">
                        <input type="hidden" name="kode_barang[]" value="${kode_barang}">
                        <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100">${kode_barang}</span>
                    </td>
                    <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 font-semibold text-slate-800 dark:text-slate-100">
                        ${nama_barang} ${satuan ? '(' + satuan + ')' : ''}
                    </td>
                    <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 text-right font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                        <input type="hidden" name="jml[]" value="${jumlah}">
                        ${jumlah}
                    </td>
                    <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 text-xs">
                        <input type="hidden" name="ket[]" value="${keterangan}">
                        ${keterangan || '-'}
                    </td>
                    <td class="py-1.5 px-3 text-center">
                        <button type="button" data-kode="${kode_barang}"
                            class="btnDeleteRowEdit w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/50 inline-flex items-center justify-center transition cursor-pointer"
                            title="Hapus Item">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
            `;

            $form.find("#loaddetailEdit").prepend(row);
            $form.find("#kode_barang_edit").val('').trigger("change");
            $form.find("#jumlah_edit").val("");
            $form.find("#keterangan_edit").val("");
            
            updateItemCount();

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Hapus Row Item
        $form.on("click", ".btnDeleteRowEdit", function(e) {
            e.preventDefault();
            const kode = $(this).data("kode");
            $form.find(`#index_edit_${kode}`).remove();

            if ($form.find('#loaddetailEdit tr[id^="index_edit_"]').length === 0) {
                $form.find("#loaddetailEdit").html(`
                    <tr id="emptyRowEdit">
                        <td colspan="5" class="py-6 text-center text-xs text-slate-400 dark:text-slate-500 italic">
                            Belum ada barang yang diajukan.
                        </td>
                    </tr>
                `);
            }
            updateItemCount();
        });

        // Checkbox Agreement -> Toggle Tombol Simpan
        $form.find("#agreementEdit").on("change", function() {
            if (this.checked) {
                $form.find("#saveButtonContainerEdit").removeClass("hidden");
            } else {
                $form.find("#saveButtonContainerEdit").addClass("hidden");
            }
        });

        // Tombol Batal Modal Edit
        $form.find("#btnCancelEditModal").on("click", function() {
            if (typeof closeModal === 'function') {
                closeModal();
            } else {
                $("#modalEditBPB").addClass("hidden");
                $("body").removeClass("overflow-hidden");
            }
        });

        // Submit Form dengan Anti-Double Submit
        $form.on("submit", function(e) {
            const tanggal = $form.find("#tanggal_edit").val();
            const itemCount = $form.find('#loaddetailEdit tr[id^="index_edit_"]').length;
            const cektutup = $form.find("#cektutuplaporan").val();

            let hasError = false;

            if (!tanggal) {
                $form.find("#error_tanggal_edit").removeClass("hidden");
                $form.find("#tanggal_edit").addClass("!border-rose-500 !ring-1 !ring-rose-500");
                hasError = true;
            }

            if (itemCount === 0) {
                $form.find("#error_table_edit").removeClass("hidden");
                hasError = true;
            }

            if (cektutup > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Laporan Sudah Ditutup!',
                    text: 'Tidak dapat mengubah data karena periode laporan sudah ditutup.',
                    confirmButtonColor: '#284c9a'
                });
                return false;
            }

            if (hasError) {
                return false;
            }

            // Anti-double submit
            const $btn = $form.find("#btnSubmitEdit");
            $btn.prop("disabled", true).addClass("opacity-75 cursor-not-allowed pointer-events-none");
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);
            return true;
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
