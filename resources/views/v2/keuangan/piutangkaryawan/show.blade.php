<div class="space-y-3.5">
    <!-- Top 2-Column Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 items-stretch">
        <!-- Kolom Kiri: Profil Peminjam -->
        <div class="p-3 sm:p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/40">
            <div class="flex items-center gap-1.5 pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
                <i data-lucide="user" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <h6 class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Peminjam</h6>
            </div>
            <dl class="space-y-1.5 text-xs">
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">No. Pinjaman</dt>
                    <dd id="no_pinjaman_val" class="font-mono-num font-bold text-slate-800 dark:text-slate-100 text-right">{{ $piutangkaryawan->no_pinjaman }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Tanggal Pengajuan</dt>
                    <dd class="font-mono-num font-semibold text-slate-700 dark:text-slate-300 text-right">{{ DateToIndo($piutangkaryawan->tanggal) }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">NIK</dt>
                    <dd class="font-mono-num font-semibold text-slate-700 dark:text-slate-300 text-right">{{ $piutangkaryawan->nik }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Nama Karyawan</dt>
                    <dd class="font-bold text-slate-800 dark:text-slate-100 text-right">{{ textUpperCase($piutangkaryawan->nama_karyawan) }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Jabatan</dt>
                    <dd class="font-semibold text-slate-700 dark:text-slate-300 text-right">{{ $piutangkaryawan->nama_jabatan }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Departemen</dt>
                    <dd class="font-semibold text-slate-700 dark:text-slate-300 text-right">{{ $piutangkaryawan->nama_dept }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Kantor Cabang</dt>
                    <dd class="font-semibold text-slate-700 dark:text-slate-300 text-right">{{ $piutangkaryawan->nama_cabang }}</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-slate-500 dark:text-slate-400">Masa Kerja</dt>
                    <dd class="font-semibold text-slate-700 dark:text-slate-300 text-right">
                        @php
                            $masakerja = hitungMasakerja($piutangkaryawan->tanggal_masuk, $piutangkaryawan->tanggal);
                        @endphp
                        {{ $masakerja['tahun'] }} Tahun {{ $masakerja['bulan'] }} Bulan {{ $masakerja['hari'] }} Hari
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Kolom Kanan: Ringkasan Piutang (Dynamic Load) -->
        <div id="loadpiutang"></div>
    </div>

    <!-- Tabel Histori Pembayaran -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="history" class="w-3.5 h-3.5 text-blue-200"></i>
                <h6 class="text-xs font-bold text-white uppercase tracking-wider">Histori Pembayaran</h6>
            </div>
            @can('pembayaranpk.create')
                <button type="button" id="btnBayarPiutangModal" data-nopinjam="{{ Crypt::encrypt($piutangkaryawan->no_pinjaman) }}"
                    class="h-[28px] px-3 rounded-lg bg-white/15 hover:bg-white/25 text-white text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Pembayaran</span>
                </button>
            @endcan
        </div>

        <div class="overflow-x-auto max-h-[280px]">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90 sticky top-0 z-10">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">NO. BUKTI</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">JUMLAH</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">JENIS BAYAR</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center w-12">#</th>
                    </tr>
                </thead>
                <tbody id="loadhistoribayar" class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        let sisatagihan = "{{ $piutangkaryawan->jumlah - $piutangkaryawan->totalpembayaran }}";
        let no_pinjaman_enc = "{{ Crypt::encrypt($piutangkaryawan->no_pinjaman) }}";

        function getpiutang() {
            $("#loadpiutang").load(`/piutangkaryawan/${no_pinjaman_enc}/getpiutangkaryawan`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        }

        function gethistoribayar() {
            var no_pinjaman = "{{ $piutangkaryawan->no_pinjaman }}";
            $.ajax({
                type: 'POST',
                url: '/pembayaranpiutangkaryawan/gethistoribayar',
                data: {
                    _token: "{{ csrf_token() }}",
                    no_pinjaman: no_pinjaman
                },
                cache: false,
                success: function(respond) {
                    $("#loadhistoribayar").html(respond);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    getpiutang();
                }
            });
        }

        getpiutang();
        gethistoribayar();

        $(document).off('change', '#switchKategori').on('change', '#switchKategori', function(e) {
            e.preventDefault();
            const checkbox = $(this);
            const isChecked = checkbox.is(':checked');
            const targetKategori = isChecked ? 'EK' : 'KA';
            const targetNama = isChecked ? 'Piutang Eks Karyawan' : 'Piutang Karyawan';

            Swal.fire({
                title: "Konfirmasi Ubah Kategori",
                text: `Apakah Anda yakin ingin mengubah kategori piutang ini menjadi "${targetNama}"?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#284c9a",
                cancelButtonColor: "#ef4444",
                confirmButtonText: "Ya, Ubah!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: `/piutangkaryawan/${no_pinjaman_enc}/updatekategori`,
                        data: {
                            _token: "{{ csrf_token() }}",
                            kategori: targetKategori
                        },
                        cache: false,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: response.message,
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                getpiutang();
                                $("#modalPiutang").data('kategori-changed', true);
                            } else {
                                Swal.fire("Gagal!", response.message, "error");
                                checkbox.prop('checked', !isChecked);
                            }
                        },
                        error: function(xhr) {
                            Swal.fire("Error!", xhr.responseJSON?.message || "Terjadi kesalahan server", "error");
                            checkbox.prop('checked', !isChecked);
                        }
                    });
                } else {
                    checkbox.prop('checked', !isChecked);
                }
            });
        });

        $(document).off('submit.bayarPk').on('submit.bayarPk', '#formPembayaranpituangkaryawan', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            const $form = $(this);
            let hasError = false;
            let $firstError = null;

            const jenis_bayar = $form.find("#jenis_bayar_pk").val();
            const bulan = $form.find("#bulan_bayar_pk").val();
            const tahun = $form.find("#tahun_bayar_pk").val();
            const jmlStr = $form.find("#jumlah_bayar_pk").val();
            const jumlah = jmlStr ? parseInt(jmlStr.replace(/\./g, '')) || 0 : 0;
            const tagihan = parseInt(sisatagihan.toString().replace(/\./g, '')) || 0;
            const no_pinjaman = $("#no_pinjaman_val").text().trim();

            if (!jenis_bayar) {
                $form.find("#error-jenis-bayar-pk").removeClass('hidden').addClass('flex');
                $form.find("#jenis_bayar_pk").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#jenis_bayar_pk");
            }

            if (!bulan) {
                $form.find("#error-bulan-bayar-pk").removeClass('hidden').addClass('flex');
                $form.find("#bulan_bayar_pk").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#bulan_bayar_pk");
            }

            if (!tahun) {
                $form.find("#error-tahun-bayar-pk").removeClass('hidden').addClass('flex');
                $form.find("#tahun_bayar_pk").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#tahun_bayar_pk");
            }

            if (jumlah <= 0) {
                $form.find("#error-jumlah-bayar-pk").removeClass('hidden').addClass('flex').find('span').text('Jumlah pembayaran wajib diisi lebih dari 0');
                $form.find("#jumlah_bayar_pk").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#jumlah_bayar_pk");
            } else if (jumlah > tagihan) {
                $form.find("#error-jumlah-bayar-pk").removeClass('hidden').addClass('flex').find('span').text('Jumlah pembayaran melebihi sisa tagihan');
                $form.find("#jumlah_bayar_pk").addClass('border-rose-500 ring-1 ring-rose-500');
                hasError = true;
                if (!$firstError) $firstError = $form.find("#jumlah_bayar_pk");
            }

            if (hasError) {
                if ($firstError) $firstError.focus();
                return false;
            }

            const $btn = $form.find("#btnSimpanBayarPk");
            const originalHtml = $btn.html();
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none');
            $btn.html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menyimpan...</span>
            `);

            $.ajax({
                type: 'POST',
                url: '/pembayaranpiutangkaryawan/store',
                data: {
                    _token: "{{ csrf_token() }}",
                    no_pinjaman: no_pinjaman,
                    jenis_bayar: jenis_bayar,
                    bulan: bulan,
                    tahun: tahun,
                    jumlah: jumlah
                },
                cache: false,
                success: function(respond) {
                    $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none').html(originalHtml);
                    if (respond == 0) {
                        sisatagihan = Math.max(0, tagihan - jumlah);
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Pembayaran berhasil disimpan",
                            icon: "success",
                            confirmButtonColor: '#284c9a'
                        }).then(() => {
                            gethistoribayar();
                            if (typeof modalBayarPiutangHelper !== 'undefined') {
                                modalBayarPiutangHelper.close();
                            }
                        });
                    } else if (respond == 2) {
                        Swal.fire("Oops!", "Melebihi Sisa Tagihan", "warning");
                    } else if (respond == 3) {
                        Swal.fire("Oops!", "Pembayaran Potongan Gaji untuk bulan ini sudah ada", "warning");
                    } else if (respond == 4) {
                        Swal.fire("Oops!", "Pembayaran Potongan Komisi untuk bulan ini sudah ada", "warning");
                    } else {
                        Swal.fire("Error!", "Silahkan Hubungi IT", "error");
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none').html(originalHtml);
                    Swal.fire("Error!", "Terjadi kesalahan saat menyimpan data", "error");
                }
            });
        });

        $(document).off('click.delBayarPk').on('click.delBayarPk', '.btnDeletebayar', function(e) {
            e.preventDefault();
            const no_bukti = $(this).attr("no_bukti");
            Swal.fire({
                title: 'Hapus Pembayaran Ini?',
                text: 'Jika dihapus maka data pembayaran akan hilang permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#284c9a',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '/pembayaranpiutangkaryawan/delete',
                        data: {
                            _token: "{{ csrf_token() }}",
                            no_bukti: no_bukti
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond == 0) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Data Berhasil Dihapus",
                                    icon: "success",
                                    confirmButtonColor: '#284c9a'
                                }).then(() => {
                                    gethistoribayar();
                                });
                            }
                        }
                    });
                }
            });
        });
    });
</script>
