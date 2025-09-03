<?php

namespace App\Http\Controllers;

use App\Jobs\sendActivityJob;
use App\Models\AktifitasSMM;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AktifitassmmController extends Controller
{
    public function index()
    {
        return view('aktifitas_smm.index');
    }

    public function create()
    {
        return view('aktifitas_smm.create');
    }

    public function getaktifitas(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $cabang = Cabang::where('kode_cabang', $user->kode_cabang)->first();
        $data['lokasi'] = explode(",", $cabang->lokasi_cabang);
        if (!isset($request->tanggal)) {
            $tanggal = date("Y-m-d");
        } else {
            $tanggal = $request->tanggal;
        }
        $aktifitas = AktifitasSMM::where('tanggal', $tanggal)->where('id_user', auth()->user()->id)->get();
        $data['aktifitas'] = $aktifitas;
        return view('aktifitas_smm.getaktifitas', $data);
    }

    public function store(Request $request)
    {


        $id = auth()->user()->id;
        $id_group_wa = auth()->user()->id_group_wa;
        $user = User::findorfail($id);
        $role_name = $user->getRoleNames()[0];
        // dd($role_name);
        $cekuser = User::where('id', $id)->first();

        $nama = $cekuser->name;
        $lokasi = $request->lokasi;
        $activity = $request->activity;
        $lok = explode(",", $lokasi);
        $latitude = $lok[0];
        $longitude = $lok[1];
        // $kode_pelanggan = $request->kode_pelanggan;
        $tglskrg = date("d");
        $bulanskrg = date("m");
        $tahunskrg = date("y");
        $hariini = date("Y-m-d");
        $tanggaljam = date("Y-m-d H:i:s");
        $format = $tahunskrg . $bulanskrg . $tglskrg;
        $smactivity = DB::table("aktifitas_smm")
            ->whereRaw('DATE(tanggal)="' . $hariini . '"')
            ->orderBy("kode_aktifitas", "desc")
            ->first();
        if ($smactivity == null) {
            $lastkode = '';
        } else {
            $lastkode = $smactivity->kode_aktifitas;
        }
        $kode_aktifitas  = buatkode($lastkode, $format, 4);

        if (isset($request->image)) {
            $image = $request->image;
            $folderPath = "public/uploads/aktifitas_smm/";
            $formatName = $kode_aktifitas;
            $image_parts = explode(";base64", $image);
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = $formatName . ".png";
            $file = $folderPath . $fileName;
        } else {
            $fileName = null;
        }

        try {
            // $cek = DB::table('activity_sm')
            //     ->whereRaw('DATE(tanggal)="' . $hariini . '"')
            //     ->where('id_user', Auth::user()->id)
            //     ->count();
            $cek = 0;
            if ($cek > 0) {
                return response()->json(['status' => 'error', 'message' => 'Data Sudah Ada'], 400);
            } else {
                $data = [
                    'kode_aktifitas' => $kode_aktifitas,
                    'tanggal' => $tanggaljam,
                    'id_user' => $id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'keterangan' => $activity,
                    'foto' => $fileName
                ];

                DB::table('aktifitas_smm')->insert($data);
                if (isset($request->image)) {
                    Storage::put($file, $image_base64);
                }
                $path_image = Storage::url('uploads/aktifitas_smm/' . $fileName);
                $group_wa = ['120363181708613638@g.us', '120363048652516047@g.us', '120363023468297226@g.us'];
                if ($role_name == 'gm marketing') {
                    foreach ($group_wa as $d) {
                        dispatch(new sendActivityJob($d, $nama, $cekuser->kode_cabang, $activity, $fileName, $role_name));
                    }
                } else {
                    dispatch(new sendActivityJob($id_group_wa, $nama, $cekuser->kode_cabang, $activity, $fileName, $role_name));
                }
                // $pesan = [
                //     'api_key' => 'B2TSubtfeWwb3eDHdIyoa0qRXJVgq8',
                //     'sender' => '6289670444321',
                //     'number' => $id_group_wa,
                //     'media_type' => 'image',
                //     'caption' => '*' . $nama . ': (' . $cekuser->kode_cabang . ')* ' . $activity,
                //     'url' => 'https://sfa.pacific-tasikmalaya.com/storage/uploads/smactivity/' . $fileName
                // ];

                // $curl = curl_init();

                // curl_setopt_array($curl, array(
                //     CURLOPT_URL => 'https://wa.pedasalami.com/send-media',
                //     CURLOPT_RETURNTRANSFER => true,
                //     CURLOPT_ENCODING => '',
                //     CURLOPT_MAXREDIRS => 10,
                //     CURLOPT_TIMEOUT => 0,
                //     CURLOPT_FOLLOWLOCATION => true,
                //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //     CURLOPT_CUSTOMREQUEST => 'POST',
                //     CURLOPT_POSTFIELDS => json_encode($pesan),
                //     CURLOPT_HTTPHEADER => array(
                //         'Content-Type: application/json'
                //     ),
                // ));

                // $response = curl_exec($curl);
                // curl_close($curl);

                return response()->json(['status' => 'success', 'message' => 'Data Berhasil Disimpan'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function getdetailaktifitas($id_user, $tanggal)
    {
        $aktifitas = AktifitasSMM::where('id_user', $id_user)->where('tanggal', $tanggal)->get();
        $user = User::where('id', $id_user)
            ->join('cabang', 'users.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $data['user'] = $user;
        $data['aktifitas'] = $aktifitas;
        return view('aktifitas_smm.getdetailaktifitas', $data);
    }
}
