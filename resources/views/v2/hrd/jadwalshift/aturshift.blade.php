<style>
    .nav-tabs-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .nav-tabs-container::-webkit-scrollbar {
        display: none;
    }
</style>

<div class="space-y-3">
    <!-- Group Tabs -->
    <div class="nav-tabs-container pb-1" id="listgroup">
        <div class="inline-flex items-center gap-1.5 whitespace-nowrap">
            @foreach ($group as $d)
                <button type="button"
                    class="getgroup h-[32px] px-3 rounded-lg text-xs font-bold transition cursor-pointer {{ $loop->iteration == 1 ? 'active bg-[#284c9a] text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}"
                    kode_group="{{ $d->kode_group }}">
                    {{ $d->nama_group }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="flex flex-wrap items-center justify-between gap-2">
        <button type="button" id="tambahkansemua"
            class="h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            <span>Tambahkan Semua</span>
        </button>
        <button type="button" id="batalkansemua"
            class="h-[34px] px-3.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
            <i data-lucide="minus-circle" class="w-3.5 h-3.5"></i>
            <span>Batalkan Semua</span>
        </button>
    </div>

    <!-- Group Table -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto max-h-[400px]">
            <table class="w-full text-xs text-left whitespace-nowrap" id="tabelgroup">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider sticky top-0 z-10">
                    <tr>
                        <th class="py-2 px-3 text-center w-12">NO.</th>
                        <th class="py-2 px-3">NIK</th>
                        <th class="py-2 px-3">NAMA</th>
                        <th class="py-2 px-3 text-center">JADWAL</th>
                        <th class="py-2 px-3 text-center w-14">#</th>
                    </tr>
                </thead>
                <tbody id="loadgroup" class="divide-y divide-slate-100 dark:divide-slate-800">
                    <tr>
                        <td colspan="5" class="py-6 px-3 text-center text-xs text-slate-400">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        function loadgroup() {
            var kodeGroup = $('#listgroup .getgroup.active').attr('kode_group');
            var kode_jadwalshift = "{{ $kode_jadwalshift }}";
            if (!kodeGroup) return;
            $("#loadgroup").html('<tr><td colspan="5" class="py-6 px-3 text-center text-xs text-slate-400">Memuat data...</td></tr>');
            $("#loadgroup").load(`/jadwalshift/${kodeGroup}/${kode_jadwalshift}/getgroup`);
        }

        loadgroup();

        $("#listgroup .getgroup").on('click', function() {
            $("#listgroup .getgroup")
                .removeClass('active bg-[#284c9a] text-white shadow-xs')
                .addClass('bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300');
            $(this)
                .removeClass('bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300')
                .addClass('active bg-[#284c9a] text-white shadow-xs');
            loadgroup();
        });

        function loadshift(shift, kode_jadwal) {
            let kode_jadwalshift = "{{ $kode_jadwalshift }}";
            $("#loadshift" + shift).html(`<tr><td colspan="4" class="py-6 px-3 text-center text-xs text-slate-400">Memuat data...</td></tr>`);
            $.ajax({
                type: 'POST',
                url: '/jadwalshift/getshift',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_jadwal: kode_jadwal,
                    kode_jadwalshift: kode_jadwalshift
                },
                cache: false,
                success: function(respond) {
                    $("#loadshift" + shift).html(respond);
                }
            });
        }

        $(document).off('click.aturshift').on('click.aturshift', '#tabelgroup .updateJadwal', function(e) {
            e.preventDefault();
            const nik = $(this).attr('nik');
            const kode_jadwalshift = "{{ $kode_jadwalshift }}";
            const shift = "{{ $shift }}";
            $(this).html('<svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>');
            $.ajax({
                type: 'POST',
                url: `/jadwalshift/updatejadwal`,
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik,
                    kode_jadwalshift: kode_jadwalshift,
                    shift: shift
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loadgroup();
                        loadshift(1, "JD002");
                        loadshift(2, "JD003");
                        loadshift(3, "JD004");
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
        });

        $("#tambahkansemua").on('click', function() {
            const kode_jadwalshift = "{{ $kode_jadwalshift }}";
            const shift = "{{ $shift }}";
            const kode_group = $('#listgroup .getgroup.active').attr('kode_group');
            $("#loadgroup").html(`<tr><td colspan="5" class="py-6 px-3 text-center text-xs text-slate-400">Memproses...</td></tr>`);
            $.ajax({
                type: 'POST',
                url: `/jadwalshift/tambahkansemua`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_jadwalshift: kode_jadwalshift,
                    shift: shift,
                    kode_group: kode_group
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loadgroup();
                        loadshift(1, "JD002");
                        loadshift(2, "JD003");
                        loadshift(3, "JD004");
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
        });

        $("#batalkansemua").on('click', function() {
            const kode_jadwalshift = "{{ $kode_jadwalshift }}";
            const shift = "{{ $shift }}";
            const kode_group = $('#listgroup .getgroup.active').attr('kode_group');
            $("#loadgroup").html(`<tr><td colspan="5" class="py-6 px-3 text-center text-xs text-slate-400">Memproses...</td></tr>`);
            $.ajax({
                type: 'POST',
                url: `/jadwalshift/batalkansemua`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_jadwalshift: kode_jadwalshift,
                    shift: shift,
                    kode_group: kode_group
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loadgroup();
                        loadshift(1, "JD002");
                        loadshift(2, "JD003");
                        loadshift(3, "JD004");
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
        });
    });
</script>
