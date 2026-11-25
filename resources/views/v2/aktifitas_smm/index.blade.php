@extends('layouts.app')
@section('titlepage', 'Aktifitas SMM')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">SFA</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Aktifitas SMM</span>
    </div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-3 pb-20 md:pb-6">
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between gap-2">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Aktifitas SMM</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Catatan dan riwayat aktifitas harian di lapangan.</p>
        </div>
        <a href="{{ route('aktifitassmm.create') }}"
            class="hidden sm:inline-flex h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition items-center gap-1.5 shrink-0">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            <span>Tambah Aktifitas</span>
        </a>
    </div>

    <!-- FILTER TANGGAL (NO-LABEL COMPACT V2) -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
            </div>
            <input type="text" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal" readonly
                class="flatpickr-date w-full h-[36px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
        </div>
    </div>

    <!-- LIST AKTIFITAS -->
    <div id="getaktifitas" class="space-y-2.5"></div>
</div>

<!-- FLOATING ACTION BUTTON (MOBILE) -->
<a href="{{ route('aktifitassmm.create') }}"
    style="position: fixed; bottom: 76px; right: 16px; z-index: 45;"
    class="sm:hidden w-12 h-12 rounded-full bg-[#284c9a] hover:bg-[#1e3a78] text-white shadow-lg flex items-center justify-center transition active:scale-95"
    title="Tambah Aktifitas">
    <i data-lucide="plus" class="w-5 h-5"></i>
</a>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                allowInput: false
            });
        }

        function getAktifitas() {
            let tanggal = $('#tanggal').val();
            $('#getaktifitas').html(`
                <div class="flex items-center justify-center py-8 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs text-slate-400 gap-2">
                    <svg class="animate-spin h-4 w-4 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Memuat aktifitas...</span>
                </div>
            `);

            $.ajax({
                method: "POST",
                url: "{{ route('aktifitassmm.getaktifitas') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal
                },
                cache: false,
                success: function(data) {
                    $('#getaktifitas').html(data);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        getAktifitas();
        $('#tanggal').change(function() {
            getAktifitas();
        });
    });
</script>
@endpush
