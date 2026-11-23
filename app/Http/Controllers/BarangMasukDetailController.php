<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKategori;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\BarangSupplier;
use App\Models\Menu;
use App\Models\HakAkses;
use App\Models\Karyawan;
use App\Models\Log;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class BarangMasukDetailController extends Controller
{
    public function index($id_bapb, $id)
    {
        $menu_id = $id;
        $id_bapb = $id_bapb;
        $bapb = BarangMasuk::where('id', $id_bapb)->first();
        $user = Auth::user();
        $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
        $supplier = BarangSupplier::where('id_proyek', $user->id_proyek)->get();
        // dd($supplier);
        $kategori = BarangKategori::where('id_proyek', $user->id_proyek)->get();
        $parent = HakAkses::where('id_jabatan', $karyawan->id_jabatan)
            ->with('Menu.Children')
            ->orderBy('nama_menu')
            ->get();
        return view('gudang/barang_masuk/detail', compact(
            'parent',
            'menu_id',
            'id_bapb',
            'bapb',
            'kategori',
            'supplier',
        ));
    }
    public function get_barang($id_kategori)
    {
        // dd($id_kategori);
        $get_barang = Barang::where('id_kategori', $id_kategori)->orderBy('nama_barang', 'ASC')->get();
        // dd($get_barang);
        echo "<option value=''>Pilih Barang...</option>";
        foreach ($get_barang as $barang) {
            echo "<option value='$barang->id'>$barang->kode_barang - $barang->nama_barang ($barang->satuan)</option>";
        }
    }
    public function create(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'menu_id' => 'required',
                'id_barang_masuk' => 'required',
                'no_bapb' => 'required',
                'tanggal_masuk' => 'required',
                'surat_jalan' => 'required',
                'id_kategori' => 'required',
                'id_barang' => 'required',
                'jumlah' => 'required',
                'id_supplier' => 'required',
                'keterangan' => 'required',
                'foto' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ];

            $messages = [
                'menu_id.required' => 'Menu wajib diisi',
                'id_barang_masuk.required' => 'Barang masuk wajib diisi',
                'no_bapb.required' => 'Nomor BAPB wajib diisi',
                'tanggal_masuk.required' => 'Tanggal masuk wajib diisi',
                'surat_jalan.required' => 'Surat jalan wajib diisi',
                'id_kategori.required' => 'Kategori barang wajib diisi',
                'id_barang.required' => 'Barang wajib diisi',
                'jumlah.required' => 'Jumlah barang wajib diisi',
                'id_supplier.required' => 'Supplier wajib diisi',
                'keterangan.required' => 'Keterangan wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }
            $user = Auth::user();
            $menu = Menu::where('id', $request->menu_id)->first();
            $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
            $kode = Uuid::uuid4();

            $barang = Barang::where('id', $request->id_barang)->first();
            $supplier = BarangSupplier::where('id', $request->id_supplier)->first();

            if ($request->hasFile('foto')) {
                $file = $request->file('foto')->store('foto_surat_jalan', 'public');
                $file_save = basename($file);
            }
            // dd($barang);

            BarangMasukDetail::create([
                'id' => $kode,
                'id_barang_masuk' => $request->id_barang_masuk,
                'tanggal_masuk' => $request->tanggal_masuk,
                'surat_jalan' => $request->surat_jalan,

                'kode_barang' => $barang->kode_barang,
                'id_barang' => $barang->id,
                'nama_barang' => $barang->nama_barang,
                'satuan' => $barang->satuan,
                'no_bapb' => $request->no_bapb,
                'jumlah' => $request->jumlah,

                'id_supplier' => $supplier->id,
                'nama_supplier' => $supplier->nama_supplier,
                'foto_surat_jalan' => isset($file_save) ? $file_save : null,

                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'keterangan' => $request->keterangan,
                'status' => 1,
                'created_at' => now(),
            ]);

            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'CREATE',
                'deskripsi' => 'Menambah barang baru ' . $barang->nama_barang,
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'id_tabel_baru' => $kode,
                'nama_tabel' => 'barang_masuk_detail',
                'id_tabel_lama' => null,
                'keterangan' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function barang_masuk_detail_dt($id)
    {
        $user = Auth::user();
        $data = BarangMasukDetail::where('id_barang_masuk', $id)
            ->get();


        return DataTables::of($data)
            ->addColumn('tanggal_masuk', function ($row) {
                $tgl = Carbon::parse($row->tanggal_masuk)->format('d-m-Y');
                return $tgl;
            })
            ->addColumn('aksi', function ($row) {
                $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-nama_barang="' . $row->nama_barang . '">
                                 <i class="bi bi-pencil"></i></button>';
                $btn = $action . '<button id="btn_delete_datatable" class="btn btn-danger m-1" data-id="' . $row->id . '"><i class="bi bi-trash"></i></button>';
                return $btn;
            })
            ->addColumn('foto_surat_jalan', function ($row) {
                $action = '';

                if ($row->foto_surat_jalan != null) {
                    $action = '<a href="' . asset('storage/foto_surat_jalan/' . $row->foto_surat_jalan) . '" target="_blank" class="btn btn-info btn-sm">
                       <i class="bi bi-camera text-white"></i>
                   </a>';
                }

                return $action;
            })
            ->rawColumns([
                'aksi',
                'tanggal_masuk',
                'foto_surat_jalan',
            ])
            ->make(true);
    }

    public function delete(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'id_barang_masuk_delete' => 'required',
                'menu_id' => 'required|uuid|exists:master_menu,id',

            ];
            $messages = [
                'id_bapb_delete.required' => 'Terjadi kesalahan',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            $barang_masuk = BarangMasukDetail::where('id', $request->id_barang_masuk_delete)->first();
            if ($barang_masuk->foto_surat_jalan) {
                $oldSignaturePath = 'foto_surat_jalan/' . basename($barang_masuk->foto_surat_jalan);
                if (Storage::disk('public')->exists($oldSignaturePath)) {
                    Storage::disk('public')->delete($oldSignaturePath);
                }
            }
            $user = Auth::user();
            $menu = Menu::where('id', $request->menu_id)->first();
            $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'DELETE',
                'deskripsi' => 'Menghapus barang masuk ' . $barang_masuk->no_barang_masuk,
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'nama_tabel' => 'barang_masuk',
                'id_tabel_lama' => $barang_masuk->id,
                'keterangan' => 'success',
            ]);

            $barang_masuk->delete();

            return response()->json([
                'success' => true,
                'message' => 'No. BAPB berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'nama_barang' => 'required',
                'menu_id' => 'required|uuid|exists:master_menu,id',

            ];
            $messages = [
                'nama_barang.required' => 'barang wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }
            $user = Auth::user();
            $menu = Menu::where('id', $request->menu_id)->first();
            $karyawan = Karyawan::where('id', $user->id_karyawan)->first();

            BarangMasukDetail::where('id', $request->id_barang_masuk_detail_update)->update([
                'nama_barang' => $barang->nama_barang,
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'updated_at' => now(),
            ]);
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'UPDATE',
                'deskripsi' => 'Update data barang ' . $barang->nama_barang,
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'id_tabel_baru' =>  $request->id_barang_masuk_detail_update,
                'nama_tabel' => 'barang_masuk_detail',
                'id_tabel_lama' => null,
                'keterangan' => 'success',
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
