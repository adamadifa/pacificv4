<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buku Besar {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    <style>
        .text-red {
            background-color: red;
            color: white;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            BUKU BESAR<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if (!empty($is_lazyload))
            <p style="font-size: 11px; color: #666; margin-top: 4px;">* Tampilan bertahap aktif (Semua Akun). Gulir ke bawah untuk memuat akun berikutnya.</p>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <tbody id="bukubesar-table-body">
                    @if (!empty($is_lazyload))
                        <!-- Baris akan dimuat bertahap via AJAX -->
                    @else
                        @include('accounting.laporan.lk.bukubesar_row_akun', ['bukubesar' => $bukubesar])
                    @endif
                </tbody>
            </table>
        </div>

        @if (!empty($is_lazyload))
            <div id="loading-container" style="text-align: center; padding: 25px 0; margin-top: 15px;">
                <div class="spinner" style="display: inline-block; width: 24px; height: 24px; border: 3px solid #ccc; border-top-color: #284c9a; border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                <div id="loading-text" style="margin-top: 8px; font-weight: bold; color: #555; font-size: 13px;">
                    Memuat data akun... (0 / {{ count($daftar_akun) }})
                </div>
                <button type="button" id="btn-load-all" style="margin-top: 10px; display: none; padding: 6px 14px; font-size: 12px; cursor: pointer; background: #284c9a; color: white; border: none; border-radius: 4px;">
                    Muat Semua Akun Sekaligus
                </button>
            </div>
            <style>
                @keyframes spin {
                    to { transform: rotate(360deg); }
                }
            </style>
            <script>
                $(function() {
                    const daftarAkun = @json($daftar_akun ?? []);
                    const totalAkun = daftarAkun.length;
                    const dari = "{{ $dari }}";
                    const sampai = "{{ $sampai }}";
                    const csrfToken = "{{ csrf_token() }}";
                    const urlGetAkun = "{{ route('laporanaccounting.getbukubesarbyakun') }}";
                    
                    let currentIndex = 0;
                    let isLoading = false;
                    let akunAdaData = 0;
                    let isAutoLoadingAll = false;

                    function loadNextAccount(callback) {
                        if (currentIndex >= totalAkun) {
                            $('#loading-container').html('<div style="color: green; font-weight: bold; padding: 15px;">✓ Semua akun selesai dimuat. (' + akunAdaData + ' akun memiliki transaksi)</div>');
                            return;
                        }

                        if (isLoading && !isAutoLoadingAll) return;
                        isLoading = true;

                        const akun = daftarAkun[currentIndex];
                        $('#loading-text').text('Memuat: ' + akun.kode_akun + ' - ' + akun.nama_akun + ' (' + (currentIndex + 1) + ' dari ' + totalAkun + ' akun)...');

                        $.ajax({
                            url: urlGetAkun,
                            type: 'POST',
                            data: {
                                _token: csrfToken,
                                dari: dari,
                                sampai: sampai,
                                kode_akun: akun.kode_akun
                            },
                            success: function(res) {
                                if (res.status && res.has_data && res.html) {
                                    $('#bukubesar-table-body').append(res.html);
                                    akunAdaData++;
                                }
                                currentIndex++;
                                isLoading = false;

                                if (callback) {
                                    callback();
                                } else if (isAutoLoadingAll) {
                                    loadNextAccount();
                                } else {
                                    // Jika akun ini tidak ada transaksi, langsung lanjut ke akun berikutnya agar layar tidak kosong
                                    if (!res.has_data && currentIndex < totalAkun) {
                                        loadNextAccount();
                                    }
                                }
                            },
                            error: function() {
                                currentIndex++;
                                isLoading = false;
                                if (callback) callback();
                                else if (isAutoLoadingAll) loadNextAccount();
                            }
                        });
                    }

                    // Muat akun pertama secara otomatis
                    loadNextAccount(function() {
                        checkScrollLoad();
                    });

                    // Trigger saat scroll ke bawah
                    function checkScrollLoad() {
                        if (currentIndex >= totalAkun || isLoading || isAutoLoadingAll) return;

                        const scrollPos = $(window).scrollTop() + $(window).height();
                        const docHeight = $(document).height();

                        // Jika mendekati 400px dari dasar halaman
                        if (scrollPos >= docHeight - 400) {
                            loadNextAccount(function() {
                                checkScrollLoad();
                            });
                        }
                    }

                    $(window).on('scroll', checkScrollLoad);

                    // Tombol muat semua akun jika user ingin langsung melihat/mencetak semuanya
                    setTimeout(function() {
                        if (currentIndex < totalAkun) {
                            $('#btn-load-all').show();
                        }
                    }, 1500);

                    $('#btn-load-all').on('click', function() {
                        $(this).prop('disabled', true).text('Sedang memuat semua akun...');
                        isAutoLoadingAll = true;
                        loadNextAccount();
                    });
                });
            </script>
        @endif
    </div>
</body>
</html>
