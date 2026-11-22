<form action="{{ route('bpbj.store') }}" id="formcreateBpbj" method="POST" class="space-y-3">
    @csrf
    <input type="hidden" id="cektutuplaporan">
    <input type="hidden" id="cekdetailtemp">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
        <!-- No. BPBJ -->
        <div class="field-group">
            <label for="no_mutasi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                No. BPBJ <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                </div>
                <input type="text" name="no_mutasi" id="no_mutasi"
                    class="w-full h-[36px] pl-9 pr-3 text-xs font-bold font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-brand-600 dark:text-brand-400 focus:outline-hidden transition"
                    placeholder="[Auto-Generated]" readonly>
            </div>
        </div>

        <!-- Tanggal BPBJ -->
        <div class="field-group">
            <label for="tanggal_mutasi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal BPBJ <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal_mutasi" id="tanggal_mutasi"
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                    placeholder="Pilih Tanggal Mutasi" readonly>
            </div>
        </div>
    </div>

    <!-- ROW INPUT PRODUK, SHIFT & JUMLAH -->
    <div class="p-3 rounded-lg border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40 space-y-2.5">
        <div class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-200">
            <i data-lucide="box" class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400"></i>
            <span>Input Rincian Produk Hasil Produksi</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
            <!-- Pilih Produk -->
            <div class="sm:col-span-6 field-group">
                <label for="kode_produk" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                    Produk <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="package" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_produk" id="kode_produk" class="select2Kodeproduk w-full">
                        <option value="">Pilih Produk</option>
                        @foreach ($produk as $d)
                            <option value="{{ $d->kode_produk }}">{{ $d->kode_produk }} - {{ $d->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Shift -->
            <div class="sm:col-span-2 field-group">
                <label for="shift" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                    Shift <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="shift" id="shift"
                        class="w-full h-[36px] pl-8 pr-6 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Shift</option>
                        <option value="1">Shift 1</option>
                        <option value="2">Shift 2</option>
                        <option value="3">Shift 3</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                    </div>
                </div>
            </div>

            <!-- Jumlah -->
            <div class="sm:col-span-3 field-group">
                <label for="jumlah" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
                    Jumlah (Dus) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="jumlah" id="jumlah"
                        class="money w-full h-[36px] pl-8 pr-2.5 text-xs font-bold text-right font-mono-num rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition"
                        placeholder="0">
                </div>
            </div>

            <!-- Tombol Tambah -->
            <div class="sm:col-span-1 self-start">
                <label class="block text-[11px] font-bold invisible mb-1 select-none pointer-events-none">Aksi</label>
                <button type="button" id="tambahproduk"
                    class="w-full h-[36px] inline-flex items-center justify-center rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white transition shadow-xs cursor-pointer"
                    title="Tambah Rincian Produk">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- TABEL TEMPORARY DETAIL BPBJ -->
    <div class="field-group rounded-lg border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto scrollbar-thin max-h-[260px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="5" class="px-4 py-3 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center gap-2">
                                <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Hasil Produksi (BPBJ)</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">Kode Produk</th>
                        <th class="py-2.5 px-4 font-bold uppercase tracking-wider text-white/90 border-none">Nama Produk</th>
                        <th class="py-2.5 px-4 text-center font-bold uppercase tracking-wider text-white/90 border-none w-28">Shift</th>
                        <th class="py-2.5 px-4 text-right font-bold uppercase tracking-wider text-white/90 border-none w-36">Jumlah</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody id="loaddetailbpbjtemp" class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-400">
                            <span class="text-xs font-semibold">Pilih produk dan masukkan shift & jumlah untuk menambahkan</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- KONFIRMASI CHECKBOX & SUBMIT BUTTON -->
    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 space-y-2.5">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="aggrement" id="defaultCheck3" class="agreement w-4 h-4 rounded text-brand-600 border-slate-300 dark:border-slate-700 focus:ring-brand-500/20">
            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Yakin data BPBJ di atas sudah benar dan akan disimpan?</span>
        </label>

        <div id="saveButton" class="hidden flex items-center justify-end gap-2 pt-1">
            <button type="button" class="btn-cancel-modal h-[34px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer" data-bs-dismiss="modal">
                Batal
            </button>
            <button type="submit" id="btnSimpan"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span>Simpan BPBJ</span>
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formcreateBpbj");
        const $btnSimpan = $form.find("#btnSimpan");

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Standard Error State Helpers
        function setFieldError($input, message) {
            const $container = $input.closest('.field-group');
            $container.addClass('has-error');
            $input.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid')
                  .removeClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').addClass('text-rose-500').removeClass('text-slate-400');

            if ($input.hasClass('select2-hidden-accessible') || $input.next().hasClass('select2')) {
                $input.addClass('is-invalid');
                const $s2 = $input.next('.select2');
                $s2.addClass('is-invalid');
                $s2.find('.select2-selection').addClass('is-invalid border-rose-500 !border-rose-500');
                $container.find('.select2-selection').addClass('is-invalid border-rose-500 !border-rose-500');
            }

            $container.find('.field-error-msg').remove();
            $container.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3 h-3 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($input) {
            const $container = $input.closest('.field-group');
            $container.removeClass('has-error');
            $input.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20 is-invalid')
                  .addClass('border-slate-200 dark:border-slate-700');
            $container.find('.input-prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');

            if ($input.hasClass('select2-hidden-accessible') || $input.next().hasClass('select2')) {
                $input.removeClass('is-invalid');
                const $s2 = $input.next('.select2');
                $s2.removeClass('is-invalid');
                $s2.find('.select2-selection').removeClass('is-invalid border-rose-500 !border-rose-500');
                $container.find('.select2-selection').removeClass('is-invalid border-rose-500 !border-rose-500');
            }

            $container.find('.field-error-msg').remove();
        }

        // Live Cleanup on Type / Change
        $form.find('input, select').on('input change select2:select', function() {
            clearFieldError($(this));
        });

        // Flatpickr
        $form.find("#tanggal_mutasi").flatpickr({
            dateFormat: "Y-m-d",
            enable: [{
                from: "{{ $start_periode }}",
                to: "{{ $end_periode }}"
            }],
            onChange: function() {
                clearFieldError($form.find("#tanggal_mutasi"));
            }
        });

        // MaskMoney
        if ($.fn.maskMoney) {
            $form.find(".money").maskMoney({
                thousands: '.',
                decimal: ',',
                precision: 0
            });
        }

        // Select2
        $form.find('.select2Kodeproduk').select2({
            placeholder: 'Pilih Produk',
            width: '100%'
        }).on('change select2:select', function() {
            clearFieldError($(this));
        });

        function cekdetailtemp() {
            var kode_produk = $form.find("#kode_produk").val();
            $.ajax({
                type: 'POST',
                url: '/bpbj/cekdetailtemp',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_produk: kode_produk
                },
                cache: false,
                success: function(respond) {
                    $form.find("#cekdetailtemp").val(respond);
                }
            });
        }

        function loaddetailtemp() {
            const kode_produk = $form.find("#kode_produk").val();
            if (!kode_produk) {
                $form.find("#loaddetailbpbjtemp").html(`
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-400">
                            <span class="text-xs font-semibold">Pilih produk dan masukkan shift & jumlah untuk menambahkan</span>
                        </td>
                    </tr>
                `);
                return;
            }
            $form.find("#loaddetailbpbjtemp").load("/bpbj/" + kode_produk + "/getdetailtemp", function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
            cekdetailtemp();
        }

        function generatenobpbj() {
            var tanggal_mutasi = $form.find("#tanggal_mutasi").val();
            var kode_produk = $form.find("#kode_produk").val();
            if (tanggal_mutasi && kode_produk) {
                $.ajax({
                    type: 'POST',
                    url: '/bpbj/generatenobpbj',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal_mutasi: tanggal_mutasi,
                        kode_produk: kode_produk
                    },
                    cache: false,
                    success: function(respond) {
                        $form.find("#no_mutasi").val(respond);
                        clearFieldError($form.find("#no_mutasi"));
                    }
                });
            }
        }

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

        $form.find("#kode_produk").on("change", function() {
            loaddetailtemp();
            generatenobpbj();
        });

        $form.find("#tanggal_mutasi").on("change", function() {
            generatenobpbj();
            cektutuplaporan($(this).val(), "produksi");
        });

        // Tambah Produk ke Temporary Table
        $form.find("#tambahproduk").on("click", function(e) {
            e.preventDefault();
            const $kodeProduk = $form.find("#kode_produk");
            const $shift = $form.find("#shift");
            const $jumlah = $form.find("#jumlah");

            let hasAddError = false;
            let $firstAddError = null;

            if (!$kodeProduk.val()) {
                setFieldError($kodeProduk, "Pilih produk terlebih dahulu");
                hasAddError = true;
                $firstAddError = $kodeProduk;
            }
            if (!$shift.val()) {
                setFieldError($shift, "Pilih shift kerja");
                if (!hasAddError) {
                    hasAddError = true;
                    $firstAddError = $shift;
                }
            }
            if (!$jumlah.val() || $jumlah.val() === "0") {
                setFieldError($jumlah, "Jumlah wajib diisi angka > 0");
                if (!hasAddError) {
                    hasAddError = true;
                    $firstAddError = $jumlah;
                }
            }

            if (hasAddError) {
                if ($firstAddError.hasClass('select2-hidden-accessible')) {
                    $firstAddError.select2('open');
                } else {
                    $firstAddError.focus();
                }
                return;
            }

            const $btnTambah = $(this);
            $btnTambah.prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "{{ route('bpbj.storedetailtemp') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_produk: $kodeProduk.val(),
                    shift: $shift.val(),
                    jumlah: $jumlah.val()
                },
                cache: false,
                success: function(respond) {
                    $jumlah.val('');
                    $shift.val('');
                    loaddetailtemp();
                    $btnTambah.prop('disabled', false);
                },
                error: function(xhr) {
                    $btnTambah.prop('disabled', false);
                    Swal.fire("Error", xhr.responseJSON?.message || "Gagal menambahkan rincian produk", "error");
                }
            });
        });

        $form.find('.agreement').on("change", function() {
            if (this.checked) {
                $form.find("#saveButton").removeClass("hidden");
            } else {
                $form.find("#saveButton").addClass("hidden");
            }
        });

        // Submit Form BPBJ
        $form.on("submit", function(e) {
            let hasError = false;
            let $firstErrorField = null;

            function validateField($field, condition, message) {
                if (condition) {
                    setFieldError($field, message);
                    if (!hasError) {
                        hasError = true;
                        $firstErrorField = $field;
                    }
                } else {
                    clearFieldError($field);
                }
            }

            const $noMutasi = $form.find("#no_mutasi");
            const $tglMutasi = $form.find("#tanggal_mutasi");
            const $kdProduk = $form.find("#kode_produk");
            const cektutuplaporan = $form.find("#cektutuplaporan").val();
            const cekdetailtemp = $form.find("#cekdetailtemp").val();

            validateField($tglMutasi, !$tglMutasi.val(), "Tanggal BPBJ wajib dipilih");
            validateField($kdProduk, !$kdProduk.val(), "Produk wajib dipilih");
            validateField($noMutasi, !$noMutasi.val(), "No. BPBJ belum terbentuk. Pilih tanggal & produk");

            if (hasError) {
                e.preventDefault();
                if ($firstErrorField) {
                    if ($firstErrorField.hasClass('select2-hidden-accessible')) {
                        $firstErrorField.select2('open');
                    } else {
                        $firstErrorField.focus();
                    }
                }
                return false;
            }

            if (cektutuplaporan === '1') {
                e.preventDefault();
                Swal.fire("Oops!", "Laporan untuk periode tanggal ini sudah ditutup!", "warning");
                return false;
            }

            if (cekdetailtemp === '0' || cekdetailtemp === '') {
                e.preventDefault();
                Swal.fire("Oops!", "Rincian produk masih kosong! Tambahkan minimal satu rincian.", "warning");
                return false;
            }

            // Prevent Double Submit
            $btnSimpan.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btnSimpan.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan BPBJ...</span>
            `);
            return true;
        });

        // Delete temporary item
        $('body').on('click', '.delete-temp-bpbj', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            Swal.fire({
                title: 'Hapus Rincian Ini?',
                text: "Item yang dihapus tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '/bpbj/deletetemp',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        cache: false,
                        success: function(respond) {
                            loaddetailtemp();
                        },
                        error: function(xhr) {
                            Swal.fire("Error", xhr.responseJSON?.message || "Gagal menghapus item", "error");
                        }
                    });
                }
            });
        });
    });
</script>
