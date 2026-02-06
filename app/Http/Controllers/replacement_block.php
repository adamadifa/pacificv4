
    public function lockNeraca(Request $request)
    {
        $request->validate([
            'dari' => 'required|date',
            'sampai' => 'required|date',
            'details' => 'required|array',
            'details.*.kode_akun' => 'required',
            'details.*.jumlah' => 'required'
        ]);

        $dari = $request->dari;
        $bulan = date('m', strtotime($dari));
        $tahun = date('Y', strtotime($dari));
        $kode_lk = 'LK' . $bulan . $tahun;
        $kategori = 'NC';

        try {
            DB::beginTransaction();

            $user_id = Auth::user()->id;

            // Simpan atau update Header Laporan Keuangan
            \App\Models\LaporanKeuangan::updateOrCreate(
                ['kode_lk' => $kode_lk],
                [
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'kategori' => $kategori,
                    'user_id' => $user_id,
                    'updated_at' => now()
                ]
            );

            // Hapus detail sebelumnya untuk kode_lk ini agar tidak duplikat/conflict
            \App\Models\LaporanKeuanganDetail::where('kode_lk', $kode_lk)->delete();

            // Insert detail baru
            $details = [];
            foreach ($request->details as $item) {
                // Pastikan jumlah numeric
                $jumlah = str_replace('.', '', $item['jumlah']); // Remove dots if formatAngka implies dots
                $jumlah = str_replace(',', '.', $jumlah); // Replace comma with dot if locale requires

                // Di view formatAngka menggunakan number_format($val, 0, ',', '.') -> ribuan pake titik
                // Jadi kita balikin ke float: hapus titik, replace koma jadi titik (jika ada desimal)
                // Tapi formatAngka standard indo: 1.000.000
                $jumlahClean = (float) filter_var($item['jumlah'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                // ATAU lebih aman jika formatnya 1.234.567:
                // $jumlah = (float) str_replace(['.', ','], ['', '.'], $item['jumlah']); 
                // Cek formatAngka helper. Biasanya return number_format($angka, '0', ',', '.');
                // Jadi 1000 jadi 1.000
                // Kita replace . dengan empty string.
                
                // Correction: formatAngka might produce "1.000".
                // We should just store the raw value if possible, but the view sends the formatted value because it uses formatAngka($saldo_akhir) in the hidden input?
                // WAIT! In the view modification I did: value="{{ $saldo_akhir }}"
                // $saldo_akhir is the raw number (float/int).
                // So I do NOT need to unformat it. It is raw.
                
                $details[] = [
                    'kode_lk' => $kode_lk,
                    'kode_akun' => $item['kode_akun'],
                    'jumlah' => $item['jumlah'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            \App\Models\LaporanKeuanganDetail::insert($details);

            DB::commit();

            return redirect()->back()->with('success', 'Laporan Neraca Berhasil Dikunci!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal Mengunci Laporan: ' . $e->getMessage());
        }
    }
