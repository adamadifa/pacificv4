<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class InternalMemoController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = DB::table('internal_memo')
            ->leftJoin('internal_memo_tujuan_dept as td', 'internal_memo.id', '=', 'td.internal_memo_id')
            ->leftJoin('internal_memo_tujuan_cabang as tc', 'internal_memo.id', '=', 'tc.internal_memo_id')
            ->leftJoin('internal_memo_tujuan_jabatan as tr', 'internal_memo.id', '=', 'tr.internal_memo_id')
            ->leftJoin('internal_memo_log_baca as lb', function ($join) use ($user) {
                $join->on('internal_memo.id', '=', 'lb.internal_memo_id')
                    ->where('lb.user_id', $user->id);
            })
            ->where(function ($query) use ($user) {
                $query->where('td.kode_dept', $user->kode_dept)
                    ->orWhere('tc.kode_cabang', $user->kode_cabang)
                    ->orWhere('tr.kode_jabatan', $user->kode_jabatan);
            });

        /* ================= FILTER ================= */

        // Filter No IM
        if (request('no_im')) {
            $query->where('internal_memo.no_im', 'like', '%' . request('no_im') . '%');
        }

        // Filter Judul
        if (request('judul')) {
            $query->where('internal_memo.judul', 'like', '%' . request('judul') . '%');
        }

        // Filter Status
        if (request('status')) {
            $query->where('internal_memo.status', request('status'));
        }

        // Filter Dibaca
        if (request('dibaca') == 'sudah') {
            $query->whereNotNull('lb.dibaca_pada');
        }

        if (request('dibaca') == 'belum') {
            $query->whereNull('lb.dibaca_pada');
        }

        /* ========================================== */

        $internalMemos = $query
            ->select(
                'internal_memo.id',
                'internal_memo.no_im',
                'internal_memo.judul',
                'internal_memo.tanggal_im',
                'internal_memo.berlaku_dari',
                'internal_memo.berlaku_sampai',
                'internal_memo.file_im',
                'internal_memo.keterangan',
                'internal_memo.status',
                DB::raw('MAX(lb.dibaca_pada) as dibaca_pada')
            )
            ->groupBy(
                'internal_memo.id',
                'internal_memo.no_im',
                'internal_memo.judul',
                'internal_memo.tanggal_im',
                'internal_memo.berlaku_dari',
                'internal_memo.berlaku_sampai',
                'internal_memo.file_im',
                'internal_memo.keterangan',
                'internal_memo.status'
            )
            ->orderBy('internal_memo.tanggal_im', 'desc')
            ->paginate(10)
            ->withQueryString(); // ⬅️ penting agar filter ikut pagination

        return view('utilities.internalmemo.index', compact('internalMemos'));
    }



    public function create()
    {
        $data['deptList'] = DB::table('hrd_departemen')
            ->orderBy('nama_dept')
            ->get();
        $data['jabatanList'] = DB::table('hrd_jabatan')
            ->orderBy('nama_jabatan')
            ->whereNotNull('alias')
            ->get();
        $data['cabangList'] = Cabang::all();

        return view('utilities.internalmemo.create', $data);
    }

    private function generateFileName($noIm, $extension)
    {
        // Bersihkan karakter aneh
        $safeNoIm = preg_replace('/[^A-Za-z0-9\-]/', '_', $noIm);

        return $safeNoIm . '.' . $extension;
    }
    public function store(Request $request)
    {
        $request->validate([
            'no_im' => 'required|unique:internal_memo,no_im',
            'judul' => 'required',
            'tanggal_im' => 'required|date',
            'berlaku_dari' => 'required|date',
            'berlaku_sampai' => 'nullable|date|after_or_equal:berlaku_dari',

            'tujuan' => 'required|array',
            'tujuan.*' => 'required|string',

            'tujuan_cabang' => 'nullable|array',
            'tujuan_jabatan' => 'nullable|array',

            'file_im' => 'nullable|mimes:pdf|max:2048',
        ]);

        DB::beginTransaction();
        try {

            // =========================
            // UPLOAD FILE
            // =========================
            $fileName = null;
            if ($request->hasFile('file_im')) {
                $extension = $request->file('file_im')->getClientOriginalExtension();
                $fileName = $this->generateFileName($request->no_im, $extension);

                $request->file('file_im')
                    ->storeAs('internal_memo', $fileName, 'public');
            }

            // =========================
            // INSERT INTERNAL MEMO
            // =========================
            $memoId = DB::table('internal_memo')->insertGetId([
                'no_im' => $request->no_im,
                'judul' => $request->judul,
                'tanggal_im' => $request->tanggal_im,
                'berlaku_dari' => $request->berlaku_dari,
                'berlaku_sampai' => $request->berlaku_sampai,
                'file_im' => $fileName,
                'keterangan' => $request->keterangan,
                'dibuat_oleh' => Auth::id(),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // =========================
            // TUJUAN DEPARTEMEN (WAJIB)
            // =========================
            foreach ($request->tujuan as $dept) {
                DB::table('internal_memo_tujuan_dept')->insert([
                    'internal_memo_id' => $memoId,
                    'kode_dept' => $dept,
                    'created_at' => now(),
                ]);
            }

            // =========================
            // TUJUAN CABANG (OPSIONAL)
            // =========================
            if ($request->filled('tujuan_cabang')) {
                foreach ($request->tujuan_cabang as $cabang) {
                    DB::table('internal_memo_tujuan_cabang')->insert([
                        'internal_memo_id' => $memoId,
                        'kode_cabang' => $cabang,
                        'created_at' => now(),
                    ]);
                }
            }

            // =========================
            // TUJUAN jabatan (OPSIONAL)
            // =========================
            if ($request->filled('tujuan_jabatan')) {
                foreach ($request->tujuan_jabatan as $jabatan) {
                    DB::table('internal_memo_tujuan_jabatan')->insert([
                        'internal_memo_id' => $memoId,
                        'kode_jabatan' => $jabatan,
                        'created_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('internalmemo.index')
                ->with('success', 'Internal Memo berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }



    public function show($id)
    {
        $user = Auth::user();

        $memo = DB::table('internal_memo')->where('id', $id)->first();
        if (!$memo)
            abort(404);

        // validasi tujuan
        $cekTujuan = DB::table('internal_memo_tujuan_dept')
            ->where('internal_memo_id', $id)
            ->where('kode_dept', $user->kode_dept)
            ->exists();

        if (!$cekTujuan)
            abort(403);

        // log baca
        $cekBaca = DB::table('internal_memo_log_baca')
            ->where('internal_memo_id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$cekBaca) {
            DB::table('internal_memo_log_baca')->updateOrInsert(
                [
                    'internal_memo_id' => $id,
                    'user_id' => Auth::id()
                ],
                [
                    'dibaca_pada' => now()
                ]
            );
        }



        return view('utilities.internalmemo.show', compact('memo'));
    }


    public function edit($id)
    {
        $memo = DB::table('internal_memo')->where('id', $id)->first();

        if (!$memo) {
            abort(404);
        }

        $deptList = DB::table('hrd_departemen')->orderBy('nama_dept')->get();
        $cabangList = DB::table('cabang')->orderBy('nama_cabang')->get();
        $jabatanList = DB::table('hrd_jabatan')
            ->orderBy('nama_jabatan')
            ->whereNotNull('alias')
            ->get();

        // Tujuan yang sudah dipilih
        $selectedDept = DB::table('internal_memo_tujuan_dept')
            ->where('internal_memo_id', $id)
            ->pluck('kode_dept')
            ->toArray();

        $selectedCabang = DB::table('internal_memo_tujuan_cabang')
            ->where('internal_memo_id', $id)
            ->pluck('kode_cabang')
            ->toArray();

        $selectedJabatan = DB::table('internal_memo_tujuan_jabatan')
            ->where('internal_memo_id', $id)
            ->pluck('kode_jabatan')
            ->toArray();

        return view('utilities.internalmemo.edit', compact(
            'memo',
            'deptList',
            'cabangList',
            'jabatanList',
            'selectedDept',
            'selectedCabang',
            'selectedJabatan'
        ));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'no_im' => 'required|unique:internal_memo,no_im,' . $id,
            'judul' => 'required',
            'tanggal_im' => 'required|date',
            'file_im' => 'nullable|mimes:pdf|max:2048'
        ]);

        DB::beginTransaction();
        try {

            $memo = DB::table('internal_memo')->where('id', $id)->first();
            if (!$memo) {
                abort(404);
            }

            // Upload file jika ada
            $fileName = $memo->file_im;
            if ($request->hasFile('file_im')) {
                $extension = $request->file('file_im')->getClientOriginalExtension();
                $fileName = $this->generateFileName($request->no_im, $extension);

                $request->file('file_im')
                    ->storeAs('internal_memo', $fileName, 'public');
            }

            // Update memo
            DB::table('internal_memo')->where('id', $id)->update([
                'no_im' => $request->no_im,
                'judul' => $request->judul,
                'tanggal_im' => $request->tanggal_im,
                'berlaku_dari' => $request->berlaku_dari,
                'berlaku_sampai' => $request->berlaku_sampai,
                'file_im' => $fileName,
                'keterangan' => $request->keterangan,
                'updated_at' => now(),
            ]);

            // === RESET TUJUAN ===
            DB::table('internal_memo_tujuan_dept')
                ->where('internal_memo_id', $id)->delete();

            DB::table('internal_memo_tujuan_cabang')
                ->where('internal_memo_id', $id)->delete();

            DB::table('internal_memo_tujuan_jabatan')
                ->where('internal_memo_id', $id)->delete();

            // === INSERT ULANG ===
            if ($request->tujuan) {
                foreach ($request->tujuan as $dept) {
                    DB::table('internal_memo_tujuan_dept')->insert([
                        'internal_memo_id' => $id,
                        'kode_dept' => $dept,
                        'created_at' => now()
                    ]);
                }
            }

            if ($request->tujuan_cabang) {
                foreach ($request->tujuan_cabang as $cabang) {
                    DB::table('internal_memo_tujuan_cabang')->insert([
                        'internal_memo_id' => $id,
                        'kode_cabang' => $cabang,
                        'created_at' => now()
                    ]);
                }
            }

            if ($request->tujuan_jabatan) {
                foreach ($request->tujuan_jabatan as $jabatan) {
                    DB::table('internal_memo_tujuan_jabatan')->insert([
                        'internal_memo_id' => $id,
                        'kode_jabatan' => $jabatan,
                        'created_at' => now()
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('internalmemo.index')
                ->with('success', 'Internal Memo berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }


    public function destroy($id)
    {
        DB::table('internal_memo')->where('id', $id)->delete();
        DB::table('internal_memo_tujuan')->where('internal_memo_id', $id)->delete();
        DB::table('internal_memo_log_baca')->where('internal_memo_id', $id)->delete();

        return back()->with('success', 'Internal Memo berhasil dihapus');
    }


    public function aktifkan($id)
    {
        DB::table('internal_memo')->where('id', $id)->update([
            'status' => 'aktif',
            'updated_at' => now()
        ]);

        return back()->with('success', 'Internal Memo diaktifkan');
    }


    public function nonaktifkan($id)
    {
        DB::table('internal_memo')->where('id', $id)->update([
            'status' => 'nonaktif',
            'updated_at' => now()
        ]);

        return back()->with('success', 'Internal Memo dinonaktifkan');
    }
}
