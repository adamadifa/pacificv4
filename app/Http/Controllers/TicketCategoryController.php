<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class TicketCategoryController extends Controller
{
    public function index()
    {
        $categories = TicketCategory::orderBy('created_at', 'desc')->get();
        return view('utilities.ticket_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('utilities.ticket_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kategori' => 'required|unique:ticket_categories,kode_kategori',
            'nama_kategori' => 'required',
        ]);

        $template_file = null;
        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            $filename = 'template_' . strtolower($request->kode_kategori) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('templates'), $filename);
            $template_file = 'templates/' . $filename;
        }

        try {
            TicketCategory::create([
                'kode_kategori' => strtoupper($request->kode_kategori),
                'nama_kategori' => $request->nama_kategori,
                'keterangan' => $request->keterangan,
                'perlu_manager_dept' => $request->has('perlu_manager_dept') ? 1 : 0,
                'perlu_smm' => $request->has('perlu_smm') ? 1 : 0,
                'perlu_rsm' => $request->has('perlu_rsm') ? 1 : 0,
                'perlu_gm' => $request->has('perlu_gm') ? 1 : 0,
                'wajib_lampiran' => $request->has('wajib_lampiran') ? 1 : 0,
                'template_file' => $template_file,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            return Redirect::back()->with(messageSuccess('Master Kategori Tiket Berhasil Disimpan'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }

    public function edit($id)
    {
        $category = TicketCategory::findOrFail($id);
        return view('utilities.ticket_categories.edit', compact('category'));
    }

    public function update($id, Request $request)
    {
        $category = TicketCategory::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required',
        ]);

        $data = [
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->keterangan,
            'perlu_manager_dept' => $request->has('perlu_manager_dept') ? 1 : 0,
            'perlu_smm' => $request->has('perlu_smm') ? 1 : 0,
            'perlu_rsm' => $request->has('perlu_rsm') ? 1 : 0,
            'perlu_gm' => $request->has('perlu_gm') ? 1 : 0,
            'wajib_lampiran' => $request->has('wajib_lampiran') ? 1 : 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            $filename = 'template_' . strtolower($category->kode_kategori) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('templates'), $filename);
            $data['template_file'] = 'templates/' . $filename;
        }

        try {
            $category->update($data);
            return Redirect::back()->with(messageSuccess('Master Kategori Tiket Berhasil Diupdate'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }

    public function destroy($id)
    {
        try {
            $category = TicketCategory::findOrFail($id);
            $category->delete();
            return Redirect::back()->with(messageSuccess('Master Kategori Tiket Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return Redirect::back()->with(messageError($th->getMessage()));
        }
    }
}
