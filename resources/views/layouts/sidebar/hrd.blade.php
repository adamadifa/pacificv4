@if (auth()->user()->hasAnyPermission(['kontrakkerja.index', 'suratperingatan.index', 'jasamasakerja.index', 'kb.index', 'penilaiankaryawan.index']))
    <li
        class="menu-item {{ request()->is(['kontrakkerja', 'suratperingatan', 'jasamasakerja', 'kesepakatanbersama', 'penilaiankaryawan', 'penilaiankaryawan/*']) ? 'open' : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-users-group"></i>
            <div>HRD</div>
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['penilaiankaryawan.index']))
                <li class="menu-item {{ request()->is(['penilaiankaryawan', 'penilaiankaryawan/*']) ? 'active' : '' }}">
                    <a href="{{ route('penilaiankaryawan.index') }}" class="menu-link">
                        <div>Penilaian Karyawan</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['kontrakkerja.index']))
                <li class="menu-item {{ request()->is(['kontrakkerja']) ? 'active' : '' }}">
                    <a href="{{ route('kontrakkerja.index') }}" class="menu-link">
                        <div>Kontrak Kerja</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['suratperingatan.index']))
                <li class="menu-item {{ request()->is(['suratperingatan']) ? 'active' : '' }}">
                    <a href="{{ route('suratperingatan.index') }}" class="menu-link">
                        <div>Surat Peringatan</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['jasamasakerja.index']))
                <li class="menu-item {{ request()->is(['jasamasakerja']) ? 'active' : '' }}">
                    <a href="{{ route('jasamasakerja.index') }}" class="menu-link">
                        <div>Jasa Masa Kerja</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['kb.index']))
                <li class="menu-item {{ request()->is(['kesepakatanbersama']) ? 'active' : '' }}">
                    <a href="{{ route('kesepakatanbersama.index') }}" class="menu-link">
                        <div>Kesepakatan Bersama</div>
                    </a>
                </li>
            @endif


        </ul>
    </li>
@endif
