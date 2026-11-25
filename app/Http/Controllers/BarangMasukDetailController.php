<?php

namespace App\Http\Controllers;

use App\Models\Area;
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
        // dd($menu_id);
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
            $kategori = BarangKategori::where('id', $request->id_kategori)->first();

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

                'id_kategori' => $kategori->id,
                'nama_kategori' => $kategori->nama_kategori,

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
                'deskripsi' => 'Menambah barang baru ',
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
                                data-tanggal_masuk="' . $row->tanggal_masuk . '"
                                data-surat_jalan="' . $row->surat_jalan . '"
                                data-nama_kategori="' . $row->nama_kategori . '"
                                data-nama_supplier="' . $row->nama_supplier . '"
                                data-nama_barang="' . $row->nama_barang . '"
                                data-jumlah="' . $row->jumlah . '"
                                data-keterangan ="' . $row->keterangan  . '"
                                data-foto="' . $row->foto_surat_jalan . '">
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
    public function selesai_ttd(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'id_bapb' => 'required',
                'signature' => [
                    'required',
                    'string',
                    'regex:/\Adata:image\/png;base64,[A-Za-z0-9+\/]+={0,2}\z/',
                ],
                'menu_id' => 'required|uuid|exists:master_menu,id',

            ];
            $messages = [
                'id_bapb.required' => 'id BAPB wajib diisi',
                'signature.required' => 'Tanda tangan wajib diisi',
                'signature.string' => 'Format tanda tangan tidak valid',
                'signature.regex' => 'Format tanda tangan tidak valid',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }
            $user = Auth::user();
            $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
            $menu = Menu::where('id', $request->menu_id)->first();
            $area = Area::where('id', $karyawan->id_area)->first();
            $proyek = Proyek::where('id', $user->id_proyek)->first();

            // dd($karyawan);
            $bapb = BarangMasuk::where('id', $request->id_bapb)->first();
            $detail = BarangMasukDetail::where('id_barang_masuk', $request->id_bapb)
                ->doesntExist();
            // dd($detail);

            if ($detail) {
                return response()->json([
                    'status' => false,
                    'message' => 'Barang masuk masih belum diisi!'
                ], 402);
            }
            if ($bapb->ttd_karyawan) {
                $oldSignaturePath = 'signatures/' . basename($bapb->ttd_karyawan);
                if (Storage::disk('public')->exists($oldSignaturePath)) {
                    Storage::disk('public')->delete($oldSignaturePath);
                }
            }
            $image = $request->signature;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);

            // nama file
            $fileName = 'signature_' . time() . '.png';
            // dd($fileName);
            // simpan ke storage
            Storage::disk('public')->put(
                'signatures/' . $fileName,
                base64_decode($image)
            );

            $bapb->update([
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'ttd_karyawan' => $fileName,

                'id_atasan1' => $karyawan->id,
                'nama_atasan1' => $karyawan->nama_karyawan,
                'ttd_atasan1' => $fileName,
                'status_atasan1' => 1,

                'status' => 1,

                'id_area' => $area->id,
                'nama_area' => $area->nama_area,

                'id_proyek' => $proyek->id,
                'nama_proyek' => $proyek->nama_proyek,
                'updated_at' => now(),
            ]);
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'UPDATE',
                'deskripsi' => 'Mengupdate BAPB ' . $request->no_bapb,
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'nama_tabel' => 'barang_masuk',
                'id_tabel_lama' => null,
                'keterangan' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'No. BAPB berhasil disimpan',
                'data' => $request->menu_id
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
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
                'nama_tabel' => 'barang_masuk_detail',
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
                'menu_id' => 'required',
                'id_barang_masuk_detail' => 'required|exists:barang_masuk_detail,id',
                'tanggal_masuk' => 'required',
                'surat_jalan' => 'required',
                'jumlah' => 'required',
                'keterangan' => 'required',
                'foto' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ];

            $messages = [
                'menu_id.required' => 'Menu wajib diisi',
                'tanggal_masuk.required' => 'Tanggal masuk wajib diisi',
                'surat_jalan.required' => 'Surat jalan wajib diisi',
                'jumlah.required' => 'Jumlah barang wajib diisi',
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
            $detail = BarangMasukDetail::where('id', $request->id_barang_masuk_detail)->firstOrFail();

            $oldPhotoPath = $detail->foto_surat_jalan
                ? 'foto_surat_jalan/' . basename($detail->foto_surat_jalan)
                : null;

            $photoPath = null;
            if ($request->hasFile('foto')) {
                $photoPath = $request->file('foto')->store('foto_surat_jalan', 'public');
            }

            $detail->update([
                'tanggal_masuk' => $request->tanggal_masuk,
                'surat_jalan' => $request->surat_jalan,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
                'foto_surat_jalan' => $photoPath ? basename($photoPath) : $detail->foto_surat_jalan,
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'updated_at' => now(),
            ]);
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'UPDATE',
                'deskripsi' => 'Update data barang masuk ',
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'id_tabel_baru' =>  $request->id_barang_masuk_detail,
                'nama_tabel' => 'barang_masuk_detail',
                'id_tabel_lama' => null,
                'keterangan' => 'success',
            ]);

            if ($photoPath && $oldPhotoPath && Storage::disk('public')->exists($oldPhotoPath)) {
                Storage::disk('public')->delete($oldPhotoPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
