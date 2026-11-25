@extends('layouts.app')
@section('titlepage', 'Input Retur SFA')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('sfa.pelanggan') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Pelanggan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($kode_pelanggan)) }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">{{ $kode_pelanggan }}</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Input Retur</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 pb-20 lg:pb-0">
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Input Retur SFA</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Buat transaksi retur produk untuk outlet pelanggan.</p>
        </div>
        <a href="{{ route('sfa.showpelanggan', Crypt::encrypt($kode_pelanggan)) }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold inline-flex items-center gap-1.5 transition shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('retur.store') }}" method="POST" id="formRetur" class="space-y-4">
        @csrf

        <!-- GRAND TOTAL STRIP -->
        <div class="rounded-xl overflow-hidden shadow-xs bg-[#284c9a] text-white relative">
            <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>
            <div class="relative z-10 p-4 sm:p-5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center shrink-0">
                        <i data-lucide="rotate-ccw" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-white/70">Total Retur</span>
                        <div class="text-2xl sm:text-3xl font-black font-mono-num tracking-tight leading-none mt-1">
                            Rp <span id="grandtotal_text">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">

            <!-- LEFT: INFORMASI RETUR (NO-LABEL COMPACT) -->
            <div class="lg:col-span-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
                <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Retur</span>
                </div>

                <div class="p-3.5 space-y-2">
                    <div class="grid grid-cols-2 lg:grid-cols-1 gap-2 items-start">
                        <div class="field-group">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="barcode" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="no_retur" id="no_retur" placeholder="No. Retur (Opsional)"
                                    class="w-full h-[38px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium font-mono-num text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                            </div>
                        </div>

                        <div class="field-group">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="tanggal" id="tanggal" readonly value="{{ date('Y-m-d') }}" placeholder="Pilih Tanggal"
                                    class="flatpickr-date w-full h-[38px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-2 items-start">
                        <div class="field-group">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="store" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="nama_pelanggan" id="nama_pelanggan" readonly placeholder="Nama Pelanggan"
                                    class="w-full h-[38px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-medium text-slate-600 dark:text-slate-300">
                            </div>
                            <input type="hidden" id="kode_pelanggan" name="kode_pelanggan">
                        </div>

                        <div class="field-group">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="user-check" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="nama_salesman" id="nama_salesman" readonly placeholder="Nama Salesman"
                                    class="w-full h-[38px] sm:h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xs font-medium text-slate-600 dark:text-slate-300">
                            </div>
                            <input type="hidden" name="kode_salesman" id="kode_salesman">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-2 items-start">
                        <div class="field-group">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="repeat" class="w-4 h-4"></i>
                                </div>
                                <select name="jenis_retur" id="jenis_retur"
                                    class="w-full h-[38px] sm:h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                    <option value="">Jenis Retur</option>
                                    <option value="GB">Ganti Barang</option>
                                    <option value="PF">Potongan Faktur</option>
                                </select>
                            </div>
                        </div>

                        <div class="field-group">
                            <div class="relative">
                                <div class="input-prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                    <i data-lucide="receipt" class="w-4 h-4"></i>
                                </div>
                                <select name="no_faktur" id="no_faktur"
                                    class="w-full h-[38px] sm:h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                                    <option value="">Pilih Faktur</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: DETAIL PRODUK RETUR & KONFIRMASI -->
            <div class="lg:col-span-8 space-y-4">
                <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
                    <div class="px-3.5 py-2.5 border-b border-slate-200/80 dark:border-slate-800 flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                                <i data-lucide="package" class="w-3.5 h-3.5"></i>
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Detail Produk Retur</span>
                        </div>

                        <button type="button" id="tambahproduk"
                            class="h-[32px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 cursor-pointer">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            <span>Tambah Produk</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs" id="tabelproduk">
                            <tbody id="loadproduk" class="divide-y divide-slate-100 dark:divide-slate-800/80"></tbody>
                            <tfoot>
                                <tr class="bg-slate-100/90 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-700">
                                    <td class="py-2.5 px-3.5 text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">Total Retur</td>
                                    <td class="py-2.5 px-3.5 text-right text-sm font-black text-slate-900 dark:text-white font-mono-num" id="subtotal">0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="produk_error_container" class="hidden px-3.5 py-2 border-t border-rose-200 dark:border-rose-900/60 bg-rose-50/70 dark:bg-rose-950/30"></div>

                    <div class="p-3.5 border-t border-slate-100 dark:border-slate-800 space-y-3">
                        <label for="defaultCheck3"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/60 text-xs font-bold text-slate-700 dark:text-slate-200 cursor-pointer select-none">
                            <input class="agreement rounded border-slate-300 text-[#284c9a] focus:ring-[#284c9a]" name="aggrement" value="aggrement" type="checkbox" id="defaultCheck3">
                            <span>Yakin Akan Disimpan?</span>
                        </label>

                        <div id="saveButton" class="hidden pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                            <button type="submit" id="btnSimpan"
                                class="w-full sm:w-auto h-[38px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 cursor-pointer">
                                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                                <span>Simpan Retur</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- STICKY MOBILE CHECKOUT BAR -->
