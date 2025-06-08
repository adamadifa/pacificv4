@if (auth()->user()->hasAnyPermission([
            'kontrakkerja.index',
            'suratperingatan.index',
            'jasamasakerja.index',
            'kb.index',
            'penilaiankaryawan.index',
            'jadwalshift.index',
            'harilibur.index',
            'lembur.index',
            'izinabsen.index',
            'izincuti.index',
            'izinsakit.index',
            'izinpulang.index',
            'izinkoreksi.index',
            'izindinas.index',
            'izinterlambat.index',
            'presensi.index',
            'presensi.presensikaryawan',
            'slipgaji.index',
            'penyupah.index',
            'resign.index'
        ]))
    <li
        class="menu-item {{ request()->is([
            'kontrakkerja',
            'suratperingatan',
            'jasamasakerja',
            'kesepakatanbersama',
            'penilaiankaryawan',
            'penilaiankaryawan/*',
            'jadwalshift',
            'jadwalshift/*',
            'harilibur',
            'harilibur/*',
            'lembur',
            'lembur/*',
            'izinabsen',
            'izinsakit',
            'izincuti',
            'izinpulang',
            'izindinas',
            'izinterlambat',
            'presensi',
            'presensikaryawan',
            'slipgaji',
            'penyupah',
            'resign'
        ])
            ? 'open'
            : '' }}">

        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-users-group"></i>
            <div>HRD</div>
            @if (!empty($notifikasi_hrd))
                <div class="badge bg-danger rounded-pill ms-auto">{{ $notifikasi_hrd }}</div>
            @endif
        </a>
        <ul class="menu-sub">
            @if (auth()->user()->hasAnyPermission(['penilaiankaryawan.index']))
                <li class="menu-item {{ request()->is(['penilaiankaryawan', 'penilaiankaryawan/*']) ? 'active' : '' }}">
                    <a href="{{ route('penilaiankaryawan.index') }}" class="menu-link">
                        <div>Penilaian Karyawan</div>
                        @if (!empty($notifikasi_penilaiankaryawan))
                            <div class="badge bg-danger rounded-pill ms-auto">{{ $notifikasi_penilaiankaryawan }}</div>
                        @endif
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

            @if (auth()->user()->hasAnyPermission(['resign.index']))
                <li class="menu-item {{ request()->is(['resign', 'resign/*']) ? 'active' : '' }}">
                    <a href="{{ route('resign.index') }}" class="menu-link">
                        <div>Resign</div>
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
            @if (auth()->user()->hasAnyPermission(['presensi.index']))
                <li class="menu-item {{ request()->is(['presensi', 'presensi/*']) ? 'active' : '' }}">
                    <a href="{{ route('presensi.index') }}" class="menu-link">
                        <div>Monitoring Presensi</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['presensi.index']))
                <li class="menu-item {{ request()->is(['presensikaryawan']) ? 'active' : '' }}">
                    <a href="{{ route('presensi.presensikaryawan') }}" class="menu-link">
                        <div>Presensi Karyawan</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['jadwalshift.index']))
                <li class="menu-item {{ request()->is(['jadwalshift', 'jadwalshift/*']) ? 'active' : '' }}">
                    <a href="{{ route('jadwalshift.index') }}" class="menu-link">
                        <div>Jadwal Shift</div>
                    </a>
                </li>
            @endif


            @if (auth()->user()->hasAnyPermission(['harilibur.index']))
                <li class="menu-item {{ request()->is(['harilibur', 'harilibur/*']) ? 'active' : '' }}">
                    <a href="{{ route('harilibur.index') }}" class="menu-link">
                        <div>Hari Libur</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['lembur.index']))
                <li class="menu-item {{ request()->is(['lembur', 'lembur/*']) ? 'active' : '' }}">
                    <a href="{{ route('lembur.index') }}" class="menu-link">
                        <div>Lembur</div>
                        @if (!empty($notifikasi_lembur))
                            <div class="badge bg-danger rounded-pill ms-auto">{{ $notifikasi_lembur }}</div>
                        @endif
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['izinabsen.index']))
                <li class="menu-item {{ request()->is(['izinabsen', 'izinabsen/*']) ? 'active' : '' }}">
                    <a href="{{ route('izinabsen.index') }}" class="menu-link">
                        <div>Pengajuan Izin</div>
                        @if (!empty($notifikasi_pengajuan_izin))
                            <div class="badge bg-danger rounded-pill ms-auto">{{ $notifikasi_pengajuan_izin }}</div>
                        @endif
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['slipgaji.index']))
                <li class="menu-item {{ request()->is(['slipgaji', 'slipgaji/*']) ? 'active' : '' }}">
                    <a href="{{ route('slipgaji.index') }}" class="menu-link">
                        <div>Slip Gaji</div>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasAnyPermission(['penyupah.index']))
                <li class="menu-item {{ request()->is(['penyupah', 'penyupah/*']) ? 'active' : '' }}">
                    <a href="{{ route('penyupah.index') }}" class="menu-link">
                        <div>Penyesuaian Upah</div>
                    </a>
                </li>
            @endif
            @if (auth()->user()->hasAnyPermission(['hrd.presensi', 'hrd.psm', 'hrd.gaji', 'hrd.slipgaji']))
                <li class="menu-item {{ request()->is(['laporanhrd', 'laporanhrd/*']) ? 'active' : '' }}">
                    <a href="{{ route('laporanhrd.index') }}" class="menu-link">
                        <div>Laporan</div>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
