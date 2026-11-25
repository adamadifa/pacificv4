<form action="#" id="frmKaryawanLibur" autocomplete="off" class="space-y-3">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center gap-2">
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
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 items-start">
        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <select name="kode_group" id="kode_group_libur"
                class="select2GroupLibur w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200">
                <option value="">Semua Group</option>
                @foreach ($group as $g)
                    <option value="{{ $g->kode_group }}">{{ strtoupper($g->nama_group) }}</option>
                @endforeach
            </select>
        </div>

        <div class="relative">
            <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input type="text" name="nama_karyawan" id="nama_karyawan_libur" placeholder="Cari Nama Karyawan..."
                class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
        </div>
    </div>

    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto max-h-[380px]">
            <table class="w-full text-xs text-left whitespace-nowrap" id="tabelkaryawan">
                <thead class="bg-[#1e3a78] text-white/90 text-[11px] font-bold uppercase tracking-wider sticky top-0 z-10">
                    <tr>
                        <th class="py-2 px-3 text-center w-12">NO.</th>
                        <th class="py-2 px-3">NIK</th>
                        <th class="py-2 px-3">NAMA KARYAWAN</th>
                        <th class="py-2 px-3">GROUP</th>
                        <th class="py-2 px-3 text-center w-14">#</th>
                    </tr>
                </thead>
                <tbody id="loadkaryawan" class="divide-y divide-slate-100 dark:divide-slate-800">
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
        const $form = $('#frmKaryawanLibur');

        if ($.fn.select2) {
            $form.find('.select2GroupLibur').select2({
                placeholder: 'Semua Group',
                allowClear: true,
                width: '100%'
            });
        }

        function loadkaryawan() {
            const kode_libur = "{{ Crypt::encrypt($harilibur->kode_libur) }}";
            const kode_group = $form.find("#kode_group_libur").val();
            const nama_karyawan = $form.find("#nama_karyawan_libur").val();
            $.ajax({
                type: 'POST',
                url: `/harilibur/getkaryawan`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_libur: kode_libur,
                    kode_group: kode_group,
                    nama_karyawan: nama_karyawan
                },
                cache: false,
                success: function(respond) {
                    $("#loadkaryawan").html(respond);
                    if (typeof window.loadliburkaryawan === 'function') {
                        window.loadliburkaryawan();
                    }
                }
            });
        }

        loadkaryawan();

        $form.find("#kode_group_libur").on('change', function() {
            $("#loadkaryawan").html(`<tr><td colspan="5" class="py-6 px-3 text-center text-xs text-slate-400">Memuat data...</td></tr>`);
            loadkaryawan();
        });

        $form.find("#nama_karyawan_libur").on('keyup', function() {
            $("#loadkaryawan").html(`<tr><td colspan="5" class="py-6 px-3 text-center text-xs text-slate-400">Memuat data...</td></tr>`);
            loadkaryawan();
        });

        $(document).off('click.aturlibur').on('click.aturlibur', '#tabelkaryawan .updateLibur', function(e) {
            e.preventDefault();
            const nik = $(this).attr('nik');
            const kode_libur = "{{ $harilibur->kode_libur }}";
            $(this).html('<svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>');
            $.ajax({
                type: 'POST',
                url: `/harilibur/updateliburkaryawan`,
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik,
                    kode_libur: kode_libur
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loadkaryawan();
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

        $form.find("#tambahkansemua").on('click', function(e) {
            e.preventDefault();
            const kode_libur = "{{ $harilibur->kode_libur }}";
            const kode_group = $form.find("#kode_group_libur").val();
            $("#loadkaryawan").html(`<tr><td colspan="5" class="py-6 px-3 text-center text-xs text-slate-400">Memproses...</td></tr>`);
            $.ajax({
                type: 'POST',
                url: `/harilibur/tambahkansemua`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_libur: kode_libur,
                    kode_group: kode_group
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loadkaryawan();
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

        $form.find("#batalkansemua").on('click', function(e) {
            e.preventDefault();
            const kode_libur = "{{ $harilibur->kode_libur }}";
            const kode_group = $form.find("#kode_group_libur").val();
            $("#loadkaryawan").html(`<tr><td colspan="5" class="py-6 px-3 text-center text-xs text-slate-400">Memproses...</td></tr>`);
            $.ajax({
                type: 'POST',
                url: `/harilibur/batalkansemua`,
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_libur: kode_libur,
                    kode_group: kode_group
                },
                cache: false,
                success: function(respond) {
                    if (respond.success == true) {
                        loadkaryawan();
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
