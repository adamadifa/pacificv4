<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Neraca {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    <style>
        .text-red {
            background-color: red;
            color: white;
        }

        .subtotal-row {
            background-color: #f0f0f0;
            font-weight: bold;
            border-top: 2px solid #333;
            border-bottom: 1px solid #666;
        }

        .subtotal-row td {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            NERACA<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        <div id="alert-container"></div>
        <form id="formLockNeraca">
            @csrf
            <input type="hidden" name="dari" value="{{ $dari }}">
            <input type="hidden" name="sampai" value="{{ $sampai }}">
            <input type="hidden" name="formatlaporan" value="{{ request('formatlaporan', '2') }}">
            <input type="hidden" name="kode_akun_dari" value="{{ request('kode_akun_dari') }}">
            <input type="hidden" name="kode_akun_sampai" value="{{ request('kode_akun_sampai') }}">
            <button type="button" id="btnLock" class="btn btn-primary">Kunci Laporan</button>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable9">
                <thead>
                    <tr>
                        <th style="font-size:12; text-align:left !important">NAMA AKUN</th>
                        <th style="font-size:12;">SALDO</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // $lastLevel2 = null;
                        // $subtotalAmount = 0;
                        // $level2Items = [];
                        // $currentLevel2Name = '';
                        $index_item = 0;
                        $subtotal_level_0 = 0;
                        $level_0_name = '';

                        $subtotal_level_1 = 0;
                        $level_1_name = '';

                        $subtotal_level_2 = 0;
                        $level_2_name = '';

                        $kode_akun_kas_bank = ['1-11', '1-12'];
                        $subtotal_akun_kas_bank = 0;

                        $kode_akun_hutang = 2;
                        $subtotal_akun_hutang = 0;

                        $kode_akun_modal = 3;
                        $subtotal_akun_modal = 0;
                    @endphp

                    @foreach ($neraca as $index => $d)
                        @php
                            $indent = ($d->level ?? 0) * 20;
                            $next_level = $neraca[$index + 1]->level ?? null;
                            $next_before_level = $neraca[$index - 1]->level ?? null;

                            $next_kode_akun = $neraca[$index + 1]->kode_akun ?? null;

                            if ($d->kode_akun == '3-2000') {
                                $saldo_akhir = $d->saldo_akhir + $net_profit_loss;
                            } else {
                                $saldo_akhir = $d->saldo_akhir;
                            }
                            //Level 0
                            if ($d->level == 0) {
                                $level_0_name = $d->nama_akun;
                            }

                            $subtotal_level_0 += $saldo_akhir;

                            //Level 1

                            if ($d->level == 1) {
                                $level_1_name = $d->nama_akun;
                            }

                            $subtotal_level_1 += $saldo_akhir;

                            //Level 2
                            if ($d->level == 2) {
                                $level_2_name = $d->nama_akun;
                            }

                            $subtotal_level_2 += $saldo_akhir;

                            //echo $level_0_name;

                            if (in_array(substr($d->kode_akun, 0, 4), $kode_akun_kas_bank)) {
                                $subtotal_akun_kas_bank += $saldo_akhir;
                            }

                            if (substr($d->kode_akun, 0, 1) == $kode_akun_hutang) {
                                $subtotal_akun_hutang += $saldo_akhir;
                            }

                            if (substr($d->kode_akun, 0, 1) == $kode_akun_modal) {
                                $subtotal_akun_modal += $saldo_akhir;
                            }

                        @endphp
                        @if (
                            ($saldo_akhir == 0 && $d->level == 1 && $next_level == 1) ||
                                ($saldo_akhir == 0 && $d->level == 2 && $next_level == 2) ||
                                ($saldo_akhir == 0 && $d->level == 1 && $next_level == 0) ||
                                ($saldo_akhir == 0 && $d->level == 3))
                        @else
                            <!-- Tampilkan item -->
                            <tr>
                                <td style="padding-left: {{ $indent }}px;">
                                    <input type="hidden" name="details[{{ $index_item }}][kode_akun]" value="{{ $d->kode_akun }}">
                                    <input type="hidden" name="details[{{ $index_item }}][jumlah]" value="{{ $saldo_akhir ?? 0 }}">
                                    @php $index_item++; @endphp

                                    @if ($d->level == 0 || $d->level == 1 || $d->level == 2)
                                        <b>{{ $d->kode_akun }} {{ $d->nama_akun }}</b>
                                    @else
                                        {{ $d->kode_akun }} {{ $d->nama_akun }}
                                    @endif

                                    {{-- {{ $d->saldo_akhir . '+' . $net_profit_loss }} --}}
                                    {{-- {{ $d->level }} - {{ $next_level }} --}}
                                </td>
                                <td style="text-align: right;">
                                    {{--
                                    Variabel $laba_rugi undefined karena di Blade, assignment variabel dengan @if ... @else ... @endif tidak akan menyimpan nilai ke variabel PHP seperti di kode biasa.
                                    Solusi: gunakan @php ... @endphp untuk assignment, lalu tampilkan nilainya.
                                --}}


                                    @if ($d->level == 0 || $d->level == 1)
                                        <b>{{ formatAngka($saldo_akhir) }}</b>
                                    @else
                                        {{ formatAngka($saldo_akhir) }}
                                    @endif
                                </td>
                            </tr>
                        @endif

                        <!-- Jika Next Level 2 dan Next Before Level bukan 1 dan Level bukan 1 atau Next Level 1 -->
                        {{-- ($next_level == 2 && $next_before_level != 1 && $d->level != 1) ||
                                ($next_level == 2 && $next_before_level == 1 && $d->level == 2) ||
                                ($next_level == 1 && $next_before_level == 3 && $d->level != 0) ||
                                ($next_level == 1 && $next_before_level == 2 && $d->level != 1) ||
                                ($next_level == 0 && $d->level != 1) --}}
                        @if (
                            ($subtotal_level_2 != 0 && $next_level == 2 && $d->level == 2) ||
                                ($subtotal_level_2 != 0 && $next_level == 2 && $d->level == 3) ||
                                ($subtotal_level_2 != 0 && $next_level == 1 && $d->level == 3) ||
                                ($subtotal_level_2 != 0 && $next_level == 1 && $d->level == 2) ||
                                ($subtotal_level_2 != 0 && $next_level == 0 && $d->level == 3))
                            <tr class="subtotal-row">
                                <td style="padding-left:40px;">
                                    <b>SUBTOTAL {{ strtoupper($level_2_name) }}</b>
                                </td>
                                <td style="text-align: right;">
                                    <b>{{ formatAngka($subtotal_level_2) }}</b>
                                </td>
                            </tr>
                            @php
                                $subtotal_level_2 = 0;
                                $level_2_name = '';
                            @endphp
                        @endif

                        <!-- Jika Next Level 1 dan Next Before Level bukan 0 dan Level bukan 0 atau Next Level 0 -->
                        @if (
                            ($subtotal_level_1 != 0 && $next_level == 1 && $d->level == 3) ||
                                ($subtotal_level_1 != 0 && $next_level == 0 && $d->level == 3) ||
                                ($subtotal_level_1 != 0 && $next_level == 1 && $d->level == 2) ||
                                ($subtotal_level_1 != 0 && $next_level == 1 && $d->level == 1) ||
                                ($subtotal_level_1 != 0 && $next_level == 0 && $d->level == 1) ||
                                ($subtotal_level_1 != 0 && $next_level == 0 && $d->level == 2))
                            <tr class="subtotal-row">
                                <td style="padding-left:20px;">
                                    <b>SUBTOTAL {{ strtoupper($level_1_name) }}</b>
                                </td>
                                <td style="text-align: right;">
                                    <b>{{ formatAngka($subtotal_level_1) }}</b>
                                </td>
                            </tr>
                            @php
                                $subtotal_level_1 = 0;
                                $level_1_name = '';
                            @endphp
                        @endif


                        @if ($next_level == 0)
                            <tr class="subtotal-row">
                                <td>
                                    <b>SUBTOTAL {{ strtoupper($level_0_name) }}</b>
                                </td>
                                <td style="text-align: right;">
                                    <b>{{ formatAngka($subtotal_level_0) }}</b>
                                </td>
                            </tr>
                            @php
                                $subtotal_level_0 = 0;
                                $level_0_name = '';
                            @endphp
                        @endif

                        @if (!in_array(substr($next_kode_akun, 0, 4), $kode_akun_kas_bank) && in_array(substr($d->kode_akun, 0, 4), $kode_akun_kas_bank))
                            <tr class="subtotal-row">
                                <td>
                                    <b>SUBTOTAL KAS BANK</b>
                                </td>
                                <td style="text-align: right;">
                                    <b>{{ formatAngka($subtotal_akun_kas_bank) }}</b>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr class="subtotal-row">
                        <td>
                            <b>TOTAL PASIVA</b>
                        </td>
                        <td style="text-align: right;">
                            @php
                                $total_pasiva = $subtotal_akun_hutang + $subtotal_akun_modal;
                            @endphp
                            <b>{{ formatAngka($total_pasiva) }}</b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        </form>
    </div>

    <!-- Script AJAX -->
    <script>
        $(document).ready(function() {
            $('#btnLock').click(function(e) {
                e.preventDefault();
                
                if (!confirm('Apakah anda yakin ingin mengunci laporan ini?')) {
                    return;
                }

                var btn = $(this);
                var originalText = btn.html();
                
                // Disable button and show loading state
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Proses...');

                $.ajax({
                    url: "{{ route('laporanaccounting.lockneraca') }}",
                    type: "POST",
                    data: $('#formLockNeraca').serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $('#alert-container').html(`
                                <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 10px; border: 1px solid #c3e6cb; border-radius: 4px; text-align: center;">
                                    ${response.message}
                                </div>
                            `);
                        } else {
                            $('#alert-container').html(`
                                <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 10px; border: 1px solid #f5c6cb; border-radius: 4px; text-align: center;">
                                    ${response.message}
                                </div>
                            `);
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = "Terjadi kesalahan saat mengunci laporan.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        $('#alert-container').html(`
                            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 10px; border: 1px solid #f5c6cb; border-radius: 4px; text-align: center;">
                                ${errorMessage}
                            </div>
                        `);
                    },
                    complete: function() {
                        // Restore button state
                        btn.prop('disabled', false).html(originalText);
                        
                        // Scroll to top to see alert
                        $('html, body').animate({ scrollTop: 0 }, 'fast');
                    }
                });
            });
        });
    </script>
</body>

</html>
