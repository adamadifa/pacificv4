<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\Ticketmessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = Ticket::query();
        $query->with(['category', 'user', 'managerDept', 'smmUser', 'rsmUser', 'gmUser', 'adminUser'])->withCount('messages');

        $roles_it_admin = ['super admin', 'admin maintenance'];

        if (!$user->hasRole($roles_it_admin)) {
            $query->where(function ($q) use ($user) {
                $q->where('id_user', $user->id)
                    ->orWhere('id_manager_dept', $user->id)
                    ->orWhere('id_smm', $user->id)
                    ->orWhere('id_rsm', $user->id)
                    ->orWhere('id_gm', $user->id);
            });
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->id_kategori_search)) {
            $query->where('id_kategori', $request->id_kategori_search);
        }

        if (!empty($request->status_search)) {
            if ($request->status_search == "pending") {
                $query->where('status', '0');
            } elseif ($request->status_search == "selesai") {
                $query->where('status', '1');
            } elseif ($request->status_search == "ditolak") {
                $query->where('status', '2');
            }
        }

        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_pengajuan', 'like', "%{$keyword}%")
                    ->orWhere('judul', 'like', "%{$keyword}%")
                    ->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }

        $query->orderBy('status', 'asc');
        $query->orderBy('updated_at', 'desc');
        $query->orderBy('kode_pengajuan', 'desc');

        $tickets = $query->paginate(15);
        $tickets->appends($request->all());

        $statsBaseQuery = Ticket::query();
        if (!$user->hasRole($roles_it_admin)) {
            $statsBaseQuery->where(function ($q) use ($user) {
                $q->where('id_user', $user->id)
                    ->orWhere('id_manager_dept', $user->id)
                    ->orWhere('id_smm', $user->id)
                    ->orWhere('id_rsm', $user->id)
                    ->orWhere('id_gm', $user->id);
            });
        }

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['categories'] = TicketCategory::where('is_active', true)->get();
        $data['ticket'] = $tickets;
        $data['stats'] = [
            'total' => (clone $statsBaseQuery)->count(),
            'pending' => (clone $statsBaseQuery)->where('status', '0')->where('posisi_approval', '!=', 'ADMIN')->count(),
            'proses_it' => (clone $statsBaseQuery)->where('status', '0')->where('posisi_approval', 'ADMIN')->count(),
            'selesai' => (clone $statsBaseQuery)->where('status', '1')->count(),
            'ditolak' => (clone $statsBaseQuery)->where('status', '2')->count(),
        ];

        return view('utilities.ticket.index', $data);
    }

    public function create()
    {
        $user = auth()->user();
        $categories = TicketCategory::where('is_active', true)->get();

        // SMM List in this branch
        $smmList = User::where('kode_cabang', $user->kode_cabang)
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['sales marketing manager']);
            })->get();

        // RSM List for this regional
        $cabangObj = Cabang::where('kode_cabang', $user->kode_cabang)->first();
        $kode_regional = $cabangObj ? $cabangObj->kode_regional : $user->kode_regional;
        $rsmList = User::where('kode_regional', $kode_regional)
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['regional sales manager']);
            })->get();

        // GM List
        $gmList = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['gm marketing', 'gm administrasi', 'gm operasional']);
        })->get();

        // Manager/Head List for PST
        if (empty($user->kode_jabatan) || $user->kode_jabatan == '') {
            // Regular staff: only show managers from the same department
            $managerList = User::where('kode_dept', $user->kode_dept)
                ->whereNotNull('kode_jabatan')
                ->where('kode_jabatan', '!=', '')
                ->orderBy('name', 'asc')
                ->get();
        } else {
            // Manager: show all managers
            $managerList = User::whereNotNull('kode_jabatan')
                ->where('kode_jabatan', '!=', '')
                ->orderBy('name', 'asc')
                ->get();
        }

        return view('utilities.ticket.create', compact('categories', 'smmList', 'rsmList', 'gmList', 'managerList'));
    }

    public function getCategoryDetail($id)
    {
        $category = TicketCategory::find($id);
        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Kategori tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $category
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'id_kategori' => 'required',
            'judul' => 'required',
            'keterangan' => 'required',
            'priority' => 'required',
        ]);

        $category = TicketCategory::findOrFail($request->id_kategori);

        if ($category->wajib_lampiran && !$request->hasFile('lampiran')) {
            return Redirect::back()->with(messageError('Kategori ini mewajibkan upload file lampiran!'));
        }

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = 'lampiran_tk_' . time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('uploads/tickets', $filename, 'public');
            $lampiranPath = 'storage/uploads/tickets/' . $filename;
        }

        $user = User::findOrFail(auth()->user()->id);

        $bulan = date("m", strtotime($request->tanggal));
        $tahun = substr(date("Y", strtotime($request->tanggal)), 2, 2);
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));

        $lastTicket = Ticket::whereBetween('tanggal', [$dari, $sampai])->orderBy('kode_pengajuan', 'desc')->first();
        $lastKode = $lastTicket != null ? $lastTicket->kode_pengajuan : '';
        $kode_pengajuan = buatkode($lastKode, "TK" . $bulan . $tahun, 4);

        // Approvers auto-detection
        $id_manager_dept = null;
        $id_smm = null;
        $id_rsm = null;
        $id_gm = null;
        $posisi_approval = 'ADMIN';

        if ($user->kode_cabang == 'PST') {
            // Cabang PST (Pusat): Goes to Manager Dept
            if ($request->has('perlu_manager_dept') && $request->id_manager_dept) {
                $id_manager_dept = $request->id_manager_dept;
            }

            if ($id_manager_dept && $id_manager_dept != $user->id) {
                $posisi_approval = 'MANAGER_DEPT';
            } else {
                $posisi_approval = 'ADMIN';
            }
        } else {
            // Non-PST (Branch): SMM -> RSM -> GM -> ADMIN
            if ($request->has('perlu_smm') && $request->id_smm) {
                $id_smm = $request->id_smm;
            }
            if ($request->has('perlu_rsm') && $request->id_rsm) {
                $id_rsm = $request->id_rsm;
            }
            if ($request->has('perlu_gm') && $request->id_gm) {
                $id_gm = $request->id_gm;
            }

            if ($id_smm && $id_smm != $user->id) {
                $posisi_approval = 'SMM';
            } elseif ($id_rsm && $id_rsm != $user->id) {
                $posisi_approval = 'RSM';
            } elseif ($id_gm && $id_gm != $user->id) {
                $posisi_approval = 'GM';
            } else {
                $posisi_approval = 'ADMIN';
            }
        }

        try {
            Ticket::create([
                'kode_pengajuan' => $kode_pengajuan,
                'tanggal' => $request->tanggal,
                'id_kategori' => $request->id_kategori,
                'judul' => $request->judul,
                'no_bukti' => $request->no_bukti,
                'keterangan' => $request->keterangan,
                'priority' => $request->priority,
                'lampiran' => $lampiranPath,
                'link' => $request->link,
                'status' => '0',
                'posisi_approval' => $posisi_approval,
                'id_user' => $user->id,
                'kode_cabang' => $user->kode_cabang,
                'kode_dept' => $user->kode_dept,
                'id_manager_dept' => $id_manager_dept,
                'id_smm' => $id_smm,
                'id_rsm' => $id_rsm,
                'id_gm' => $id_gm,
            ]);

            return Redirect::back()->with(messageSuccess('Tiket Ajuan Berhasil Dibuat'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }

    public function edit($kode_pengajuan)
    {
        $ticket = Ticket::with(['category', 'user'])->where('kode_pengajuan', $kode_pengajuan)->firstOrFail();
        $categories = TicketCategory::where('is_active', true)->get();

        $owner = $ticket->user;

        // SMM List in owner's branch
        $smmList = User::where('kode_cabang', $owner->kode_cabang)
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['sales marketing manager']);
            })->get();

        // RSM List for owner's regional
        $cabangObj = Cabang::where('kode_cabang', $owner->kode_cabang)->first();
        $kode_regional = $cabangObj ? $cabangObj->kode_regional : $owner->kode_regional;
        $rsmList = User::where('kode_regional', $kode_regional)
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['regional sales manager']);
            })->get();

        // GM List
        $gmList = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['gm marketing', 'gm administrasi', 'gm operasional']);
        })->get();

        // Manager/Head List for PST
        if (empty($owner->kode_jabatan) || $owner->kode_jabatan == '') {
            // Regular staff: only show managers from the same department
            $managerList = User::where('kode_dept', $owner->kode_dept)
                ->whereNotNull('kode_jabatan')
                ->where('kode_jabatan', '!=', '')
                ->orderBy('name', 'asc')
                ->get();
        } else {
            // Manager: show all managers
            $managerList = User::whereNotNull('kode_jabatan')
                ->where('kode_jabatan', '!=', '')
                ->orderBy('name', 'asc')
                ->get();
        }

        return view('utilities.ticket.edit', compact('ticket', 'categories', 'smmList', 'rsmList', 'gmList', 'managerList'));
    }

    public function update($kode_pengajuan, Request $request)
    {
        $kode_pengajuan = Crypt::decrypt($kode_pengajuan);
        $ticket = Ticket::where('kode_pengajuan', $kode_pengajuan)->firstOrFail();

        $request->validate([
            'tanggal' => 'required',
            'id_kategori' => 'required',
            'judul' => 'required',
            'keterangan' => 'required',
            'priority' => 'required',
        ]);

        $data = [
            'tanggal' => $request->tanggal,
            'id_kategori' => $request->id_kategori,
            'judul' => $request->judul,
            'no_bukti' => $request->no_bukti,
            'keterangan' => $request->keterangan,
            'priority' => $request->priority,
            'link' => $request->link,
        ];

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = 'lampiran_tk_' . time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('uploads/tickets', $filename, 'public');
            $data['lampiran'] = 'storage/uploads/tickets/' . $filename;
        }

        if ($ticket->status == '0') {
            if ($ticket->kode_cabang == 'PST') {
                $id_manager_dept = null;
                if ($request->has('perlu_manager_dept') && $request->id_manager_dept) {
                    $id_manager_dept = $request->id_manager_dept;
                }
                $data['id_manager_dept'] = $id_manager_dept;

                if (empty($ticket->manager_approved_at)) {
                    if ($id_manager_dept && $id_manager_dept != $ticket->id_user) {
                        $data['posisi_approval'] = 'MANAGER_DEPT';
                    } else {
                        $data['posisi_approval'] = 'ADMIN';
                    }
                }
            } else {
                $id_smm = null;
                $id_rsm = null;
                $id_gm = null;

                if ($request->has('perlu_smm') && $request->id_smm) {
                    $id_smm = $request->id_smm;
                }
                if ($request->has('perlu_rsm') && $request->id_rsm) {
                    $id_rsm = $request->id_rsm;
                }
                if ($request->has('perlu_gm') && $request->id_gm) {
                    $id_gm = $request->id_gm;
                }

                $data['id_smm'] = $id_smm;
                $data['id_rsm'] = $id_rsm;
                $data['id_gm'] = $id_gm;

                // Recalculate posisi_approval if no approvals have been made yet
                if (empty($ticket->smm_approved_at) && empty($ticket->rsm_approved_at) && empty($ticket->gm_approved_at)) {
                    if ($id_smm && $id_smm != $ticket->id_user) {
                        $data['posisi_approval'] = 'SMM';
                    } elseif ($id_rsm && $id_rsm != $ticket->id_user) {
                        $data['posisi_approval'] = 'RSM';
                    } elseif ($id_gm && $id_gm != $ticket->id_user) {
                        $data['posisi_approval'] = 'GM';
                    } else {
                        $data['posisi_approval'] = 'ADMIN';
                    }
                }
            }
        }

        try {
            Ticket::where('kode_pengajuan', $kode_pengajuan)->update($data);
            return Redirect::back()->with(messageSuccess('Tiket Ajuan Berhasil Diupdate'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }

    public function destroy($kode_pengajuan)
    {
        $kode_pengajuan = Crypt::decrypt($kode_pengajuan);
        try {
            Ticket::where('kode_pengajuan', $kode_pengajuan)->delete();
            return Redirect::back()->with(messageSuccess('Tiket Ajuan Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }

    public function approve($kode_pengajuan)
    {
        $ticket = Ticket::with(['category', 'user', 'managerDept', 'smmUser', 'rsmUser', 'gmUser', 'adminUser'])
            ->where('kode_pengajuan', $kode_pengajuan)
            ->firstOrFail();

        return view('utilities.ticket.approve', compact('ticket'));
    }

    public function storeapprove($kode_pengajuan, Request $request)
    {
        $kode_pengajuan = Crypt::decrypt($kode_pengajuan);
        $ticket = Ticket::with('category')->where('kode_pengajuan', $kode_pengajuan)->firstOrFail();
        $user = User::findOrFail(auth()->user()->id);

        // Check if Decline / Reject
        if ($request->has('decline')) {
            Ticket::where('kode_pengajuan', $kode_pengajuan)->update([
                'status' => '2',
                'posisi_approval' => 'DITOLAK',
                'catatan_decline' => $request->catatan_decline ?? 'Pengajuan ditolak oleh ' . $user->name,
            ]);
            return Redirect::back()->with(messageSuccess('Tiket Ajuan Telah Ditolak'));
        }

        // Processing Approval steps
        $posisi = $ticket->posisi_approval;
        $category = $ticket->category;
        $updateData = [];

        if ($posisi == 'MANAGER_DEPT') {
            $updateData['manager_approved_at'] = date('Y-m-d H:i:s');
            $updateData['posisi_approval'] = 'ADMIN';
        } elseif ($posisi == 'SMM') {
            $updateData['smm_approved_at'] = date('Y-m-d H:i:s');
            if ($ticket->id_rsm) {
                $updateData['posisi_approval'] = 'RSM';
            } elseif ($ticket->id_gm) {
                $updateData['posisi_approval'] = 'GM';
            } else {
                $updateData['posisi_approval'] = 'ADMIN';
            }
        } elseif ($posisi == 'RSM') {
            $updateData['rsm_approved_at'] = date('Y-m-d H:i:s');
            if ($ticket->id_gm) {
                $updateData['posisi_approval'] = 'GM';
            } else {
                $updateData['posisi_approval'] = 'ADMIN';
            }
        } elseif ($posisi == 'GM') {
            $updateData['gm_approved_at'] = date('Y-m-d H:i:s');
            $updateData['posisi_approval'] = 'ADMIN';
        } elseif ($posisi == 'ADMIN') {
            $updateData['status'] = '1';
            $updateData['id_admin'] = $user->id;
            $updateData['tanggal_selesai'] = date('Y-m-d');
            $updateData['posisi_approval'] = 'SELESAI';
        }

        try {
            Ticket::where('kode_pengajuan', $kode_pengajuan)->update($updateData);
            return Redirect::back()->with(messageSuccess('Persetujuan Tiket Berhasil Disimpan'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }

    public function downloadTemplate($id_kategori)
    {
        $category = TicketCategory::findOrFail($id_kategori);
        if (!$category->template_file || !file_exists(public_path($category->template_file))) {
            return Redirect::back()->with(messageError('File template tidak ditemukan.'));
        }

        return response()->download(public_path($category->template_file));
    }

    public function message($kode_pengajuan)
    {
        $ticket = Ticket::where('kode_pengajuan', $kode_pengajuan)->firstOrFail();
        $ticketmessage = Ticketmessage::where('kode_pengajuan', $kode_pengajuan)
            ->select('tickets_messages.*', 'users.name')
            ->join('users', 'tickets_messages.id_user', '=', 'users.id')
            ->orderBy('tickets_messages.created_at', 'asc')
            ->get();

        $data['ticket'] = $ticket;
        $data['ticketmessage'] = $ticketmessage;
        $data['kode_pengajuan'] = $kode_pengajuan;

        return view('utilities.ticket.message', $data);
    }

    public function storemessage($kode_pengajuan, Request $request)
    {
        $kode_pengajuan = Crypt::decrypt($kode_pengajuan);
        $request->validate([
            'message' => 'required',
        ]);

        try {
            Ticketmessage::create([
                'kode_pengajuan' => $kode_pengajuan,
                'message' => $request->message,
                'id_user' => auth()->user()->id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pesan berhasil dikirim'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function cetakLaporan(Request $request)
    {
        if (!auth()->user()->hasRole('super admin')) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang dapat mengakses cetak laporan tiket.');
        }

        $query = Ticket::query();
        $query->with(['category', 'user', 'managerDept', 'smmUser', 'rsmUser', 'gmUser', 'adminUser']);

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->id_kategori_search)) {
            $query->where('id_kategori', $request->id_kategori_search);
        }

        if (!empty($request->status_search)) {
            if ($request->status_search == "pending") {
                $query->where('status', '0');
            } elseif ($request->status_search == "selesai") {
                $query->where('status', '1');
            } elseif ($request->status_search == "ditolak") {
                $query->where('status', '2');
            }
        }

        $tickets = $query->orderBy('tanggal', 'desc')->get();

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $categories = TicketCategory::all();

        return view('utilities.ticket.cetak_laporan', compact('tickets', 'cabang', 'categories'));
    }
}
