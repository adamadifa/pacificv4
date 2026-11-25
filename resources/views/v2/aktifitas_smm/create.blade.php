@extends('layouts.app')
@section('titlepage', 'Tambah Aktifitas SMM')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('aktifitassmm.index') }}" class="text-slate-400 hover:text-[#284c9a] transition">Aktifitas SMM</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Tambah</span>
    </div>
@endsection

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    .webcam-capture,
    .webcam-capture video {
        width: 100% !important;
        height: 260px !important;
        object-fit: cover;
        border-radius: 0.75rem;
    }
    #map {
        height: 76px;
        width: 120px;
        border-radius: 0.5rem;
        z-index: 20;
    }
</style>

<div class="max-w-2xl mx-auto space-y-3 pb-20 md:pb-6">
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between gap-2">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Tambah Aktifitas SMM</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Ambil foto lokasi dan catat aktifitas harian Anda.</p>
        </div>
        <a href="{{ route('aktifitassmm.index') }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center gap-1.5 shrink-0 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <input type="hidden" id="lokasi">

    <!-- KAMERA & LOKASI -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-2.5 sm:p-3">
        <div class="relative rounded-xl overflow-hidden bg-slate-900">
            <div class="webcam-capture"></div>

            <!-- Overlay Info Waktu & GPS -->
            <div class="absolute bottom-2.5 left-2.5 z-20 bg-slate-900/75 backdrop-blur-xs text-white rounded-lg px-2.5 py-1.5 text-[11px] space-y-0.5 max-w-[58%]">
                <p class="font-bold truncate">{{ auth()->user()->name }}</p>
                <p class="text-white/80">{{ DateToIndo(date('Y-m-d')) }} • <span id="jam" class="font-mono-num"></span></p>
                <p id="maptext" class="text-[10px] text-blue-200 font-mono-num truncate">Mendeteksi lokasi...</p>
            </div>

            <!-- Mini Map -->
            <div id="map" class="absolute bottom-2.5 right-2.5 border border-white/30 shadow-md opacity-90"></div>
        </div>
    </div>

    <!-- FORM AKTIFITAS -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 sm:p-3.5 space-y-3">
        <div>
            <label for="activity" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                Aktifitas <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute top-2.5 left-2.5 pointer-events-none text-slate-400 z-10">
                    <i data-lucide="file-edit" class="w-3.5 h-3.5"></i>
                </div>
                <textarea name="activity" id="activity" rows="4" placeholder="Tuliskan detail aktifitas Anda..."
                    class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition"></textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
            <a href="{{ route('aktifitassmm.index') }}"
                class="h-[36px] px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center justify-center transition">
                Batal
            </a>
            <button type="button" id="sendactivity"
                class="h-[36px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5">
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span>Kirim</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        function updateJam() {
            const d = new Date();
            const h = String(d.getHours()).padStart(2, '0');
            const m = String(d.getMinutes()).padStart(2, '0');
            const s = String(d.getSeconds()).padStart(2, '0');
            $('#jam').text(`${h}:${m}:${s}`);
            setTimeout(updateJam, 1000);
        }
        updateJam();

        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80,
            constraints: {
                facingMode: { ideal: 'environment' }
            }
        });
        Webcam.attach('.webcam-capture');

        const lokasiEl = document.getElementById('lokasi');
        const maptextEl = document.getElementById('maptext');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                lokasiEl.value = lat + "," + lng;
                maptextEl.textContent = lat.toFixed(5) + ", " + lng.toFixed(5);

                const map = L.map('map', { zoomControl: false, attributionControl: false }).setView([lat, lng], 16);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map);
                L.marker([lat, lng]).addTo(map);
                setTimeout(() => map.invalidateSize(), 300);
            }, function() {
                maptextEl.textContent = 'Lokasi tidak tersedia';
            });
        }

        $('#activity').on('input change', function() {
            const $el = $(this);
            const $wrap = $el.closest('.relative');
            $el.removeClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
            $wrap.find('> div:first-child').removeClass('text-rose-500').addClass('text-slate-400');
            $wrap.parent().find('.field-error-msg').remove();
        });

        $("#sendactivity").click(function() {
            const $activity = $("#activity");
            const activity = ($activity.val() || '').trim();
            const lokasi = $("#lokasi").val();

            if (!activity) {
                const $wrap = $activity.closest('.relative');
                $activity.addClass('border-rose-500 ring-1 ring-rose-500 focus:border-rose-500 focus:ring-rose-500/20');
                $wrap.find('> div:first-child').removeClass('text-slate-400').addClass('text-rose-500');
                $wrap.parent().find('.field-error-msg').remove();
                $wrap.after(`<p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1"><svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>Aktifitas wajib diisi.</span></p>`);
                $activity.focus();
                return;
            }

            let image = '';
            Webcam.snap(function(uri) {
                image = uri;
            });

            const $btn = $(this);
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`<svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg><span>Mengirim...</span>`);

            $.ajax({
                type: 'POST',
                url: '/aktifitassmm/store',
                data: {
                    _token: "{{ csrf_token() }}",
                    lokasi: lokasi,
                    activity: activity,
                    image: image
                },
                cache: false,
                success: function(respond) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: respond.message,
                        icon: 'success'
                    });
                    setTimeout("location.href='/aktifitassmm'", 1500);
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none');
                    $btn.html(`<i data-lucide="send" class="w-3.5 h-3.5"></i><span>Kirim</span>`);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    Swal.fire("Error", xhr.responseJSON?.message || "Terjadi kesalahan", "error");
                }
            });
        });
    });
</script>
@endpush
