@extends('v2.layouts.app')
@section('titlepage', 'Buat Penilaian')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">HRD</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('penilaiankaryawan.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Penilaian Karyawan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Buat Penilaian</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full max-w-5xl">
    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Buat Penilaian Karyawan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Formulir evaluasi kinerja dan rekomendasi kontrak karyawan.</p>
        </div>
        <a href="{{ route('penilaiankaryawan.index') }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition flex items-center gap-1.5 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('penilaiankaryawan.store', Crypt::encrypt($kontrak->no_kontrak)) }}" method="POST" id="formPenilaian" class="space-y-3.5">
        @csrf
        <input type="hidden" name="tanggal" id="tanggal" value="{{ $tanggal }}">
        <input type="hidden" name="kode_doc" id="kode_doc" value="{{ $doc }}">

        <!-- INFO KARYAWAN, FOTO & TOTAL SCORE -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
            <div class="bg-[#284c9a] p-4 sm:p-5 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full overflow-hidden shrink-0 bg-white/15 border-2 border-white/40 flex items-center justify-center">
                        @if (!empty($karyawan->foto) && Storage::disk('public')->exists('/karyawan/' . ($karyawan instanceof App\Models\Karyawan ? $karyawan->getRawOriginal('foto') : $karyawan->foto)))
                            <img src="{{ getfotoKaryawan($karyawan->foto) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <i data-lucide="user" class="w-7 h-7 text-white"></i>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-base sm:text-lg font-black text-white leading-tight">{{ formatName($karyawan->nama_karyawan) }}</h4>
                        <div class="flex flex-wrap items-center gap-2 mt-1.5">
                            <span class="px-2 py-0.5 rounded-md bg-white text-[#284c9a] text-[11px] font-mono-num font-bold">{{ $karyawan->nik }}</span>
                            <span class="text-xs text-white/80 flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                {{ DateToIndo($tanggal) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex sm:flex-col items-center justify-between sm:justify-center gap-1 shrink-0">
                    <div class="w-16 h-16 rounded-full bg-white/15 border-2 border-white/30 flex items-center justify-center">
                        <h1 id="totalscore" class="text-2xl font-black font-mono-num text-white">0</h1>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75">Total Score</span>
                </div>
            </div>

            <div class="px-4 py-3 grid grid-cols-1 sm:grid-cols-3 gap-3 bg-slate-50/70 dark:bg-slate-800/40 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center text-[#284c9a] dark:text-blue-400 shrink-0">
                        <i data-lucide="building" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Departemen</p>
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">{{ $karyawan->nama_dept }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center text-[#284c9a] dark:text-blue-400 shrink-0">
                        <i data-lucide="briefcase" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Jabatan</p>
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate">{{ $karyawan->nama_jabatan }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center text-[#284c9a] dark:text-blue-400 shrink-0">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Periode Kontrak</p>
                        <p class="text-xs font-bold text-[#284c9a] dark:text-blue-400 truncate">{{ DateToIndo($kontrak->dari) }} s.d {{ DateToIndo($kontrak->sampai) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- A. TABEL PENILAIAN -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
            <div class="px-3.5 py-2 bg-[#284c9a] text-white">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-square" class="w-4 h-4 text-blue-200"></i>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">A. Penilaian</h5>
                </div>
                <p class="text-[11px] text-white/75 mt-0.5">Checklist bobot penilaian di bawah ini (semakin besar angka yang dipilih semakin baik).</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-2 px-3 w-12 text-center">NO.</th>
                            <th class="py-2 px-3">SASARAN KERJA</th>
                            <th class="py-2 px-3 w-64">NILAI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @php
                            $no = 1;
                            $kode_kategori = '';
                            $jenis_kompetensi = '';
                        @endphp
                        @foreach ($penilaian_item as $d)
                            @if ($kode_kategori != $d->kode_kategori)
                                @php $no = 1; @endphp
                                <tr class="bg-slate-100 dark:bg-slate-800/80">
                                    <th colspan="3" class="py-2 px-3 text-xs font-bold text-slate-800 dark:text-slate-100">
                                        {{ $d->nama_kategori }}
                                    </th>
                                </tr>
                            @endif

                            @if ($d->jenis_kompetensi != 'C00' && $jenis_kompetensi != $d->jenis_kompetensi)
                                <tr class="bg-emerald-50/80 dark:bg-emerald-950/30">
                                    <td colspan="3" class="py-1.5 px-3 text-center text-[11px] font-bold text-emerald-700 dark:text-emerald-300">
                                        {{ $d->jenis_kompetensi == 'C01' ? 'Kompetensi Wajib' : 'Kompetensi' }}
                                    </td>
                                </tr>
                            @endif

                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-2 px-3 text-center font-mono-num text-slate-500">{{ $no }}</td>
                                <td class="py-2 px-3 text-slate-700 dark:text-slate-200">
                                    <input type="hidden" name="kode_item" value="{{ $d->kode_item }}">
                                    {{ $d->item_penilaian }}
                                </td>
                                <td class="py-2 px-3 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-700 dark:text-slate-200">
                                            <input class="skor w-4 h-4 text-[#284c9a] border-slate-300 focus:ring-[#284c9a]" type="radio"
                                                name="skor[{{ $d->kode_item }}]" id="skor_{{ $d->kode_item }}_m" value="1">
                                            <span>Memuaskan</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-medium text-rose-600 dark:text-rose-400">
                                            <input class="skor w-4 h-4 text-rose-600 border-slate-300 focus:ring-rose-500" type="radio"
                                                name="skor[{{ $d->kode_item }}]" id="skor_{{ $d->kode_item }}_tm" value="0">
                                            <span>Tidak Memuaskan</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @php
                                $no++;
                                $kode_kategori = $d->kode_kategori;
                                $jenis_kompetensi = $d->jenis_kompetensi;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="error-skor" class="hidden px-3.5 py-2 bg-rose-50/80 dark:bg-rose-950/30 border-t border-rose-200/80 dark:border-rose-900/60 text-[11px] font-semibold text-rose-600 dark:text-rose-400 items-center gap-1.5">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>Masih ada pertanyaan penilaian yang belum dipilih.</span>
            </div>
        </div>

        <!-- REKAP PRESENSI, MASA KONTRAK, REKOMENDASI & EVALUASI -->
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 sm:p-4 shadow-2xs space-y-4">
            <!-- Rekap Presensi -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                @foreach ([
                    ['name' => 'sakit', 'label' => 'Sakit', 'val' => $rekappresensi->sakit ?? 0, 'icon' => 'heart-pulse'],
                    ['name' => 'izin', 'label' => 'Izin', 'val' => $rekappresensi->izin ?? 0, 'icon' => 'file-text'],
                    ['name' => 'alfa', 'label' => 'Alfa', 'val' => $rekappresensi->alpa ?? 0, 'icon' => 'clock-alert'],
                    ['name' => 'sid', 'label' => 'SID', 'val' => $rekappresensi->sid ?? 0, 'icon' => 'receipt'],
                ] as $absen)
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">{{ $absen['label'] }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="{{ $absen['icon'] }}" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="{{ $absen['name'] }}" value="{{ $absen['val'] }}" readonly
                                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs font-mono-num font-bold text-slate-700 dark:text-slate-200">
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- B. Masa Kontrak Kerja -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                    B. Masa Kontrak Kerja <span class="text-rose-500">*</span>
                </label>
                <div class="flex flex-wrap gap-3">
                    <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">
                        <input class="chbmk w-4 h-4 rounded text-[#284c9a] border-slate-300" type="checkbox" value="TP" name="masa_kontrak">
                        <span>Tidak di Perpanjang</span>
                    </label>
                    @if ($tanggal < '2026-04-01')
                        <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">
                            <input class="chbmk w-4 h-4 rounded text-[#284c9a] border-slate-300" type="checkbox" value="K3" name="masa_kontrak">
                            <span>3 Bulan</span>
                        </label>
                    @endif
                    <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">
                        <input class="chbmk w-4 h-4 rounded text-[#284c9a] border-slate-300" type="checkbox" value="K6" name="masa_kontrak">
                        <span>6 Bulan</span>
                    </label>
                    <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">
                        <input class="chbmk w-4 h-4 rounded text-[#284c9a] border-slate-300" type="checkbox" value="KT" name="masa_kontrak">
                        <span>Karyawan Tetap</span>
                    </label>
                </div>
                <p id="error-masakontrak" class="hidden text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>Masa kontrak kerja wajib dipilih.</span>
                </p>
            </div>

            <!-- C. Rekomendasi -->
            <div>
                <label for="rekomendasi_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    C. Riwayat Absensi dan Rekomendasi User <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="prefix-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <textarea name="rekomendasi" id="rekomendasi_create" rows="3" placeholder="Tuliskan rekomendasi..."
                        class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition"></textarea>
                </div>
            </div>

            <!-- D. Evaluasi -->
            <div>
                <label for="evaluasi_create" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                    D. Evaluasi Skill Teknis / Kinerja (Wajib Diisi User) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="prefix-icon absolute top-2.5 left-0 pl-2.5 flex items-start pointer-events-none text-slate-400 z-10">
                        <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                    </div>
                    <textarea name="evaluasi" id="evaluasi_create" rows="3" placeholder="Tuliskan evaluasi kinerja..."
                        class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition"></textarea>
                </div>
            </div>

            <!-- Footer Submit (Right-Aligned) -->
            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                <a href="{{ route('penilaiankaryawan.index') }}"
                    class="h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold transition flex items-center justify-center">
                    Batal
                </a>
                <button type="submit" id="btnSimpan"
                    class="h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    <span>Simpan Penilaian</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $form = $('#formPenilaian');
        const $rekomendasi = $form.find('#rekomendasi_create');
        const $evaluasi = $form.find('#evaluasi_create');

        $('.chbmk').on('change', function() {
            $('.chbmk').prop('checked', false);
            $(this).prop('checked', true);
            $('#error-masakontrak').removeClass('flex').addClass('hidden');
        });

        function calculateScore() {
            let totalScore = 0;
            $form.find('input[type=radio]:checked').each(function() {
                totalScore += parseInt($(this).val(), 10) || 0;
            });
            $('#totalscore').text(totalScore);
        }

        function calculateUncheckedRadios() {
            let count = 0;
            $form.find('.skor').each(function() {
                if (!$(this).closest('td').find('input[type=radio]:checked').length) {
                    count++;
                }
            });
            return count;
        }

        calculateScore();

        $form.on('click', 'input[type=radio]', function() {
            calculateScore();
            if (calculateUncheckedRadios() === 0) {
                $('#error-skor').removeClass('flex').addClass('hidden');
            }
        });

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

        $rekomendasi.add($evaluasi).on('input change', function() {
            if ($(this).val().trim()) clearFieldError($(this));
        });

        $form.on('submit', function(e) {
            let hasError = false;
            let firstInvalid = null;

            if (calculateUncheckedRadios() > 0) {
                $('#error-skor').removeClass('hidden').addClass('flex');
                hasError = true;
            }
            if ($form.find("input[name='masa_kontrak']:checked").length === 0) {
                $('#error-masakontrak').removeClass('hidden').addClass('flex');
                hasError = true;
            }
            if (!$rekomendasi.val().trim()) {
                setFieldError($rekomendasi, 'Rekomendasi wajib diisi.');
                firstInvalid = firstInvalid || $rekomendasi;
                hasError = true;
            }
            if (!$evaluasi.val().trim()) {
                setFieldError($evaluasi, 'Evaluasi wajib diisi.');
                firstInvalid = firstInvalid || $evaluasi;
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                if (firstInvalid) firstInvalid.focus();
                return false;
            }

            const $btn = $('#btnSimpan');
            $btn.prop('disabled', true)
                .addClass('opacity-75 cursor-not-allowed pointer-events-none')
                .html(`
                    <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Menyimpan...</span>
                `);
        });
    });
</script>
@endpush
