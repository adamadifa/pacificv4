@extends('layouts.app')
@section('titlepage', 'Dashboard')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Dashboard</span>
    </div>
@endsection

@section('content')
<div class="space-y-3">
    <!-- WELCOME SLIM BAR -->
    @include('dashboard.welcome')

    <!-- FILTER TARGET KOMISI (NO-LABEL COMPACT V2, MOBILE-FIRST) -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 sm:p-3.5">
        <div class="flex items-center gap-2 pb-2 mb-2.5 border-b border-slate-100 dark:border-slate-800">
            <i data-lucide="target" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-200">Monitoring Target Komisi</h4>
        </div>

        <form action="#" id="formTarget" class="space-y-2.5">
            @hasanyrole($roles_show_cabang)
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang" id="kode_cabang"
                        class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}">{{ textUpperCase($d->nama_cabang) }}</option>
                        @endforeach
                    </select>
                </div>
            @endhasanyrole

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_salesman" id="kode_salesman"
                    class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Semua Salesman</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="bulan" id="bulan"
                        class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Bulan</option>
                        @foreach ($list_bulan as $d)
                            <option value="{{ $d['kode_bulan'] }}" {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }}>
                                {{ $d['nama_bulan'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar-range" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="tahun" id="tahun"
                        class="w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Tahun</option>
                        @for ($t = $start_year; $t <= date('Y'); $t++)
                            <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- TARGET RESULT CONTAINER -->
    <div id="loadtarget" class="space-y-2.5"></div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const $formTarget = $('#formTarget');

        function getsalesmanbyCabang() {
            var kode_cabang = $formTarget.find("#kode_cabang").val();
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang
                },
                cache: false,
                success: function(respond) {
                    $formTarget.find("#kode_salesman").html(respond);
                }
            });
        }

        function loadtarget() {
            const kode_cabang = $formTarget.find("#kode_cabang").val();
            const kode_salesman = $formTarget.find("#kode_salesman").val();
            const bulan = $formTarget.find("#bulan").val();
            const tahun = $formTarget.find("#tahun").val();

            $("#loadtarget").html(`
                <div class="flex items-center justify-center py-8 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs text-slate-400 gap-2">
                    <svg class="animate-spin h-4 w-4 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Memuat data target...</span>
                </div>
            `);

            $.ajax({
                type: "POST",
                url: "/targetkomisi/gettarget",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun,
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    $("#loadtarget").html(respond);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        getsalesmanbyCabang();
        loadtarget();

        $formTarget.find("#kode_cabang").change(function() {
            getsalesmanbyCabang();
        });

        $formTarget.find("#kode_cabang,#kode_salesman,#bulan,#tahun").change(function() {
            loadtarget();
        });
    });
</script>
@endpush