<div class="fixed bottom-0 inset-x-0 z-30 lg:hidden bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 px-4 py-2.5 flex items-center justify-between gap-3 shadow-lg">
    <div class="min-w-0">
        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Retur</span>
        <span class="block text-base font-black text-[#284c9a] dark:text-blue-400 font-mono-num truncate">
            Rp <span id="grandtotal_mobile_text">0</span>
        </span>
    </div>
    <button type="button" id="btnSimpanMobile" style="display: none;"
        class="h-[40px] px-5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5 shrink-0 cursor-pointer">
        <i data-lucide="send" class="w-3.5 h-3.5"></i>
        <span>Simpan</span>
    </button>
</div>

<!-- MODAL TAMBAH PRODUK V2 (BOTTOM SHEET ON MOBILE, STATIC BACKDROP, CLEAN WHITE HEADER) -->
<div id="modaleditProduk" class="fixed inset-0 z-50 hidden items-end sm:items-center justify-center bg-slate-950/60 backdrop-blur-[2px] p-0 sm:p-4">
    <div class="w-full max-w-lg rounded-t-2xl sm:rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xl overflow-hidden">
        <div class="px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="package-plus" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h5 class="modal-title text-sm font-bold text-slate-800 dark:text-slate-100">Tambah Produk</h5>
            </div>
            <button type="button" class="btn-close-modal-produk w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div id="loadmodaleditProduk" class="p-4 max-h-[82vh] overflow-y-auto"></div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        function openModalProduk() {
            $('#modaleditProduk').removeClass('hidden').addClass('flex');
        }

        function closeModalProduk() {
            $('#modaleditProduk').addClass('hidden').removeClass('flex');
        }

        $(document).on('click', '.btn-close-modal-produk', function() {
            closeModalProduk();
        });

        function convertToRupiah(number) {
            if (number) {
                let rupiah = '';
                const numberrev = number.toString().split('').reverse().join('');
                for (let i = 0; i < numberrev.length; i++) {
                    if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + '.';
                }
                return rupiah.split('', rupiah.length - 1).reverse().join('');
            }
            return number;
        }

        function buttonDisable() {
            $('#btnSimpan, #btnSimpanMobile').prop('disabled', true).addClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Loading...</span>
            `);
        }

        function buttonEnable() {
            $('#btnSimpan').prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span>Simpan Retur</span>
            `);
            $('#btnSimpanMobile').prop('disabled', false).removeClass('opacity-75 cursor-not-allowed pointer-events-none').html(`
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                <span>Simpan</span>
            `);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        // V2 Inline Form Validator
        function showFieldError($field, message) {
            $field.addClass('border-rose-500 ring-1 ring-rose-500');
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container').find('.select2-selection').addClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').addClass('text-rose-500');
            $group.find('.field-error-msg').remove();
            $group.append(`
                <p class="field-error-msg text-[11px] font-semibold text-rose-600 dark:text-rose-400 mt-1 pl-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>${message}</span>
                </p>
            `);
        }

        function clearFieldError($field) {
            $field.removeClass('border-rose-500 ring-1 ring-rose-500');
            if ($field.hasClass('select2-hidden-accessible')) {
                $field.next('.select2-container').find('.select2-selection').removeClass('!border-rose-500 !ring-1 !ring-rose-500');
            }
            const $group = $field.closest('.field-group');
            $group.find('.input-prefix-icon').removeClass('text-rose-500');
            $group.find('.field-error-msg').remove();
        }

        $('#formRetur').on('input change', 'input, select', function() {
            clearFieldError($(this));
        });

        $(document).on('input keyup change', '#formAddproduk input, #formAddproduk select', function() {
            clearFieldError($(this));
            if ($(this).is('#jml_pack, #jml_pcs')) {
                clearFieldError($('#formAddproduk #jml_dus'));
            }
        });
        $(document).on('select2:select select2:clear', '#formAddproduk select', function() {
            clearFieldError($(this));
        });

        function getListfaktur(kode_pelanggan) {
            $.ajax({
                type: 'POST',
                url: '/penjualan/getfakturbypelanggan',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_pelanggan: kode_pelanggan
                },
                cache: false,
                success: function(respond) {
                    $('#no_faktur').html(respond);
                }
            });
        }

        function getPelanggan(kode_pelanggan) {
            buttonDisable();
            $.ajax({
                url: `/pelanggan/${kode_pelanggan}/getPelanggan`,
                type: 'GET',
                cache: false,
                success: function(response) {
                    const status_aktif_pelanggan = response.data.status_aktif_pelanggan;
                    if (status_aktif_pelanggan === '0') {
                        buttonEnable();
                        Swal.fire({
                            title: 'Oops!',
                            text: 'Pelanggan Tidak Dapat Bertransaksi, Silahkan Hubungi Admin Untuk Mengaktifkan Pelanggan !',
                            icon: 'warning',
                            showConfirmButton: true
                        });
                    } else {
                        $('#kode_pelanggan').val(response.data.kode_pelanggan);
                        $('#nama_pelanggan').val(response.data.nama_pelanggan);
                        $('#kode_salesman').val(response.data.kode_salesman);
                        $('#nama_salesman').val(response.data.nama_salesman);
                        getListfaktur(response.data.kode_pelanggan);
                        buttonEnable();
                    }
                }
            });
        }

        getPelanggan("{{ Crypt::encrypt($kode_pelanggan) }}");

        function convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah) {
            const jml_dus = Math.floor(jumlah / isi_pcs_dus);
            const sisa_dus = jumlah % isi_pcs_dus;
            let jml_pack = 0;
            let sisa_pack = 0;
            if (isi_pcs_pack !== '0' && isi_pcs_pack != '' && isi_pcs_pack != 0) {
                jml_pack = Math.floor(sisa_dus / isi_pcs_pack);
                sisa_pack = sisa_dus % isi_pcs_pack;
            } else {
                sisa_pack = sisa_dus;
            }
            return { dus: jml_dus, pack: jml_pack, pcs: sisa_pack };
        }

        $('#tambahproduk').on('click', function(e) {
            e.preventDefault();
            const kode_pelanggan = "{{ Crypt::encrypt($kode_pelanggan) }}";
            openModalProduk();
            $('#modaleditProduk').find('.modal-title').text('Tambah Produk');
            $('#loadmodaleditProduk').html('<div class="py-8 text-center text-xs font-semibold text-slate-400">Memuat daftar produk...</div>');
            $('#loadmodaleditProduk').load(`/sfa/retur/${kode_pelanggan}/addproduk`);
        });

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            const key = $(this).attr('key');
            Swal.fire({
                title: 'Apakah Anda Yakin Ingin Menghapus Data Ini ?',
                text: 'Jika dihapus maka data akan hilang permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#284c9a',
                cancelButtonColor: '#e11d48',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`.index_${key}`).remove();
                    loadsubtotal();
                }
            });
        });

        $(document).on('submit', '#formAddproduk', function(event) {
            event.preventDefault();

            const kode_harga = $(this).find('#kode_harga').val();
            const nama_produk = $(this).find('#kode_harga').find(':selected').text();
            const jml_dus = $(this).find('#jml_dus').val();
            const jml_pack = $(this).find('#jml_pack').val();
            const jml_pcs = $(this).find('#jml_pcs').val();
            const harga_dus = $(this).find('#harga_dus').val();
            const harga_pack = $(this).find('#harga_pack').val();
            const harga_pcs = $(this).find('#harga_pcs').val();
            const isi_pcs_dus = $(this).find('#isi_pcs_dus').val();
            const isi_pcs_pack = $(this).find('#isi_pcs_pack').val();
            const status_promosi = 0;

            const jmldus = jml_dus != '' ? parseInt(jml_dus.replace(/\./g, '')) : 0;
            const jmlpack = jml_pack != '' ? parseInt(jml_pack.replace(/\./g, '')) : 0;
            const jmlpcs = jml_pcs != '' ? parseInt(jml_pcs.replace(/\./g, '')) : 0;

            const hargadus = harga_dus != '' ? parseInt(harga_dus.replace(/\./g, '')) : 0;
            const hargapack = harga_pack != '' ? parseInt(harga_pack.replace(/\./g, '')) : 0;
            const hargapcs = harga_pcs != '' ? parseInt(harga_pcs.replace(/\./g, '')) : 0;

            const jumlah = (jmldus * parseInt(isi_pcs_dus || 0)) + (jmlpack * parseInt(isi_pcs_pack || 0)) + jmlpcs;
            const data = convertoduspackpcs(isi_pcs_dus, isi_pcs_pack, jumlah);
            const dus = data.dus;
            const pack = data.pack;
            const pcs = data.pcs;
            const index = kode_harga + status_promosi;

            const subtotal = (parseInt(dus) * parseInt(hargadus)) + (parseInt(pack) * parseInt(hargapack)) + (parseInt(pcs) * parseInt(hargapcs));
            const subtotal_dus = parseInt(dus) * parseInt(hargadus);
            const subtotal_pack = parseInt(pack) * parseInt(hargapack);
            const subtotal_pcs = parseInt(pcs) * parseInt(hargapcs);

            const produk = `
                <tr class="bg-slate-50/90 dark:bg-slate-800/60 index_${index}">
                    <td class="py-1.5 px-3.5 font-bold text-slate-800 dark:text-slate-100">
                        <input type="hidden" name="kode_harga_produk[]" value="${kode_harga}" class="kode_harga"/>
                        <input type="hidden" name="status_promosi_produk[]" class="status_promosi" value="${status_promosi}"/>
                        <input type="hidden" name="jumlah_produk[]" value="${jumlah}"/>
                        <input type="hidden" name="jumlah_dus[]" class="jumlah_dus" value="${dus}"/>
                        <input type="hidden" name="isi_pcs_dus_produk[]" value="${isi_pcs_dus}"/>
                        <input type="hidden" name="isi_pcs_pack_produk[]" value="${isi_pcs_pack}"/>
                        <input type="hidden" name="harga_dus_produk[]" value="${harga_dus}"/>
                        <input type="hidden" name="harga_pack_produk[]" value="${harga_pack}"/>
                        <input type="hidden" name="harga_pcs_produk[]" value="${harga_pcs}"/>
                        <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}"/>
                        <span>${nama_produk}</span>
                    </td>
                    <td class="py-1.5 px-3.5 text-right">
                        <button type="button" key="${index}" class="delete w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 inline-flex items-center justify-center transition cursor-pointer">
                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                        </button>
                    </td>
                </tr>
                ${dus != '' && dus != 0 ? `
                <tr class="index_${index}">
                    <td class="py-1.5 px-3.5 text-slate-600 dark:text-slate-300 font-mono-num">${dus} Dus &times; ${harga_dus}</td>
                    <td class="py-1.5 px-3.5 text-right font-bold text-slate-800 dark:text-slate-100 font-mono-num">${convertToRupiah(subtotal_dus) || 0}</td>
                </tr>` : ''}
                ${pack != '' && pack != 0 ? `
                <tr class="index_${index}">
                    <td class="py-1.5 px-3.5 text-slate-600 dark:text-slate-300 font-mono-num">${pack} Pack &times; ${harga_pack}</td>
                    <td class="py-1.5 px-3.5 text-right font-bold text-slate-800 dark:text-slate-100 font-mono-num">${convertToRupiah(subtotal_pack) || 0}</td>
                </tr>` : ''}
                ${pcs != '' && pcs != 0 ? `
                <tr class="index_${index}">
                    <td class="py-1.5 px-3.5 text-slate-600 dark:text-slate-300 font-mono-num">${pcs} Pcs &times; ${harga_pcs}</td>
                    <td class="py-1.5 px-3.5 text-right font-bold text-slate-800 dark:text-slate-100 font-mono-num">${convertToRupiah(subtotal_pcs) || 0}</td>
                </tr>` : ''}
            `;

            const $kodeHargaField = $(this).find('#kode_harga');
            const $jmlDusField = $(this).find('#jml_dus');

            if (kode_harga == '') {
                showFieldError($kodeHargaField, 'Produk wajib dipilih.');
                $kodeHargaField.focus();
                return false;
            }
            if (jumlah == '' || jumlah == 0) {
                showFieldError($jmlDusField, 'Jumlah produk tidak boleh kosong.');
                $jmlDusField.focus();
                return false;
            }
            if ($('#tabelproduk').find('.index_' + index).length > 0) {
                showFieldError($kodeHargaField, 'Produk ini sudah ada di daftar.');
                $kodeHargaField.focus();
                return false;
            }

            $('#loadproduk').append(produk);
            $('#produk_error_container').addClass('hidden').empty();
            if (typeof lucide !== 'undefined') lucide.createIcons();
            closeModalProduk();
            loadsubtotal();
        });

        function loadsubtotal() {
            let subtotal = 0;
            $('#tabelproduk').find('.subtotal').each(function() {
                const val = parseInt($(this).val());
                subtotal += isNaN(val) ? 0 : val;
            });
            const formatted = convertToRupiah(subtotal) || '0';
            $('#subtotal').text(formatted);
            $('#grandtotal_text').text(formatted);
            $('#grandtotal_mobile_text').text(formatted);
        }

        $('.agreement').on('change', function() {
            if (this.checked) {
                $('#saveButton').removeClass('hidden');
                $('#btnSimpanMobile').show();
            } else {
                $('#saveButton').addClass('hidden');
                $('#btnSimpanMobile').hide();
            }
        });

        $('#btnSimpanMobile').on('click', function() {
            $('#formRetur').trigger('submit');
        });

        $('#formRetur').on('submit', function(e) {
            const $tanggal = $('#tanggal');
            const $nama_pelanggan = $('#nama_pelanggan');
            const $nama_salesman = $('#nama_salesman');
            const $jenis_retur = $('#jenis_retur');
            const $no_faktur = $('#no_faktur');

            let firstInvalid = null;

            if (!$tanggal.val().trim()) {
                showFieldError($tanggal, 'Tanggal wajib diisi.');
                firstInvalid = firstInvalid || $tanggal;
            }
            if (!$('#kode_pelanggan').val()) {
                showFieldError($nama_pelanggan, 'Pelanggan wajib diisi.');
                firstInvalid = firstInvalid || $nama_pelanggan;
            }
            if (!$('#kode_salesman').val()) {
                showFieldError($nama_salesman, 'Salesman wajib diisi.');
                firstInvalid = firstInvalid || $nama_salesman;
            }
            if (!$jenis_retur.val()) {
                showFieldError($jenis_retur, 'Jenis Retur wajib dipilih.');
                firstInvalid = firstInvalid || $jenis_retur;
            }
            if (!$no_faktur.val()) {
                showFieldError($no_faktur, 'No. Faktur wajib dipilih.');
                firstInvalid = firstInvalid || $no_faktur;
            }
            if ($('#loadproduk tr').length == 0) {
                $('#produk_error_container').removeClass('hidden').html(`
                    <p class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>Detail produk retur belum ditambahkan.</span>
                    </p>
                `);
                firstInvalid = firstInvalid || $('#tambahproduk');
            }

            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
                return false;
            }

            buttonDisable();
        });
    });
</script>
@endpush
