@if (auth()->user()->hasAnyPermission([
            'pembayarantransfer.index',
            'pembayarangiro.index',
            'setorangiro.index',
            'setoranpusat.index',
            'ajuantransfer.index',
            'kaskecil.index',
            'sakaskecil.index',
            'klaimkaskecil.index',
            'ledger.index',
            'saledger.index',
            'mutasibank.index',
            'pjp.index',
            'kasbon.index',
            'pembayarankasbon.index',
            'piutangkaryawan.index',
            'kontrabonpembelian.index',
            // 'kontrabonangkutan.index',
            'keu.kaskecil',
            'keu.ledger',
            'keu.saldokasbesar',
            'keu.lpu',
            'keu.penjualan',
            'keu.uanglogam',
            'keu.rekapbg',
            'keu.pinjaman',
            'keu.kartupinjaman',
            'keu.kasbon',
            'keu.kartukasbon',
            'keu.piutangkaryawan',
            'keu.kartupiutangkaryawan',
            'keu.rekapkartupiutang',
            'mutasikeuangan.index',
        ]))
    <li
        class="menu-item {{ request()->is([
            'pembayarantransfer',
            'pembayarangiro',
            'setoranpenjualan',
            'setorantransfer',
            'setorangiro',
            'setoranpusat',
            'logamtokertas',
            'sakasbesar',
            'ajuantransfer',
            'kaskecil',
            'sakaskecil',
            'ledger',
            'saledger',
            'mutasibank',
            'samutasibank',
            'pjp',
            'pembayaranpjp',
            'kasbon',
            'pembayarankasbon',
            'piutangkaryawan',
            'kontrabonkeuangan',
            'kontrabonkeuangan/*',
            'laporankeuangan',
            'klaimkaskecil',
            'mutasikeuangan',
        ])
            ? 'open'
            : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-moneybag"></i>
            <div>Keuangan</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['pembayarantransfer.index', 'pembayarangiro.index']))
                <li class="menu-item {{ request()->is(['pembayarantransfer', 'pembayarangiro']) ? 'active' : '' }}">
                    <a href="{{ route('pembayarantransfer.index') }}" class="menu-link">
                        <div>Transfer & Giro</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission([
                        'setoranpenjualan.index',
                        'setorantransfer.index',
                        'setorangiro.index',
                        'setoranpusat.index',
                        'logamtokertas.index',
                        'sakasbesar.index',
                    ]))
                <li
                    class="menu-item {{ request()->is(['setoranpenjualan', 'setorantransfer', 'setorangiro', 'setoranpusat', 'logamtokertas', 'sakasbesar']) ? 'active' : '' }}">
                    <a href="{{ route('sakasbesar.index') }}" class="menu-link">
                        <div>Kas Besar</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['kontrabonpembelian.index']))
                <li class="menu-item {{ request()->is(['kontrabonpembelian', 'kontrabonangkutan']) ? 'active' : '' }}">
                    <a href="{{ route('kontrabonpembelian.index') }}" class="menu-link">
                        <div>Kontrabon</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['ajuantransfer.index']))
                <li class="menu-item {{ request()->is(['ajuantransfer']) ? 'active' : '' }}">
                    <a href="{{ route('ajuantransfer.index') }}" class="menu-link">
                        <div>Ajuan Transfer Dana</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['kaskecil.index', 'klaimkaskecil.index', 'sakaskecil.index']))
                <li class="menu-item {{ request()->is(['kaskecil', 'klaimkaskecil', 'sakaskecil']) ? 'active' : '' }}">
                    <a href="{{ route('kaskecil.index') }}" class="menu-link">
                        <div>Kas Kecil</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['ledger.index', 'saledger.index']))
                <li class="menu-item {{ request()->is(['ledger', 'saledger']) ? 'active' : '' }}">
                    <a href="{{ route('ledger.index') }}" class="menu-link">
                        <div>Ledger</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['mutasibank.index']))
                <li class="menu-item {{ request()->is(['mutasibank', 'samutasibank']) ? 'active' : '' }}">
                    <a href="{{ route('mutasibank.index') }}" class="menu-link">
                        <div>Mutasi Bank</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['pjp.index', 'pembayaranpjp.index']))
                <li class="menu-item {{ request()->is(['pjp', 'pembayaranpjp']) ? 'active' : '' }}">
                    <a href="{{ route('pjp.index') }}" class="menu-link">
                        <div>PJP</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['kasbon.index']))
                <li class="menu-item {{ request()->is(['kasbon', 'pembayarankasbon']) ? 'active' : '' }}">
                    <a href="{{ route('kasbon.index') }}" class="menu-link">
                        <div>Kasbon</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['piutangkaryawan.index']))
                <li class="menu-item {{ request()->is(['piutangkaryawan']) ? 'active' : '' }}">
                    <a href="{{ route('piutangkaryawan.index') }}" class="menu-link">
                        <div>Piutang Karyawan</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['mutasikeuangan.index']))
                <li class="menu-item {{ request()->is(['mutasikeuangan']) ? 'active' : '' }}">
                    <a href="{{ route('mutasikeuangan.index') }}" class="menu-link">
                        <div>Mutasi Keuangan</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission([
                        'keu.kaskecil',
                        'keu.ledger',
                        'keu.saldokasbesar',
                        'keu.lpu',
                        'keu.penjualan',
                        'keu.uanglogam',
                        'keu.rekapbg',
                        'keu.pinjaman',
                        'keu.kartupinjaman',
                        'keu.kasbon',
                        'keu.kartukasbon',
                        'keu.piutangkaryawan',
                        'keu.kartupiutangkaryawan',
                        'keu.rekapkartupiutang',
                        'keu.mutasikeuangan',
                    ]))
                <li class="menu-item {{ request()->is(['laporankeuangan']) ? 'active' : '' }}">
                    <a href="{{ route('laporankeuangan.index') }}" class="menu-link">
                        <div>Laporan</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
