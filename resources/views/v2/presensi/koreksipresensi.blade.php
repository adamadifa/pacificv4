<style>
    .presensi-koreksi-grid {
        display: grid;
        grid-template-columns: 240px minmax(0, 1fr);
        gap: 16px;
        align-items: start;
    }

    .presensi-koreksi-jam-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    @media (max-width: 767px) {
        .presensi-koreksi-grid,
        .presensi-koreksi-jam-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@if (!empty($presensi))
    <form action="{{ route('presensi.updatepresensi', Crypt::encrypt($presensi->id)) }}" method="POST" id="formKoreksiPresensi" autocomplete="off" class="space-y-3">
@else
    <form action="{{ route('presensi.storekoreksipresensi') }}" method="POST" id="formKoreksiPresensi" autocomplete="off" class="space-y-3">
@endif
    @csrf
    <input type="hidden" name="nik" value="{{ $karyawan->nik }}">
    <input type="hidden" name="tanggal" value="{{ Request::get('tanggal') }}">

    <div class="presensi-koreksi-grid">
        <!-- Info Karyawan -->
        <div class="min-w-0 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 p-3 divide-y divide-dashed divide-slate-200 dark:divide-slate-700 text-xs">
            <div class="py-1.5 flex items-center justify-between gap-2">
                <span class="text-slate-400">NIK</span>
                <span class="font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $karyawan->nik }}</span>
            </div>
            <div class="py-1.5 flex items-center justify-between gap-2">
                <span class="text-slate-400">Nama</span>
                <span class="font-bold text-slate-800 dark:text-slate-100 text-right">{{ $karyawan->nama_karyawan }}</span>
            </div>
            <div class="py-1.5 flex items-center justify-between gap-2">
                <span class="text-slate-400">Departemen</span>
                <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $karyawan->nama_dept }}</span>
            </div>
            <div class="py-1.5 flex items-center justify-between gap-2">
                <span class="text-slate-400">Jabatan</span>
                <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $karyawan->nama_jabatan }}</span>
            </div>
            <div class="py-1.5 flex items-center justify-between gap-2">
                <span class="text-slate-400">Cabang</span>
                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $karyawan->kode_cabang }}</span>
            </div>
        </div>

        <!-- Form Koreksi -->
        <div class="min-w-0 space-y-2.5">
            <div>
                <label for="kode_jadwal_koreksi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Jadwal <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_jadwal" id="kode_jadwal_koreksi"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Pilih Jadwal</option>
                        @foreach ($jadwal as $d)
                            <option value="{{ $d->kode_jadwal }}" @selected(!empty($presensi) && $presensi->kode_jadwal == $d->kode_jadwal)>
                                {{ $d->nama_jadwal }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="kode_jam_kerja_koreksi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    Jam Kerja <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_jam_kerja" id="kode_jam_kerja_koreksi"
                        class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Pilih Jam Kerja</option>
                    </select>
                </div>
            </div>

            @hasanyrole(['super admin', 'asst. manager hrd', 'spv presensi'])
                <div class="presensi-koreksi-jam-grid">
                    <div class="min-w-0">
                        <label for="jam_in_koreksi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Jam Masuk</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="jam_in" id="jam_in_koreksi" placeholder="HH:mm"
                                value="{{ !empty($presensi->jam_in) ? date('H:i', strtotime($presensi->jam_in)) : '' }}"
                                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-mono-num font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        </div>
                    </div>

                    <div class="min-w-0">
                        <label for="jam_out_koreksi" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">Jam Keluar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="jam_out" id="jam_out_koreksi" placeholder="HH:mm"
                                value="{{ !empty($presensi->jam_out) ? date('H:i', strtotime($presensi->jam_out)) : '' }}"
                                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-mono-num font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        </div>
                    </div>
                </div>
            @endhasanyrole
        </div>
    </div>

    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
        <button type="button" onclick="window.closeModalPresensi && window.closeModalPresensi()"
            class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Batal
        </button>
        <button type="submit" id="btnSimpanKoreksi"
            class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            <span>Update Presensi</span>
        </button>
    </div>
</form>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $('#formKoreksiPresensi');
        const $jadwal = $form.find('#kode_jadwal_koreksi');
        const $jamKerja = $form.find('#kode_jam_kerja_koreksi');

        function setFieldError($el, msg) {
            const $col = $el.closest('div').parent();
            $el.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $el.closest('.relative').find('.prefix-icon').removeClass('text-slate-400').addClass('text-rose-500');
            $col.find('.field-error-msg').remove();
            $col.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>${msg}</span>
                </p>
            `);
        }

        function clearFieldError($el) {
            const $col = $el.closest('div').parent();
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $el.closest('.relative').find('.prefix-icon').removeClass('text-rose-500').addClass('text-slate-400');
            $col.find('.field-error-msg').remove();
        }

        function loadjamkerja() {
            $.ajax({
                url: "{{ route('presensi.getjamkerja') }}",
                type: 'GET',
                data: {
                    kode_jadwal: $jadwal.val(),
                    kode_jam_kerja: "{{ !empty($presensi) ? $presensi->kode_jam_kerja : '' }}"
                },
                success: function(respond) {
                    $jamKerja.html(respond);
                }
            });
        }

        $jadwal.on('change', function() {
            if ($(this).val()) clearFieldError($(this));
            loadjamkerja();
        });
        loadjamkerja();

        $jamKerja.on('change', function() {
            if ($(this).val()) clearFieldError($(this));
        });

        $form.on('submit', function(e) {
            let firstInvalid = null;

            if (!$jadwal.val()) {
                setFieldError($jadwal, 'Jadwal wajib dipilih.');
                firstInvalid = firstInvalid || $jadwal;
            }
            if (!$jamKerja.val()) {
                setFieldError($jamKerja, 'Jam kerja wajib dipilih.');
                firstInvalid = firstInvalid || $jamKerja;
            }

            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
                return false;
            }

            const $btn = $('#btnSimpanKoreksi');
            $btn.prop('disabled', true)
                .addClass('opacity-75 cursor-not-allowed pointer-events-none')
                .html(`
                    <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memproses...</span>
                `);
        });
    });
</script>
