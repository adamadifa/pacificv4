<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Owner Dashboard | PORTAL CV. Makmur Permata</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F8FAFC;
            color: #1E293B;
            min-height: 100vh;
        }

        .header-bg {
            background-color: #0F172A;
            height: 280px;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 0;
        }

        .balance-card {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.2);
            position: relative;
            overflow: hidden;
        }

        .balance-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .glass-icon {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .tab-active {
            color: #2563EB;
            position: relative;
        }

        .tab-active::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background-color: #2563EB;
            border-radius: 50%;
        }

        .flatpickr-calendar {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            border: none !important;
            border-radius: 12px !important;
        }

        .bottom-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-top: 1px solid #E2E8F0;
        }
    </style>
</head>

<body class="pb-32">
    <!-- Header Background -->
    <div class="header-bg"></div>

    <!-- Top Bar -->
    <div class="relative z-10 px-6 pt-6 flex justify-between items-center text-white">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full border border-white/20 overflow-hidden shadow-sm">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2563eb&color=fff" alt="Profile">
            </div>
            <div>
                <p class="text-[10px] text-white/60 uppercase tracking-widest font-semibold">Selamat Datang</p>
                <h2 class="text-sm font-bold">{{ explode(' ', auth()->user()->name)[0] }} {{ explode(' ', auth()->user()->name)[1] ?? '' }}</h2>
            </div>
        </div>
        <div class="flex space-x-3">
            <button class="w-10 h-10 rounded-full glass-icon flex items-center justify-center">
                <i class="ti ti-bell text-lg"></i>
            </button>
            <button class="w-10 h-10 rounded-full glass-icon flex items-center justify-center">
                <i class="ti ti-message-dots text-lg"></i>
            </button>
        </div>
    </div>

    <!-- Content Area -->
    <main class="relative z-10 px-6 pt-8">
        <!-- Balance Card -->
        <div class="balance-card p-6 text-white mb-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-xs text-white/70 font-medium mb-1">Total Saldo</p>
                    <h1 class="text-3xl font-bold tracking-tight">Rp {{ formatAngka($total_saldo_awal) }}</h1>
                </div>
                <div class="flex flex-col items-end">
                    <img src="{{ asset('assets/img/logo/logoportal64.png') }}" class="h-10 rounded-sm mb-2" style="filter: brightness(0) invert(1);">
                    <i class="ti ti-chevron-right text-white/50"></i>
                </div>
            </div>
            <div class="flex justify-between items-end">
                <div>
                    <p class="text-[10px] text-white/50 uppercase tracking-widest mb-1">Periode</p>
                    <p class="text-xs font-bold">{{ date('d M Y', strtotime($tanggal)) }}</p>
                </div>
                <a href="#" class="bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all">
                    Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Date Filter Section -->
        <div class="mb-10 px-1">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="ti ti-calendar-stats text-xl"></i>
                    </div>
                </div>
                <input type="text" id="tanggal_filter_display" 
                    class="block w-full pl-16 pr-4 py-5 bg-white border-none rounded-[28px] shadow-sm text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer"
                    value="{{ date('d F Y', strtotime($tanggal)) }}"
                    readonly>
                <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none">
                    <i class="ti ti-chevron-right text-slate-300"></i>
                </div>
            </div>
        </div>

        <!-- Rekap Rekening -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-800">Rekap Rekening</h3>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between cursor-pointer btn-detail-saldoawal">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="ti ti-wallet text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Saldo</p>
                        </div>
                    </div>
                    <p class="text-xs font-bold text-blue-600">Rp {{ formatAngka($saldo_awal) }}</p>
                </div>
                <div class="flex items-center justify-between cursor-pointer btn-detail-mutasi" data-jenis="K">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="ti ti-arrow-up-right text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Penerimaan</p>
                        </div>
                    </div>
                    <p class="text-xs font-bold text-emerald-600">+ Rp {{ formatAngka($penerimaan) }}</p>
                </div>
                <div class="flex items-center justify-between cursor-pointer btn-detail-mutasi" data-jenis="D">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-600">
                            <i class="ti ti-arrow-down-left text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Pengeluaran</p>
                        </div>
                    </div>
                    <p class="text-xs font-bold text-rose-600">- Rp {{ formatAngka($pengeluaran) }}</p>
                </div>
                <div class="pt-4 border-t border-slate-50 flex items-center justify-between">
                    <p class="text-xs font-bold text-slate-400">Net. (Saldo Akhir)</p>
                    <p class="text-sm font-black text-blue-600">Rp {{ formatAngka($net) }}</p>
                </div>
            </div>
        </div>

        <!-- Rekap Kas Besar -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-800">Rekap Kas Besar</h3>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between cursor-pointer btn-detail-saldoawal-kb">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="ti ti-wallet text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Saldo</p>
                        </div>
                    </div>
                    <p class="text-xs font-bold text-blue-600">Rp {{ formatAngka($saldo_awal_kb) }}</p>
                </div>
                <div class="flex items-center justify-between cursor-pointer btn-detail-mutasi-kb" data-jenis="K">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="ti ti-arrow-up-right text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Penerimaan</p>
                        </div>
                    </div>
                    <p class="text-xs font-bold text-emerald-600">+ Rp {{ formatAngka($penerimaan_kb) }}</p>
                </div>
                <div class="flex items-center justify-between cursor-pointer btn-detail-mutasi-kb" data-jenis="D">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-600">
                            <i class="ti ti-arrow-down-left text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Pengeluaran</p>
                        </div>
                    </div>
                    <p class="text-xs font-bold text-rose-600">- Rp {{ formatAngka($pengeluaran_kb) }}</p>
                </div>
                <div class="pt-4 border-t border-slate-50 flex items-center justify-between">
                    <p class="text-xs font-bold text-slate-400">Net. (Saldo Akhir)</p>
                    <p class="text-sm font-black text-blue-600">Rp {{ formatAngka($net_kb) }}</p>
                </div>
            </div>
        </div>

        <!-- Bank History List -->
        @if(isset($bank))
        <div class="flex justify-between items-center mb-4 px-2">
            <h3 class="font-bold text-slate-800">Rincian Per Bank</h3>
            <a href="#" class="text-xs font-bold text-blue-600">Lihat Semua</a>
        </div>
        <div class="space-y-3">
            @foreach($bank as $b)
            <div class="bg-white rounded-2xl p-4 flex items-center justify-between shadow-sm border border-slate-50">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100">
                        @php
                            $iconBank = 'ti-building-bank';
                            $bankName = strtolower($b->nama_bank);
                            if(str_contains($bankName, 'bca')) $iconBank = 'ti-circle-letter-b';
                            elseif(str_contains($bankName, 'mandiri')) $iconBank = 'ti-circle-letter-m';
                            elseif(str_contains($bankName, 'bri')) $iconBank = 'ti-circle-letter-b';
                            elseif(str_contains($bankName, 'bni')) $iconBank = 'ti-circle-letter-b';
                        @endphp
                        <i class="ti {{ $iconBank }} text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">{{ $b->nama_bank }}</h4>
                        <p class="text-[10px] text-slate-400">{{ $b->no_rekening }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-slate-800">Rp {{ formatAngka($b->saldo) }}</p>
                    <div class="flex items-center justify-end space-x-1 mt-0.5">
                        <span class="text-[9px] font-bold text-emerald-500">+{{ formatAngka($b->rekap_kredit) }}</span>
                        <span class="text-[9px] font-bold text-rose-500">-{{ formatAngka($b->rekap_debet) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </main>

    {{-- Modal Detail --}}
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-centered">
            <div class="modal-content rounded-[32px] border-0 overflow-hidden">
                <div class="modal-header border-0 px-6 pt-6">
                    <h5 class="modal-title font-bold text-slate-800" id="modalTitle">Detail Keuangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-6 pb-6 bg-slate-50" id="modalBody">
                    <div class="text-center py-8">
                        <div class="spinner-border text-blue-600" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 z-50 bottom-nav px-8 py-4 pb-8 flex justify-between items-center rounded-t-[32px]">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center tab-active">
            <i class="ti ti-smart-home text-xl mb-1"></i>
            <span class="text-[10px] font-bold uppercase">Beranda</span>
        </a>
        <a href="#" class="flex flex-col items-center text-slate-400">
            <i class="ti ti-chart-pie text-xl mb-1"></i>
            <span class="text-[10px] font-bold uppercase">Statistik</span>
        </a>
        <div class="relative -mt-12">
            <button class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg shadow-blue-600/30 border-4 border-white">
                <i class="ti ti-scan text-2xl"></i>
            </button>
        </div>
        <a href="#" class="flex flex-col items-center text-slate-400">
            <i class="ti ti-wallet text-xl mb-1"></i>
            <span class="text-[10px] font-bold uppercase">Dompet</span>
        </a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex flex-col items-center text-slate-400">
            <i class="ti ti-user text-xl mb-1"></i>
            <span class="text-[10px] font-bold uppercase">Profil</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fp = flatpickr("#tanggal_filter_display", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ $tanggal }}",
                altInput: true,
                altFormat: "d F Y",
                onChange: function(selectedDates, dateStr) {
                    window.location.href = "{{ URL::current() }}?dari=" + dateStr + "&sampai=" + dateStr;
                }
            });

            // Detail Saldo Awal Rekening
            $(".btn-detail-saldoawal").click(function() {
                let tanggal = "{{ $tanggal }}";
                $("#modalTitle").text("Detail Saldo Rekening (" + tanggal + ")");
                $("#modalBody").html('<div class="text-center py-8"><div class="spinner-border text-blue-600" role="status"></div></div>');
                $("#modalDetail").modal("show");

                $.ajax({
                    url: "{{ route('dashboard.getdetailsaldoawal') }}",
                    type: "GET",
                    data: { tanggal: tanggal },
                    success: function(response) {
                        $("#modalBody").html(response);
                    }
                });
            });

            // Detail Mutasi Rekening
            $(".btn-detail-mutasi").click(function() {
                let tanggal = "{{ $tanggal }}";
                let jenis = $(this).data("jenis");
                let title = jenis == "K" ? "Penerimaan Rekening" : "Pengeluaran Rekening";
                
                $("#modalTitle").text(title + " (" + tanggal + ")");
                $("#modalBody").html('<div class="text-center py-8"><div class="spinner-border text-blue-600" role="status"></div></div>');
                $("#modalDetail").modal("show");

                $.ajax({
                    url: "{{ route('dashboard.getdetailmutasi') }}",
                    type: "GET",
                    data: { 
                        tanggal: tanggal,
                        jenis: jenis
                    },
                    success: function(response) {
                        $("#modalBody").html(response);
                    }
                });
            });

            // Detail Saldo Kas Besar
            $(".btn-detail-saldoawal-kb").click(function() {
                let tanggal = "{{ $tanggal }}";
                $("#modalTitle").text("Detail Saldo Kas Besar (" + tanggal + ")");
                $("#modalBody").html('<div class="text-center py-8"><div class="spinner-border text-blue-600" role="status"></div></div>');
                $("#modalDetail").modal("show");

                $.ajax({
                    url: "{{ route('dashboard.getdetailsaldoawalkb') }}",
                    type: "GET",
                    data: { tanggal: tanggal },
                    success: function(response) {
                        $("#modalBody").html(response);
                    }
                });
            });

            // Detail Mutasi Kas Besar
            $(".btn-detail-mutasi-kb").click(function() {
                let tanggal = "{{ $tanggal }}";
                let jenis = $(this).data("jenis");
                let title = jenis == "K" ? "Penerimaan Kas Besar" : "Pengeluaran Kas Besar";
                
                $("#modalTitle").text(title + " (" + tanggal + ")");
                $("#modalBody").html('<div class="text-center py-8"><div class="spinner-border text-blue-600" role="status"></div></div>');
                $("#modalDetail").modal("show");

                $.ajax({
                    url: "{{ route('dashboard.getdetailmutasikb') }}",
                    type: "GET",
                    data: { 
                        tanggal: tanggal,
                        jenis: jenis
                    },
                    success: function(response) {
                        $("#modalBody").html(response);
                    }
                });
            });
        });
    </script>
</body>

</html>
