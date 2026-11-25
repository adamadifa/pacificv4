<form action="#" method="POST" id="formGantiShiftV2" autocomplete="off" class="space-y-3">
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-start">
        <div class="sm:col-span-3">
            <label for="tanggal_gantishift" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Tanggal <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_gantishift" placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>
        </div>

        <div class="sm:col-span-5">
            <label for="nik_gantishift" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Karyawan <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <select name="nik" id="nik_gantishift"
                    class="select2NikGantiShift w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                    <option value="">Pilih Karyawan</option>
                    @foreach ($karyawan as $d)
                        <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="sm:col-span-4">
            <label for="kode_jadwal_gantishift" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Shift <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <select name="kode_jadwal" id="kode_jadwal_gantishift"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Pilih Shift</option>
                    <option value="JD002">Shift 1</option>
                    <option value="JD003">Shift 2</option>
                    <option value="JD004">Shift 3</option>
                </select>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2">
        <button type="submit" id="btnSimpanGantiShift"
            class="h-[36px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Simpan</span>
        </button>
    </div>

    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto max-h-[320px]">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider sticky top-0 z-10">
                    <tr>
                        <th class="py-2 px-3">TANGGAL</th>
                        <th class="py-2 px-3">NIK</th>
                        <th class="py-2 px-3">NAMA KARYAWAN</th>
                        <th class="py-2 px-3">SHIFT</th>
                        <th class="py-2 px-3 text-center w-14">#</th>
                    </tr>
                </thead>
                <tbody id="loadgantishift" class="divide-y divide-slate-100 dark:divide-slate-800">
                    <tr>
                        <td colspan="5" class="py-6 px-3 text-center text-xs text-slate-400">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>

<script>
    $(function() {
        const $form = $("#formGantiShiftV2");

        $form.find('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false,
            enable: [{
                from: "{{ $jadwalshift->dari }}",
                to: "{{ $jadwalshift->sampai }}",
            }]
        });

        const $select2Nik = $form.find('.select2NikGantiShift');
        if ($select2Nik.length && $.fn.select2) {
            $select2Nik.select2({
                placeholder: 'Pilih Karyawan',
                allowClear: true,
                width: '100%'
            });
        }

        function clearError($el) {
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            const $rel = $el.closest('.relative');
            $rel.find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            $rel.find('.prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $rel.siblings('.field-error-msg').remove();
        }

        function showError($el, msg) {
            clearError($el);
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            const $rel = $el.closest('.relative');
            $rel.find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            $rel.find('.prefix-icon').removeClass('text-slate-400').addClass('text-rose-500');
            $rel.after(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>${msg}</span>
                </p>
            `);
        }

        $form.find('input, select').on('input change select2:select', function() {
            clearError($(this));
        });

        function loadgantishift() {
            const kode_jadwalshift = "{{ Crypt::encrypt($kode_jadwalshift) }}";
            $("#loadgantishift").html("<tr><td colspan='5' class='py-6 px-3 text-center text-xs text-slate-400'>Memuat data...</td></tr>");
            $("#loadgantishift").load(`/jadwalshift/${kode_jadwalshift}/getgantishift`);
        }

        loadgantishift();

        $form.on('submit', function(e) {
            e.preventDefault();
            const $tanggal = $form.find("#tanggal_gantishift");
            const $nik = $form.find("#nik_gantishift");
            const $kode_jadwal = $form.find("#kode_jadwal_gantishift");
            const kode_jadwalshift = "{{ $kode_jadwalshift }}";
            let firstError = null;

            if (!$tanggal.val()) {
                showError($tanggal, 'Tanggal wajib diisi.');
                if (!firstError) firstError = $tanggal;
            }
            if (!$nik.val()) {
                showError($nik, 'Karyawan wajib dipilih.');
                if (!firstError) firstError = $nik;
            }
            if (!$kode_jadwal.val()) {
                showError($kode_jadwal, 'Shift wajib dipilih.');
                if (!firstError) firstError = $kode_jadwal;
            }

            if (firstError) {
                firstError.focus();
                return false;
            }

            const $btn = $form.find('#btnSimpanGantiShift');
            const origHtml = $btn.html();
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>Menyimpan...</span>`);

            $.ajax({
                type: "POST",
                url: "/jadwalshift/storegantishift",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: $tanggal.val(),
                    nik: $nik.val(),
                    kode_jadwal: $kode_jadwal.val(),
                    kode_jadwalshift: kode_jadwalshift
                },
                cache: false,
                success: function(respond) {
                    $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none').html(origHtml);
                    if (respond.success == true) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: respond.message,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                        loadgantishift();
                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: respond.message,
                            icon: "warning",
                            showConfirmButton: true,
                        });
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none').html(origHtml);
                    Swal.fire({ title: "Error!", text: "Terjadi kesalahan saat menyimpan data.", icon: "error" });
                }
            });
        });

        $(document).off('click.gantishift').on('click.gantishift', '.deletegs', function(e) {
            e.preventDefault();
            const kode_gs = $(this).attr("kode_gs");
            Swal.fire({
                title: "Hapus?",
                text: "Apakah Anda yakin ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e11d48",
                cancelButtonColor: "#64748b",
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "/jadwalshift/deletegantishift",
                        data: {
                            _token: "{{ csrf_token() }}",
                            kode_gs: kode_gs
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond.success == true) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: respond.message,
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                loadgantishift();
                            } else {
                                Swal.fire({
                                    title: "Oops!",
                                    text: respond.message,
                                    icon: "warning",
                                    showConfirmButton: true,
                                });
                            }
                        }
                    });
                }
            });
        });
    });
</script>
