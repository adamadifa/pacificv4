<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function store(Request $request)
    {
        $id = auth()->user()->id;
        $id_group_wa = auth()->user()->id->id_group_wa;
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
        $smactivity = DB::table("activity_sm")
            ->whereRaw('DATE(tanggal)="' . $hariini . '"')
            ->orderBy("kode_act_sm", "desc")
            ->first();
        if ($smactivity == null) {
            $lastkode = '';
        } else {
            $lastkode = $smactivity->kode_act_sm;
        }
        $kode_act_sm  = buatkode($lastkode, $format, 4);

        if (isset($request->image)) {
            $image = $request->image;
            $folderPath = "public/uploads/smactivity/";
            $formatName = $kode_act_sm;
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
                echo "error|Anda Sudah Melakukan Aktifitas pada Pelanggan Ini !";
            } else {
                $data = [
                    'kode_act_sm' => $kode_act_sm,
                    'tanggal' => $tanggaljam,
                    'id_user' => $id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'aktifitas' => $activity,
                    'foto' => $fileName
                ];

                DB::table('activity_sm')->insert($data);
                if (isset($request->image)) {
                    Storage::put($file, $image_base64);
                }
                $path_image = Storage::url('uploads/smactivity/' . $fileName);

                dispatch(new sendActivityJob($id_group_wa, $nama, $cekuser->kode_cabang, $activity, $fileName, $cekuser->level));
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
                echo "success|Data Berhasil Disimpan";
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
