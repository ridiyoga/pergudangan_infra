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

class BarangMasukDetailKomersialController extends Controller
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
        return view('gudang/barang_masuk_komersial/detail', compact(
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
    public function barang_masuk_komersial_detail_dt($id)
    {
        $user = Auth::user();
        $total_harga = BarangMasukDetail::where('id_barang_masuk', $id)
            ->sum('harga');
        $data = BarangMasukDetail::where('id_barang_masuk', $id)
            ->get();


        return DataTables::of($data)
            ->addColumn('tanggal_masuk', function ($row) {
                $tgl = Carbon::parse($row->tanggal_masuk)->format('d-m-Y');
                return $tgl;
            })
            ->addColumn('aksi', function ($row) {
                if ($row->status == 1 || $row->status == 2) {
                    $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-tanggal_masuk="' . $row->tanggal_masuk . '"
                                data-surat_jalan="' . $row->surat_jalan . '"
                                data-nama_kategori="' . $row->nama_kategori . '"
                                data-nama_supplier="' . $row->nama_supplier . '"
                                data-nama_barang="' . $row->nama_barang . '"
                                data-jumlah="' . $row->jumlah . '"
                                data-satuan="' . $row->satuan . '"
                                data-harga="' . $row->harga . '"
                                data-keterangan ="' . $row->keterangan  . '"
                                data-foto="' . $row->foto_surat_jalan . '">
                                 <i class="bi bi-pencil"></i></button>';
                    return $action;
                } else {
                    $action = '<button class="btn btn-warning m-1" id="btn_update_datatable" disabled>
                                <i class="bi bi-pencil"></i>
                            </button>';
                    return $action;
                }
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
            ->addColumn('harga', function ($row) use ($id) {

                $harga = 'Rp ' . number_format($row->harga, 0, ',', '.');
                return $harga;
            })
            ->rawColumns([
                'aksi',
                'tanggal_masuk',
                'foto_surat_jalan',
            ])
            ->with('total_harga', 'Rp ' . number_format($total_harga, 0, ',', '.'))
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

            // dd($karyawan);
            $bapb = BarangMasuk::where('id', $request->id_bapb)->first();

            if ($bapb->ttd_atasan2) {
                $oldSignaturePath = 'signatures/' . basename($bapb->ttd_atasan2);
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

            $detail = BarangMasukDetail::where('id_barang_masuk', $request->id_bapb)
                ->whereNull('harga')
                ->exists();

            if ($detail) {
                return response()->json([
                    'status' => false,
                    'message' => 'Masih terdapat harga barang yang belum diisi!'
                ], 402);
            }

            $bapb->update([
                'id_atasan2' => $karyawan->id,
                'nama_atasan2' => $karyawan->nama_karyawan,
                'ttd_atasan2' => $fileName,
                'status_atasan2' => 2,

                'status' => 2,
                'waktu_atasan2' => now(),
                'updated_at' => now(),
            ]);
            BarangMasukDetail::where('id_barang_masuk', $request->id_bapb)->update([
                'status' => 2
            ]);
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'UPDATE',
                'deskripsi' => 'Selesai memberikan harga pada BAPB ' . $request->no_bapb,
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

    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'menu_id' => 'required',
                'id_barang_masuk_detail' => 'required|exists:barang_masuk_detail,id',
                'harga' => 'required',
            ];

            $messages = [
                'menu_id.required' => 'Menu wajib diisi',
                'harga.required' => 'Harga wajib diisi',
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
            $harga = (int) str_replace('.', '', $request->harga);
            $detail->update([
                'harga' => $harga,
                'updated_at' => now(),
            ]);
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'UPDATE',
                'deskripsi' => 'Mengedit harga barang ' . $detail->nama_barang . ' : ' . $request->harga,
                'nama_tabel' => 'barang_masuk_detail ',
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'id_tabel_baru' =>  $request->id_barang_masuk_detail,
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
