<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Evaluasi & Durasi Penyelesaian Tiket Ajuan IT</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background-color: #f8fafc;
            padding: 25px;
            line-height: 1.5;
        }

        .report-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            max-width: 1300px;
            margin: 0 auto;
            border: 1px solid #e2e8f0;
        }

        .report-header {
            border-bottom: 2px dashed #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .header-title-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }

        .meta-info-table {
            font-size: 10px;
            color: #475569;
        }

        .meta-info-table td {
            padding: 2px 8px;
        }

        .meta-info-table td:first-child {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 9px;
            color: #64748b;
        }

        /* KPI Style Grid */
        .kpi-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .kpi-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .kpi-card.kpi-total { border-left: 4px solid #4f46e5; background-color: #faf5ff; }
        .kpi-card.kpi-done { border-left: 4px solid #10b981; background-color: #ecfdf5; }
        .kpi-card.kpi-pending { border-left: 4px solid #f59e0b; background-color: #fffbeb; }
        .kpi-card.kpi-rejected { border-left: 4px solid #ef4444; background-color: #fef2f2; }

        .kpi-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 5px;
        }

        .kpi-value {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .kpi-card.kpi-done .kpi-value { color: #065f46; }
        .kpi-card.kpi-pending .kpi-value { color: #92400e; }
        .kpi-card.kpi-rejected .kpi-value { color: #991b1b; }

        /* Table Report styling */
        .table-report {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #cbd5e1;
        }

        .table-report th {
            background-color: #1e1b4b;
            color: #ffffff;
            padding: 10px 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #0f172a;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table-report th:last-child {
            border-right: none;
        }

        .table-report td {
            padding: 10px 12px;
            border-bottom: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            vertical-align: middle;
            color: #334155;
        }

        .table-report td:last-child {
            border-right: none;
        }

        .table-report tr:last-child td {
            border-bottom: none;
        }

        .table-report tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Badges */
        .badge-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 9px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .badge-done {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .badge-pending {
            background-color: #fef9c3;
            color: #a16207;
            border: 1px solid #fef08a;
        }

        .badge-rejected {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .badge-cat {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 9px;
            display: inline-block;
        }

        .badge-priority {
            font-weight: 700;
            font-size: 8px;
            padding: 1px 5px;
            border-radius: 3px;
            text-transform: uppercase;
            display: inline-block;
            margin-top: 3px;
        }

        .priority-Urgent { background: #fef2f2; color: #ef4444; border: 1px solid #fca5a5; }
        .priority-Tinggi { background: #fffbeb; color: #d97706; border: 1px solid #fcd34d; }
        .priority-Sedang { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .priority-Rendah { background: #f8fafc; color: #64748b; border: 1px solid #cbd5e1; }

        /* Signature block */
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border: none;
        }

        .signature-table td {
            border: none;
            width: 33.3%;
            text-align: center;
            vertical-align: top;
            padding: 15px;
            color: #334155;
        }

        .signature-title {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 60px;
        }

        .signature-name {
            font-weight: 700;
            font-size: 11px;
            color: #0f172a;
            text-decoration: underline;
            margin-bottom: 2px;
        }

        .signature-role {
            font-size: 9px;
            color: #64748b;
        }

        @media print {
            @page {
                size: landscape;
                margin: 10mm;
            }

            body {
                background: #ffffff;
                padding: 0;
                color: #000000;
            }

            .report-card {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
                border: none;
            }

            .no-print {
                display: none !important;
            }

            .table-report {
                border: 1px solid #000000;
            }

            .table-report th {
                background-color: #1e1b4b !important;
                color: #ffffff !important;
                border-bottom: 2px solid #000000;
                border-right: 1px solid #000000;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .table-report td {
                border-bottom: 1px solid #000000;
                border-right: 1px solid #000000;
            }

            .kpi-card {
                border: 1px solid #000000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div class="report-card">
        {{-- Header --}}
        <div class="report-header d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">
            <div class="header-title-container">
                <div class="header-logo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-ticket">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M15 5l-10 10a2.22 2.22 0 0 0 1.25 3.814a2.22 2.22 0 0 0 3.75 1.186l10 -10a2.22 2.22 0 0 0 -1.25 -3.814a2.22 2.22 0 0 0 -3.75 -1.186z" />
                        <path d="M9 11l3 3" />
                    </svg>
                </div>
                <div>
                    <h4 class="fw-bold text-uppercase mb-0" style="color: #0f172a; letter-spacing: 1px; font-size: 15px;">PACIFIC SYSTEM HELPDESK</h4>
                    <span class="fw-semibold text-secondary" style="font-size: 11px;">LAPORAN EVALUASI & LAMA PENYELESAIAN TIKET IT</span>
                </div>
            </div>
            <div>
                <table class="meta-info-table">
                    <tr>
                        <td>Periode</td>
                        <td>: {{ request('dari') ? date('d/m/Y', strtotime(request('dari'))) . ' s.d ' . date('d/m/Y', strtotime(request('sampai'))) : 'Semua Periode' }}</td>
                    </tr>
                    <tr>
                        <td>Cabang</td>
                        <td>: {{ request('kode_cabang_search') ? ($cabang->where('kode_cabang', request('kode_cabang_search'))->first()->nama_cabang ?? request('kode_cabang_search')) : 'Semua Cabang' }}</td>
                    </tr>
                    <tr>
                        <td>Kategori</td>
                        <td>: {{ request('id_kategori_search') ? ($categories->where('id', request('id_kategori_search'))->first()->nama_kategori ?? 'Umum') : 'Semua Kategori' }}</td>
                    </tr>
                    <tr class="no-print">
                        <td>Dicetak Pada</td>
                        <td>: {{ date('d/m/Y H:i') }} oleh {{ auth()->user()->name }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Summary Statistics --}}
        <div class="kpi-row">
            <div class="kpi-card kpi-total">
                <span class="kpi-label">Total Tiket</span>
                <h4 class="kpi-value">{{ number_format($tickets->count()) }}</h4>
            </div>
            <div class="kpi-card kpi-done">
                <span class="kpi-label">Selesai (Done)</span>
                <h4 class="kpi-value">{{ number_format($tickets->where('status', '1')->count()) }}</h4>
            </div>
            <div class="kpi-card kpi-pending">
                <span class="kpi-label">Menunggu (Pending)</span>
                <h4 class="kpi-value">{{ number_format($tickets->where('status', '0')->count()) }}</h4>
            </div>
            <div class="kpi-card kpi-rejected">
                <span class="kpi-label">Ditolak</span>
                <h4 class="kpi-value">{{ number_format($tickets->where('status', '2')->count()) }}</h4>
            </div>
        </div>

        {{-- Report Table --}}
        <table class="table-report">
            <thead>
                <tr>
                    <th style="width: 3%" class="text-center">NO</th>
                    <th style="width: 9%">NO. TIKET</th>
                    <th style="width: 8%">TANGGAL</th>
                    <th style="width: 10%">KATEGORI</th>
                    <th>JUDUL & PENGANJUAN</th>
                    <th style="width: 11%">PENGAJU (USER)</th>
                    <th style="width: 5%" class="text-center">CABANG</th>
                    <th style="width: 9%">TGL SELESAI</th>
                    <th style="width: 11%">DISELESAIKAN OLEH</th>
                    <th style="width: 11%">LAMA PROSES</th>
                    <th style="width: 7%" class="text-center">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $d)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="fw-bold" style="font-family: monospace; font-size: 10.5px; color: #1e1b4b;">{{ $d->kode_pengajuan }}</td>
                        <td>{{ date('d/m/Y', strtotime($d->tanggal)) }}</td>
                        <td>
                            <span class="badge-cat">{{ $d->category->nama_kategori ?? 'Umum' }}</span>
                            <span class="badge-priority priority-{{ $d->priority ?? 'Rendah' }}">{{ $d->priority ?? 'LOW' }}</span>
                        </td>
                        <td>
                            <strong class="d-block text-dark" style="font-size: 11.5px; margin-bottom: 2px;">{{ $d->judul }}</strong>
                            @if ($d->no_bukti)
                                <small class="text-muted d-block" style="font-family: monospace; font-size: 9.5px; margin-bottom: 2px;">No. Bukti: {{ $d->no_bukti }}</small>
                            @endif
                            <small class="text-secondary d-block">{{ Str::limit($d->keterangan, 80) }}</small>
                        </td>
                        <td>
                            <strong class="d-block text-dark">{{ formatName2($d->user->name ?? '-') }}</strong>
                            <small class="text-muted" style="font-size: 9.5px; font-weight: 600;">{{ $d->kode_dept }}</small>
                        </td>
                        <td class="text-center fw-bold">{{ $d->kode_cabang }}</td>
                        <td>
                            @if ($d->status == '1')
                                {{ date('d/m/Y H:i', strtotime($d->updated_at)) }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($d->status == '1' && $d->adminUser)
                                <strong class="d-block text-dark">{{ formatName2($d->adminUser->name) }}</strong>
                                <small class="text-muted" style="font-size: 9px;">IT Admin</small>
                            @elseif ($d->status == '2')
                                <small class="text-danger" style="font-weight: 600;">Ditolak</small>
                            @else
                                <span class="text-muted small italic">Menunggu IT</span>
                            @endif
                        </td>
                        <td class="fw-bold text-indigo">
                            {{ $d->lama_penyelesaian }}
                        </td>
                        <td class="text-center">
                            @if ($d->status == '1')
                                <span class="badge-status badge-done">SELESAI</span>
                            @elseif($d->status == '2')
                                <span class="badge-status badge-rejected">DITOLAK</span>
                            @else
                                <span class="badge-status badge-pending">MENUNGGU</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-5 text-muted">Tidak ada data tiket untuk kriteria filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Signatures Section --}}
        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        <div class="signature-title">Dibuat Oleh,</div>
                        <div class="signature-name">{{ auth()->user()->name }}</div>
                        <div class="signature-role">Super Admin</div>
                    </td>
                    <td>
                        <div class="signature-title">Mengetahui,</div>
                        <div class="signature-name">( ____________________ )</div>
                        <div class="signature-role">Head of Department</div>
                    </td>
                    <td>
                        <div class="signature-title">Disetujui Oleh,</div>
                        <div class="signature-name">( ____________________ )</div>
                        <div class="signature-role">IT General Manager</div>
                    </td>
                </tr>
            </table>
        </div>

    </div>

</body>

</html>
