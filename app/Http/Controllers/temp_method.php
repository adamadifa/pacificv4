    private function getFinancialDataQuery($dari, $sampai, $kode_akun_dari = null, $kode_akun_sampai = null)
    {
        $request = new \stdClass();
        $request->dari = $dari;
        $request->sampai = $sampai;
        $request->kode_akun_dari = $kode_akun_dari;
        $request->kode_akun_sampai = $kode_akun_sampai;
        $bulan = !empty($request->dari) ? date('m', strtotime($request->dari)) : '';
        $tahun = !empty($request->dari) ? date('Y', strtotime($request->dari)) : '';
        $start_date = $tahun . "-" . $bulan . "-01";



        $saldoawal = Detailsaldoawalbukubesar::query();

        $saldoawal->join('bukubesar_saldoawal', 'bukubesar_saldoawal.kode_saldo_awal', '=', 'bukubesar_saldoawal_detail.kode_saldo_awal');
        $saldoawal->join('coa', 'bukubesar_saldoawal_detail.kode_akun', '=', 'coa.kode_akun');
        $saldoawal->select(
            'bukubesar_saldoawal_detail.kode_akun',
            'coa.jenis_akun',
            'nama_akun',

            // Set tanggal 1 pada bulan yang dipilih sebagai default tanggal
            DB::raw("CONCAT('$tahun-$bulan-01') as tanggal"),
            'bukubesar_saldoawal_detail.kode_saldo_awal as no_bukti',
            DB::raw("'SALDO AWAL' AS sumber"),
            DB::raw("'Saldo Awal' as keterangan"),
            // 'bukubesar_saldoawal_detail.jumlah as jml_kredit',


            DB::raw('IF(coa.jenis_akun ="1",bukubesar_saldoawal_detail.jumlah,0) as jml_kredit'),
            DB::raw('IF(coa.jenis_akun !="1" || coa.jenis_akun IS NULL,bukubesar_saldoawal_detail.jumlah,0) as jml_debet'),
            DB::raw('0 as urutan')
        );
        $saldoawal->where('bukubesar_saldoawal.bulan', $bulan);
        $saldoawal->where('bukubesar_saldoawal.tahun', $tahun);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $saldoawal->whereBetween('bukubesar_saldoawal_detail.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $saldoawal->orderBy('bukubesar_saldoawal_detail.kode_akun');


        // ->get()->toArray();
        // Mengubah $saldo_awal_ledger menjadi koleksi
        $saldoawalCollection = collect($saldoawal);
        // dd($saldoawalCollection);
        //Ledger BANK
        $ledger = Ledger::query();
        $ledger->select(
            'bank.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'keuangan_ledger.tanggal',
            'keuangan_ledger.no_bukti',
            DB::raw('CONCAT_WS(" - ", bank.nama_bank, bank.no_rekening) AS sumber'),
            'keuangan_ledger.keterangan',
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_debet'),
            DB::raw('IF(coa.jenis_akun="1" AND debet_kredit="D",1,2) as urutan')
        );
        $ledger->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
        $ledger->join('coa', 'bank.kode_akun', '=', 'coa.kode_akun');


        $ledger->whereBetween('keuangan_ledger.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $ledger->whereBetween('bank.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $ledger->orderBy('bank.kode_akun');
        $ledger->orderBy('tanggal');
        $ledger->orderBy('keuangan_ledger.no_bukti');


        $ledger_transaksi = Ledger::query();
        $ledger_transaksi->select(
            'keuangan_ledger.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'keuangan_ledger.tanggal',
            'keuangan_ledger.no_bukti',
            DB::raw('CONCAT_WS(" - ", bank.nama_bank, bank.no_rekening) AS sumber'),
            'keuangan_ledger.keterangan',
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_debet'),
            DB::raw('IF((coa.jenis_akun = "1" AND debet_kredit = "K") OR ((coa.jenis_akun = "1" OR coa.jenis_akun IS NULL) AND debet_kredit = "D"), 1, 2) as urutan')
        );
        $ledger_transaksi->whereBetween('keuangan_ledger.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $ledger_transaksi->whereBetween('keuangan_ledger.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $ledger_transaksi->join('coa', 'keuangan_ledger.kode_akun', '=', 'coa.kode_akun');
        $ledger_transaksi->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
        $ledger_transaksi->orderBy('keuangan_ledger.kode_akun');
        $ledger_transaksi->orderBy('keuangan_ledger.tanggal');
        $ledger_transaksi->orderBy('keuangan_ledger.no_bukti');


        //Pembelian

        $pembelian = Detailpembelian::query();
        $pembelian->select(
            'pembelian_detail.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'pembelian.tanggal',
            'pembelian.no_bukti',
            DB::raw("'PEMBELIAN' AS sumber"),
            DB::raw('IF(pembelian_detail.kode_transaksi="PNJ",pembelian_detail.keterangan_penjualan,CONCAT(pembelian_barang.nama_barang, " - ", COALESCE(pembelian_detail.keterangan, ""))) as keterangan'),
            DB::raw('IF(pembelian_detail.kode_transaksi="PNJ",pembelian_detail.jumlah * harga + penyesuaian,0) as jml_kredit'),
            DB::raw('IF(pembelian_detail.kode_transaksi="PMB",pembelian_detail.jumlah * harga + penyesuaian,0) as jml_debet'),
            DB::raw('IF(pembelian_detail.kode_transaksi="PMB",2,1) as urutan')
        );
        $pembelian->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        $pembelian->join('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $pembelian->join('coa', 'pembelian_detail.kode_akun', '=', 'coa.kode_akun');
        $pembelian->whereBetween('pembelian.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $pembelian->whereBetween('pembelian_detail.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $pembelian->orderBy('pembelian_detail.kode_akun');
        $pembelian->orderBy('pembelian.tanggal');
        $pembelian->orderBy('pembelian.no_bukti');


        //JURNAL UMUM

        $jurnalumum = Jurnalumum::query();
        $jurnalumum->select(
            'accounting_jurnalumum.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'accounting_jurnalumum.tanggal',
            'accounting_jurnalumum.kode_ju as no_bukti',
            DB::raw("'JURNAL UMUM' AS sumber"),
            'accounting_jurnalumum.keterangan',
            DB::raw('IF(accounting_jurnalumum.debet_kredit="K",accounting_jurnalumum.jumlah,0) as jml_kredit'),
            DB::raw('IF(accounting_jurnalumum.debet_kredit="D",accounting_jurnalumum.jumlah,0) as jml_debet'),
            DB::raw('IF(accounting_jurnalumum.debet_kredit="D",2,1) as urutan')
        );
        $jurnalumum->whereBetween('accounting_jurnalumum.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $jurnalumum->whereBetween('accounting_jurnalumum.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $jurnalumum->join('coa', 'accounting_jurnalumum.kode_akun', '=', 'coa.kode_akun');

        $jurnalumum->orderBy('accounting_jurnalumum.kode_akun');
        $jurnalumum->orderBy('accounting_jurnalumum.tanggal');
        $jurnalumum->orderBy('accounting_jurnalumum.kode_ju');



        //JURNAL Koreksi

        $jurnalkoreksi = Jurnalkoreksi::query();
        $jurnalkoreksi->select(
            'pembelian_jurnalkoreksi.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'pembelian_jurnalkoreksi.tanggal',
            'pembelian_jurnalkoreksi.no_bukti',
            DB::raw("'JURNAL KOREKSI' AS sumber"),
            'pembelian_jurnalkoreksi.keterangan',
            DB::raw('IF(pembelian_jurnalkoreksi.debet_kredit="K",pembelian_jurnalkoreksi.jumlah*harga,0) as jml_kredit'),
            DB::raw('IF(pembelian_jurnalkoreksi.debet_kredit="D",pembelian_jurnalkoreksi.jumlah*harga,0) as jml_debet'),
            DB::raw('IF(pembelian_jurnalkoreksi.debet_kredit="D",2,1) as urutan')
        );
        $jurnalkoreksi->whereBetween('pembelian_jurnalkoreksi.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $jurnalkoreksi->whereBetween('pembelian_jurnalkoreksi.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $jurnalkoreksi->join('coa', 'pembelian_jurnalkoreksi.kode_akun', '=', 'coa.kode_akun');

        $jurnalkoreksi->orderBy('pembelian_jurnalkoreksi.kode_akun');
        $jurnalkoreksi->orderBy('pembelian_jurnalkoreksi.tanggal');
        $jurnalkoreksi->orderBy('pembelian_jurnalkoreksi.no_bukti');



        //    dd($jurnalumum->get());
        $coa_kas_kecil = Coa::where('kode_transaksi', 'KKL');
        $coa_piutangcabang = Coa::where('kode_transaksi', 'PCB');

        //Kas Kecil
        $kaskecil = Kaskecil::query();
        $kaskecil->select(
            'coa_kas_kecil.kode_akun',
            'coa_kas_kecil.jenis_akun',
            'nama_akun',
            'keuangan_kaskecil.tanggal',
            'keuangan_kaskecil.no_bukti',
            DB::raw("CONCAT('KAS KECIL ', keuangan_kaskecil.kode_cabang) AS sumber"),
            'keuangan_kaskecil.keterangan',
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_debet'),
            DB::raw('IF(debet_kredit="D",2,1) as urutan')
        );
        $kaskecil->leftJoinSub($coa_kas_kecil, 'coa_kas_kecil', function ($join) {
            $join->on('keuangan_kaskecil.kode_cabang', '=', 'coa_kas_kecil.kode_cabang_coa');
        });
        $kaskecil->where(function ($query) {
            $query->where('keuangan_kaskecil.keterangan', '!=', 'Penerimaan Kas Kecil')
                ->orWhere('keuangan_kaskecil.kode_cabang', '=', 'PST');
        });







        $kaskecil->whereBetween('keuangan_kaskecil.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kaskecil->whereBetween('coa_kas_kecil.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kaskecil->orderBy('coa_kas_kecil.kode_akun');
        $kaskecil->orderBy('keuangan_kaskecil.tanggal');
        $kaskecil->orderBy('keuangan_kaskecil.no_bukti');


        //dd($kaskecil->get());

        $kaskecil_transaksi = Kaskecil::query();
        $kaskecil_transaksi->select(
            'keuangan_kaskecil.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'keuangan_kaskecil.tanggal',
            'keuangan_kaskecil.no_bukti',
            DB::raw("CONCAT('KAS KECIL ', keuangan_kaskecil.kode_cabang) AS sumber"),
            'keuangan_kaskecil.keterangan',
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_debet'),
            DB::raw('IF(debet_kredit="D",1,2) as urutan')
        );
        $kaskecil_transaksi->whereBetween('keuangan_kaskecil.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kaskecil_transaksi->whereBetween('keuangan_kaskecil.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kaskecil_transaksi->where('keuangan_kaskecil.keterangan', '!=', 'Penerimaan Kas Kecil');
        $kaskecil_transaksi->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun');
        $kaskecil_transaksi->orderBy('keuangan_kaskecil.kode_akun');
        $kaskecil_transaksi->orderBy('keuangan_kaskecil.tanggal');
        $kaskecil_transaksi->orderBy('keuangan_kaskecil.no_bukti');

        //Kas Bank Perantara
        $kasbankperantara = Kaskecil::query();
        $kasbankperantara->select(
            'keuangan_kaskecil.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'keuangan_kaskecil.tanggal',
            'keuangan_kaskecil.no_bukti',
            DB::raw("'KAS KECIL' AS sumber"),
            'keuangan_kaskecil.keterangan',
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_debet'),
            DB::raw('IF(debet_kredit="D",1,2) as urutan')
        );
        $kasbankperantara->whereBetween('keuangan_kaskecil.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kasbankperantara->whereBetween('keuangan_kaskecil.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kasbankperantara->where('keuangan_kaskecil.kode_akun', '1-1104');
        $kasbankperantara->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun');
        $kasbankperantara->orderBy('keuangan_kaskecil.kode_akun');
        $kasbankperantara->orderBy('keuangan_kaskecil.tanggal');
        $kasbankperantara->orderBy('keuangan_kaskecil.no_bukti');



        //Piutang dari Kas Besar Penjualan
        $piutangcabang = Historibayarpenjualan::query();
        $piutangcabang->select(
            'coa_piutangcabang.kode_akun',
            'coa_piutangcabang.jenis_akun',
            'nama_akun',
            'marketing_penjualan_historibayar.tanggal',
            'marketing_penjualan_historibayar.no_bukti',
            DB::raw("'KAS BESAR PENJUALAN' AS sumber"),
            DB::raw("CONCAT(marketing_penjualan_historibayar.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            'marketing_penjualan_historibayar.jumlah as jml_debet',
            DB::raw('1 as urutan')
        );
        $piutangcabang->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $piutangcabang->leftJoin(
            DB::raw("(
                  SELECT
                    marketing_penjualan.no_faktur,
                    IF( salesbaru IS NULL, marketing_penjualan.kode_salesman, salesbaru ) AS kode_salesman_baru,
                    IF( cabangbaru IS NULL, salesman.kode_cabang, cabangbaru ) AS kode_cabang_baru
                FROM
                    marketing_penjualan
                INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                LEFT JOIN (
                SELECT
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$start_date'
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $piutangcabang->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $piutangcabang->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $piutangcabang->leftJoinSub($coa_piutangcabang, 'coa_piutangcabang', function ($join) {
            $join->on('salesman.kode_cabang', '=', 'coa_piutangcabang.kode_cabang_coa');
        });
        $piutangcabang->whereBetween('marketing_penjualan_historibayar.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $piutangcabang->whereBetween('coa_piutangcabang.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $piutangcabang->where('marketing_penjualan_historibayar.voucher', 0);
        $piutangcabang->where('marketing_penjualan.status_batal', 0);
        $piutangcabang->orderBy('coa_piutangcabang.kode_akun');
        $piutangcabang->orderBy('marketing_penjualan_historibayar.tanggal');
        $piutangcabang->orderBy('marketing_penjualan_historibayar.no_bukti');


        //Penjualan Produk
        $penjualan_produk = Detailpenjualan::query();
        $penjualan_produk->select(
            'produk.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan Produk ',produk_harga.kode_produk, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('subtotal as jml_kredit'),
            DB::raw('0 as jml_debet'),
            DB::raw('1 as urutan')
        );
        $penjualan_produk->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $penjualan_produk->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        $penjualan_produk->join('coa', 'produk.kode_akun', '=', 'coa.kode_akun');
        $penjualan_produk->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $penjualan_produk->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $penjualan_produk->whereBetween('marketing_penjualan.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $penjualan_produk->whereBetween('produk.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $penjualan_produk->where('marketing_penjualan.status_batal', 0);
        $penjualan_produk->orderBy('marketing_penjualan.tanggal');
        $penjualan_produk->orderBy('marketing_penjualan.no_faktur');




        //Putang Datang 1-1401

        //Retur Penjualan
        $returpenjualan = Detailretur::query();
        $returpenjualan->select('marketing_retur.no_faktur', DB::raw('SUM(subtotal) as jml_retur'));
        $returpenjualan->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $returpenjualan->where('jenis_retur', 'PF');
        $returpenjualan->whereBetween('marketing_retur.tanggal', [$start_date, $request->sampai]);
        $returpenjualan->groupBy('marketing_retur.no_faktur');

        $detailpenjualan = Detailpenjualan::query();
        $detailpenjualan->select('marketing_penjualan.no_faktur', DB::raw('SUM(subtotal) as jml_bruto_penjualan'));
        $detailpenjualan->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $detailpenjualan->whereBetween('marketing_penjualan.tanggal', [$start_date, $request->sampai]);
        $detailpenjualan->where('status_batal', 0);
        $detailpenjualan->groupBy('marketing_penjualan.no_faktur');

        $penjualannetto = Penjualan::query();
        $penjualannetto->select(
            'marketing_penjualan.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur as no_bukti',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan ',pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            DB::raw('(IFNULL(jml_bruto_penjualan,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) - IFNULL(jml_retur,0)) as jml_debet'),
            DB::raw('1 as urutan')
        );
        $penjualannetto->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $penjualannetto->join('coa', 'marketing_penjualan.kode_akun', '=', 'coa.kode_akun');
        $penjualannetto->leftJoinSub($returpenjualan, 'returpenjualan', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'returpenjualan.no_faktur');
        });
        $penjualannetto->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'detailpenjualan.no_faktur');
        });
        $penjualannetto->where('marketing_penjualan.status_batal', 0);
        $penjualannetto->whereBetween('marketing_penjualan.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $penjualannetto->whereBetween('marketing_penjualan.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $penjualannetto->orderBy('marketing_penjualan.kode_akun');
        $penjualannetto->orderBy('marketing_penjualan.tanggal');



        //Piutang Datang

        //Kas Besar
        $kasbesarpiutangdagang = Historibayarpenjualan::query();
        $kasbesarpiutangdagang->select(
            'marketing_penjualan_historibayar.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan_historibayar.tanggal',
            'marketing_penjualan_historibayar.no_bukti',
            DB::raw("'KAS BESAR PENJUALAN' AS sumber"),
            DB::raw("CONCAT(marketing_penjualan_historibayar.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            'marketing_penjualan_historibayar.jumlah as jml_kredit',
            DB::raw('0 as jml_debet'),
            DB::raw('2 as urutan')
        );
        $kasbesarpiutangdagang->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $kasbesarpiutangdagang->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $kasbesarpiutangdagang->join('coa', 'marketing_penjualan_historibayar.kode_akun', '=', 'coa.kode_akun');
        $kasbesarpiutangdagang->whereBetween('marketing_penjualan_historibayar.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kasbesarpiutangdagang->whereBetween('marketing_penjualan_historibayar.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kasbesarpiutangdagang->where('voucher', 0);
        $kasbesarpiutangdagang->orderBy('marketing_penjualan_historibayar.kode_akun');
        $kasbesarpiutangdagang->orderBy('marketing_penjualan_historibayar.tanggal');
        $kasbesarpiutangdagang->orderBy('marketing_penjualan_historibayar.no_bukti');



        //Retur
        $returpenjualanpiutangdagang = Detailretur::query();
        $returpenjualanpiutangdagang->select(
            'marketing_retur.kode_akun_piutang_dagang',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_retur.tanggal',
            DB::raw("marketing_retur.no_retur as no_bukti"),
            DB::raw("'RETUR PENJUALAN' AS sumber"),
            DB::raw("CONCAT(marketing_retur.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('SUM(marketing_retur_detail.subtotal) as jml_kredit'),
            DB::raw('0 as jml_debet'),
            DB::raw('2 as urutan')
        );
        $returpenjualanpiutangdagang->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $returpenjualanpiutangdagang->join('coa', 'marketing_retur.kode_akun_piutang_dagang', '=', 'coa.kode_akun');
        $returpenjualanpiutangdagang->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $returpenjualanpiutangdagang->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $returpenjualanpiutangdagang->where('jenis_retur', 'PF');
        $returpenjualanpiutangdagang->whereBetween('marketing_retur.tanggal', [$start_date, $request->sampai]);
        $returpenjualanpiutangdagang->where('marketing_penjualan.status_batal', 0);
        $returpenjualanpiutangdagang->where('marketing_penjualan.tanggal', '<', $start_date);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $returpenjualanpiutangdagang->whereBetween('marketing_retur.kode_akun_piutang_dagang', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $returpenjualanpiutangdagang->groupBy('marketing_retur.kode_akun_piutang_dagang', 'coa.jenis_akun', 'nama_akun', 'marketing_retur.tanggal', 'marketing_retur.no_retur', 'marketing_retur.no_faktur', 'pelanggan.nama_pelanggan');
        $returpenjualanpiutangdagang->orderBy('marketing_retur.tanggal');
        $returpenjualanpiutangdagang->orderBy('marketing_retur.no_retur');


        $retur_penjualan = Detailretur::query();
        $retur_penjualan->select(
            'marketing_retur.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_retur.tanggal',
            DB::raw("marketing_retur.no_retur as no_bukti"),
            DB::raw("'RETUR PENJUALAN' AS sumber"),
            DB::raw("CONCAT(marketing_retur.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            'marketing_retur_detail.subtotal as jml_debet',
            DB::raw('1 as urutan')
        );

        $retur_penjualan->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $retur_penjualan->join('coa', 'marketing_retur.kode_akun', '=', 'coa.kode_akun');
        $retur_penjualan->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $retur_penjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $retur_penjualan->whereBetween('marketing_retur.tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $retur_penjualan->whereBetween('marketing_retur.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $retur_penjualan->where('marketing_retur.jenis_retur', 'PF');
        $retur_penjualan->orderBy('marketing_retur.tanggal');
        $retur_penjualan->orderBy('marketing_retur.no_retur');



        $potongan_penjualan = Penjualan::query();
        $potongan_penjualan->select(
            'marketing_penjualan.kode_akun_potongan',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur as no_bukti',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan ',marketing_penjualan.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            DB::raw('IFNULL(potongan,0) + IFNULL(potongan_istimewa,0) as jml_debet'),
            DB::raw('1 as urutan')
        );
        $potongan_penjualan->join('coa', 'marketing_penjualan.kode_akun_potongan', '=', 'coa.kode_akun');
        $potongan_penjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $potongan_penjualan->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        $potongan_penjualan->where('marketing_penjualan.status_batal', 0);
        $potongan_penjualan->orderBy('marketing_penjualan.tanggal');
        $potongan_penjualan->orderBy('marketing_penjualan.no_faktur');
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $potongan_penjualan->whereBetween('marketing_penjualan.kode_akun_potongan', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $potongan_penjualan->orderBy('marketing_penjualan.tanggal');
        $potongan_penjualan->orderBy('marketing_penjualan.no_faktur');



        $penyesuaian_penjualan = Penjualan::query();
        $penyesuaian_penjualan->select(
            'marketing_penjualan.kode_akun_penyesuaian',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur as no_bukti',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan ',marketing_penjualan.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            DB::raw('IFNULL(penyesuaian,0) as jml_debet'),
            DB::raw('1 as urutan')
        );
        $penyesuaian_penjualan->join('coa', 'marketing_penjualan.kode_akun_penyesuaian', '=', 'coa.kode_akun');
        $penyesuaian_penjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $penyesuaian_penjualan->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        $penyesuaian_penjualan->where('marketing_penjualan.status_batal', 0);
        $penyesuaian_penjualan->orderBy('marketing_penjualan.tanggal');
        $penyesuaian_penjualan->orderBy('marketing_penjualan.no_faktur');
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $penyesuaian_penjualan->whereBetween('marketing_penjualan.kode_akun_penyesuaian', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }

        $penyesuaian_penjualan->orderBy('marketing_penjualan.tanggal');
        $penyesuaian_penjualan->orderBy('marketing_penjualan.no_faktur');



        //dd($penyesuaian_penjualan->get());


        //dd($potongan_penjualan->get());
        // if ($request->kode_akun_dari == '4-2100' || $request->kode_akun_sampai == '4-2100') {
        //     $retur_penjualan = Detailretur::query();
        //     $retur_penjualan->select(
        //         DB::raw("'4-2100' as kode_akun"),
        //         DB::raw("'Retur Penjualan' as nama_akun"),
        //         'marketing_retur.tanggal',
        //         DB::raw("marketing_retur.no_retur as no_bukti"),
        //         DB::raw("'RETUR PENJUALAN' AS sumber"),
        //         DB::raw("CONCAT(marketing_retur.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
        //         DB::raw('0 as jml_kredit'),
        //         'marketing_retur_detail.subtotal as jml_debet',
        //         DB::raw('1 as urutan')
        //     );

        //     $retur_penjualan->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        //     $retur_penjualan->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        //     $retur_penjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        //     $retur_penjualan->whereBetween('marketing_retur.tanggal', [$request->dari, $request->sampai]);
        //     $retur_penjualan->where('marketing_retur.jenis_retur', 'PF');
        //     $retur_penjualan->orderBy('marketing_retur.tanggal');
        //     $retur_penjualan->orderBy('marketing_retur.no_retur');
        // } else {
        //     // Jika tidak ada retur_penjualan, buat query kosong agar tidak error pada unionAll
        //     $retur_penjualan = Detailretur::query()->select(
        //         DB::raw("'4-2100' as kode_akun"),
        //         DB::raw("'Retur Penjualan' as nama_akun"),
        //         DB::raw("NULL as tanggal"),
        //         DB::raw("NULL as no_bukti"),
        //         DB::raw("'RETUR PENJUALAN' AS sumber"),
        //         DB::raw("NULL as keterangan"),
        //         DB::raw('0 as jml_kredit'),
        //         DB::raw('0 as jml_debet'),
        //         DB::raw('1 as urutan')
        //     )->whereRaw('0 = 1'); // Query kosong
        // }

        // Melakukan sum debet dan kredit dari data union sebelum tanggal $request->dari, group by kode_akun
        // $mutasi_transaksi = $ledger->unionAll($kaskecil)
        //     ->unionAll($ledger_transaksi)
        //     ->unionAll($piutangcabang)
        //     ->unionAll($pembelian)
        //     ->unionAll($jurnalumum)
        //     ->unionAll($jurnalkoreksi)
        //     ->unionAll($penjualan_produk);



        // Contoh penggunaan: $total_mutasi_per_akun adalah collection, akses per kode_akun



        $hutangdagangdanlainnya = Pembelian::query();
        $hutangdagangdanlainnya->select(
            'pembelian.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'pembelian.tanggal',
            'pembelian.no_bukti',
            DB::raw("'PEMBELIAN' AS sumber"),
            DB::raw("CONCAT(' Pembelian ',pembelian.no_bukti, ' - ', supplier.nama_supplier) as keterangan"),
            DB::raw('detailpembelian.subtotal as jml_kredit'),
            DB::raw('0 as jml_debet'),
            DB::raw('1 as urutan')
        );

        $hutangdagangdanlainnya->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $hutangdagangdanlainnya->join('coa', 'pembelian.kode_akun', '=', 'coa.kode_akun');
        $hutangdagangdanlainnya->leftJoin(
            DB::raw('(
                SELECT no_bukti, SUM( IF ( kode_transaksi = "PMB", ( ( jumlah * harga ) + penyesuaian ), 0 ) ) - SUM( IF ( kode_transaksi = "PNJ", ( jumlah * harga ), 0 ) ) as subtotal
                FROM pembelian_detail
                GROUP BY no_bukti
            ) detailpembelian'),
            function ($join) {
                $join->on('pembelian.no_bukti', '=', 'detailpembelian.no_bukti');
            }
        );
        $hutangdagangdanlainnya->whereBetween('pembelian.tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $hutangdagangdanlainnya->whereBetween('pembelian.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $hutangdagangdanlainnya->orderBy('pembelian.tanggal');
        $hutangdagangdanlainnya->orderBy('pembelian.no_bukti');
        $hutangdagangdanlainnya->orderBy('urutan');

        //dd($hutangdagangdanlainnya->get());
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['saldoawalCollection'] = $saldoawalCollection;

        $union_data = $ledger->unionAll($saldoawal)
            ->unionAll($kaskecil)
            ->unionAll($kaskecil_transaksi)
            ->unionAll($kasbankperantara)
            ->unionAll($ledger_transaksi)
            ->unionAll($piutangcabang)
            ->unionAll($pembelian)
            ->unionAll($jurnalumum)
            ->unionAll($jurnalkoreksi)
            ->unionAll($penjualan_produk)
            ->unionAll($penjualannetto)
            ->unionAll($kasbesarpiutangdagang)
            ->unionAll($returpenjualanpiutangdagang)
            ->unionAll($retur_penjualan)
            ->unionAll($potongan_penjualan)
            ->unionAll($penyesuaian_penjualan)
            ->unionAll($hutangdagangdanlainnya);

        return [
            'union_data' => $union_data,
            'saldoawalCollection' => $saldoawalCollection,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'start_date' => $start_date
        ];
    }
