<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $nik = $request->user()->nik;
        
        $sakit = DB::table('hrd_izinsakit')
            ->select('kode_izin_sakit as id', 'tanggal', 'dari', 'sampai', 'keterangan', 'status', DB::raw("'Sakit' as tipe"))
            ->where('nik', $nik);
            
        $absen = DB::table('hrd_izinabsen')
            ->select('kode_izin as id', 'tanggal', 'dari', 'sampai', 'keterangan', 'status', DB::raw("'Absen' as tipe"))
            ->where('nik', $nik);
            
        $cuti = DB::table('hrd_izincuti')
            ->select('kode_izin_cuti as id', 'tanggal', 'dari', 'sampai', 'keterangan', 'status', DB::raw("'Cuti' as tipe"))
            ->where('nik', $nik);
            
        $dinas = DB::table('hrd_izindinas')
            ->select('kode_izin_dinas as id', 'tanggal', 'dari', 'sampai', 'keterangan', 'status', DB::raw("'Dinas' as tipe"))
            ->where('nik', $nik);
            
        $pulang = DB::table('hrd_izinpulang')
            ->select('kode_izin_pulang as id', 'tanggal', DB::raw('tanggal as dari'), DB::raw('tanggal as sampai'), 'keterangan', 'status', DB::raw("'Pulang' as tipe"))
            ->where('nik', $nik);
            
        $keluar = DB::table('hrd_izinkeluar')
            ->select('kode_izin_keluar as id', 'tanggal', DB::raw('tanggal as dari'), DB::raw('tanggal as sampai'), 'keterangan', 'status', DB::raw("'Keluar' as tipe"))
            ->where('nik', $nik);
            
        $combined = $sakit->union($absen)
            ->union($cuti)
            ->union($dinas)
            ->union($pulang)
            ->union($keluar)
            ->orderBy('tanggal', 'desc')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $combined
        ]);
    }

    public function getFormData()
    {
        $jenis_cuti = DB::table('hrd_jeniscuti')->orderBy('kode_cuti')->get();
        $jenis_cuti_khusus = DB::table('hrd_jeniscuti_khusus')->orderBy('kode_cuti_khusus')->get();
        $cabang = DB::table('cabang')->orderBy('nama_cabang')->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'jenis_cuti' => $jenis_cuti,
                'jenis_cuti_khusus' => $jenis_cuti_khusus,
                'cabang' => $cabang
            ]
        ]);
    }

    public function store(Request $request)
    {
        $type = $request->type;
        $nik = $request->user()->nik;
        $karyawan = Karyawan::where('nik', $nik)->first();
        
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try {
            $head = ($karyawan->kode_dept == 'HRD' && $karyawan->kode_jabatan == 'J12') || $karyawan->kode_jabatan == 'J02' ? '1' : '0';
            $tgl_code = date('ym', strtotime($request->tanggal));

            switch ($type) {
                case 'sakit':
                    $last = DB::table('hrd_izinsakit')->whereRaw('LEFT(kode_izin_sakit,6) = "IS'.$tgl_code.'"')->orderBy('kode_izin_sakit', 'desc')->first();
                    $kode = buatkode($last ? $last->kode_izin_sakit : '', 'IS' . $tgl_code, 4);
                    
                    $filename = null;
                    if ($request->hasFile('file')) {
                        $filename = $kode . '.' . $request->file('file')->getClientOriginalExtension();
                        $request->file('file')->storeAs('public/uploads/sid', $filename);
                    }
                    
                    DB::table('hrd_izinsakit')->insert([
                        'kode_izin_sakit' => $kode,
                        'nik' => $nik,
                        'tanggal' => $request->tanggal,
                        'dari' => $request->dari ?? $request->tanggal,
                        'sampai' => $request->sampai ?? $request->tanggal,
                        'keterangan' => $request->keterangan,
                        'doc_sid' => $filename,
                        'kode_jabatan' => $karyawan->kode_jabatan,
                        'kode_dept' => $karyawan->kode_dept,
                        'kode_cabang' => $karyawan->kode_cabang,
                        'head' => $head, 'status' => 0, 'hrd' => 0, 'direktur' => 0, 'forward_to_direktur' => 0,
                        'id_user' => 1
                    ]);
                    break;

                case 'absen':
                    $last = DB::table('hrd_izinabsen')->whereRaw('LEFT(kode_izin,6) = "IA'.$tgl_code.'"')->orderBy('kode_izin', 'desc')->first();
                    $kode = buatkode($last ? $last->kode_izin : '', 'IA' . $tgl_code, 4);
                    
                    DB::table('hrd_izinabsen')->insert([
                        'kode_izin' => $kode,
                        'nik' => $nik,
                        'tanggal' => $request->tanggal,
                        'dari' => $request->dari ?? $request->tanggal,
                        'sampai' => $request->sampai ?? $request->tanggal,
                        'keterangan' => $request->keterangan,
                        'kode_jabatan' => $karyawan->kode_jabatan,
                        'kode_dept' => $karyawan->kode_dept,
                        'kode_cabang' => $karyawan->kode_cabang,
                        'head' => $head, 'status' => 0, 'hrd' => 0, 'direktur' => 0, 'forward_to_direktur' => 0,
                        'id_user' => 1
                    ]);
                    break;
                
                case 'cuti':
                    $last = DB::table('hrd_izincuti')->whereRaw('LEFT(kode_izin_cuti,6) = "IC'.$tgl_code.'"')->orderBy('kode_izin_cuti', 'desc')->first();
                    $kode = buatkode($last ? $last->kode_izin_cuti : '', 'IC' . $tgl_code, 4);
                    
                    $filename = null;
                    if ($request->hasFile('file')) {
                        $filename = $kode . '.' . $request->file('file')->getClientOriginalExtension();
                        $request->file('file')->storeAs('public/uploads/cuti', $filename);
                    }

                    DB::table('hrd_izincuti')->insert([
                        'kode_izin_cuti' => $kode,
                        'nik' => $nik,
                        'tanggal' => $request->tanggal,
                        'dari' => $request->dari ?? $request->tanggal,
                        'sampai' => $request->sampai ?? $request->tanggal,
                        'kode_cuti' => $request->kode_cuti,
                        'kode_cuti_khusus' => $request->kode_cuti == 'C03' ? $request->kode_cuti_khusus : null,
                        'keterangan' => $request->keterangan,
                        'doc_cuti' => $filename,
                        'kode_jabatan' => $karyawan->kode_jabatan,
                        'kode_dept' => $karyawan->kode_dept,
                        'kode_cabang' => $karyawan->kode_cabang,
                        'head' => $head, 'status' => 0, 'hrd' => 0, 'direktur' => 0, 'forward_to_direktur' => 0,
                        'id_user' => 1
                    ]);
                    break;

                case 'dinas':
                    $last = DB::table('hrd_izindinas')->whereRaw('LEFT(kode_izin_dinas,6) = "ID'.$tgl_code.'"')->orderBy('kode_izin_dinas', 'desc')->first();
                    $kode = buatkode($last ? $last->kode_izin_dinas : '', 'ID' . $tgl_code, 4);
                    
                    $filename = null;
                    if ($request->hasFile('file')) {
                        $filename = $kode . '.' . $request->file('file')->getClientOriginalExtension();
                        $request->file('file')->storeAs('public/uploads/dinas', $filename);
                    }

                    DB::table('hrd_izindinas')->insert([
                        'kode_izin_dinas' => $kode,
                        'nik' => $nik,
                        'tanggal' => $request->tanggal,
                        'dari' => $request->dari ?? $request->tanggal,
                        'sampai' => $request->sampai ?? $request->tanggal,
                        'keterangan' => $request->keterangan,
                        'kode_cabang_tujuan' => $request->kode_cabang_tujuan,
                        'doc_dinas' => $filename,
                        'kode_jabatan' => $karyawan->kode_jabatan,
                        'kode_dept' => $karyawan->kode_dept,
                        'kode_cabang' => $karyawan->kode_cabang,
                        'head' => $head, 'status' => 0, 'hrd' => 0, 'direktur' => 0, 'forward_to_direktur' => 0,
                        'id_user' => 1
                    ]);
                    break;

                case 'pulang':
                    $last = DB::table('hrd_izinpulang')->whereRaw('LEFT(kode_izin_pulang,6) = "IP'.$tgl_code.'"')->orderBy('kode_izin_pulang', 'desc')->first();
                    $kode = buatkode($last ? $last->kode_izin_pulang : '', 'IP' . $tgl_code, 4);
                    
                    DB::table('hrd_izinpulang')->insert([
                        'kode_izin_pulang' => $kode,
                        'nik' => $nik,
                        'tanggal' => $request->tanggal,
                        'jam_pulang' => $request->tanggal . ' ' . $request->jam . ':00',
                        'keterangan' => $request->keterangan,
                        'kode_jabatan' => $karyawan->kode_jabatan,
                        'kode_dept' => $karyawan->kode_dept,
                        'kode_cabang' => $karyawan->kode_cabang,
                        'head' => $head, 'status' => 0, 'hrd' => 0, 'direktur' => 0, 'forward_to_direktur' => 0,
                        'id_user' => 1
                    ]);
                    break;

                case 'keluar':
                    $last = DB::table('hrd_izinkeluar')->whereRaw('LEFT(kode_izin_keluar,6) = "IK'.$tgl_code.'"')->orderBy('kode_izin_keluar', 'desc')->first();
                    $kode = buatkode($last ? $last->kode_izin_keluar : '', 'IK' . $tgl_code, 4);
                    
                    DB::table('hrd_izinkeluar')->insert([
                        'kode_izin_keluar' => $kode,
                        'nik' => $nik,
                        'tanggal' => $request->tanggal,
                        'jam_keluar' => $request->tanggal . ' ' . $request->jam_keluar . ':00',
                        'jam_kembali' => $request->jam_kembali ? $request->tanggal . ' ' . $request->jam_kembali . ':00' : null,
                        'keperluan' => $request->keperluan ?? 'P',
                        'keterangan' => $request->keterangan,
                        'kode_jabatan' => $karyawan->kode_jabatan,
                        'kode_dept' => $karyawan->kode_dept,
                        'kode_cabang' => $karyawan->kode_cabang,
                        'head' => $head, 'status' => 0, 'hrd' => 0, 'direktur' => 0, 'forward_to_direktur' => 0,
                        'id_user' => $request->user()->id
                    ]);
                    break;
            }
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pengajuan berhasil dikirim']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
