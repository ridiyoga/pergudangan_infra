<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\BarangSupplier;
use App\Models\Menu;
use App\Models\Jabatan;
use App\Models\HakAkses;
use App\Models\Karyawan;
use App\Models\Log;
use App\Models\Proyek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class BarangMasukController extends Controller
{
    public function index($id)
    {
        $user = Auth::user();
        $menu_id = $id;
        $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
        $supplier = BarangSupplier::where('id_proyek', $user->id_proyek)->get();
        $parent = HakAkses::where('id_jabatan', $karyawan->id_jabatan)
            ->with('Menu.Children')
            ->orderBy('nama_menu')
            ->get();
        return view('gudang/barang_masuk/index', compact(
            'parent',
            'menu_id',
            'supplier'
        ));
    }
    public function create(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'no_bapb' => 'required',
                'tanggal_masuk' => 'required',
                'id_supplier' => 'required',
                // 'signature' => [
                //     'required',
                //     'string',
                //     'regex:/\Adata:image\/png;base64,[A-Za-z0-9+\/]+={0,2}\z/',
                // ],
                'menu_id' => 'required|uuid|exists:master_menu,id',

            ];
            $messages = [
                'no_bapb.required' => 'No. BAPB wajib diisi',
                'tanggal_masuk.required' => 'Tanggal masuk wajib diisi',
                'id_supplier.required' => 'Supplier wajib diisi',
                // 'signature.required' => 'Tanda tangan wajib diisi',
                // 'signature.string' => 'Format tanda tangan tidak valid',
                // 'signature.regex' => 'Format tanda tangan tidak valid',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }
            $kode = Uuid::uuid4();


            $user = Auth::user();
            $menu = Menu::where('id', $request->menu_id)->first();
            $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
            $supplier = BarangSupplier::where('id', $request->id_supplier)->first();
            // dd($supplier);
            $area = Area::where('id', $karyawan->id_area)->first();
            $proyek = Proyek::where('id', $user->id_proyek)->first();
            $kode = Uuid::uuid4();
            $bapb_unik = BarangMasuk::where('no_bapb', $request->no_bapb)
                ->where('id_proyek', $user->id_proyek)
                ->exists();
            if ($bapb_unik) {
                Log::create([
                    'id' => Uuid::uuid4(),
                    'id_karyawan' => $karyawan->id,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'aksi' => 'CREATE',
                    'deskripsi' => 'Tertolak karena NO. BAPB tidak unik ' . $request->no_bapb,
                    'id_menu' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'id_tabel_baru' => $kode,
                    'nama_tabel' => 'barang_masuk',
                    'id_tabel_lama' => null,
                    'keterangan' => 'failed',
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'No. BAPB sudah digunakan dalam proyek ini!'
                ], 422);
            }
            // dd($area);
            // $image = $request->signature;
            // $image = str_replace('data:image/png;base64,', '', $image);
            // $image = str_replace(' ', '+', $image);

            // $fileName = 'signature_' . time() . '.png';
            // // dd($fileName);
            // // simpan ke storage
            // Storage::disk('public')->put(
            //     'signatures/' . $fileName,
            //     base64_decode($image)
            // );
            BarangMasuk::create([
                'id' => $kode,
                'no_bapb' => $request->no_bapb,

                'id_supplier' => $supplier->id,
                'nama_supplier' => $supplier->nama_supplier,

                'tanggal_masuk' => $request->tanggal_masuk,

                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                // 'ttd_karyawan' => $fileName,


                'id_atasan1' => $karyawan->id,
                'nama_atasan1' => $karyawan->nama_karyawan,
                // 'ttd_atasan1' => $fileName,
                'status_atasan1' => 1,
                'waktu_atasan1' => now(),

                'id_area' => $area->id,
                'nama_area' => $area->nama_area,

                'id_proyek' => $proyek->id,
                'nama_proyek' => $proyek->nama_proyek,
                'created_at' => now(),
            ]);

            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'CREATE',
                'deskripsi' => 'Menambah BAPB baru ' . $request->no_bapb,
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'id_tabel_baru' => $kode,
                'nama_tabel' => 'barang_masuk',
                'id_tabel_lama' => null,
                'keterangan' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'No. BAPB berhasil disimpan',
                'id_bapb' => $kode,
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function barang_masuk_dt($id)
    {

        $user = Auth::user();
        $data = BarangMasuk::whereNull('status')
            ->orWhere('status', 1)->where('id_proyek', $user->id_proyek)->get();
        // dd($id);

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) use ($id) {
                $see = '<a href="' . url("/barang_masuk_detail/{$row->id}/{$id}") . '"
                            class="btn btn-info m-1"
                            data-id="{$row->id}">
                            <i class="bi bi-database-down"></i>
                        </a>';
                $update = $see . '<button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-no_bapb="' . $row->no_bapb . '"
                                data-tanggal_masuk="' . $row->tanggal_masuk . '"
                                data-nama_supplier="' . $row->nama_supplier . '">
                                <i class="bi bi-pencil"></i></button>';
                return $update;
            })
            ->addColumn('status', function ($row) use ($id) {
                if ($row->status == 1) {
                    return '<span class="badge bg-success p-2">DITANDATANGANI KEPALA GUDANG</span>';
                } else {
                    return '<span class="badge bg-secondary p-2">BELUM DITANDATANGANI</span>';
                }
            })
            ->addColumn('ttd_karyawan', function ($row) {

                if (!$row->ttd_karyawan) {
                    return '-';
                }

                $signature = asset('storage/signatures/' . basename($row->ttd_karyawan));

                return '<img src="' . $signature . '"
                        style="
                            max-width: 120px;
                            max-height: 60px;
                            width: auto;
                            height: auto;
                            object-fit: contain;
                            display: block;
                            margin: auto;
                        ">';
            })
            ->rawColumns([
                'aksi',
                'status',
                'ttd_karyawan'
            ])
            ->make(true);
    }
    public function barang_masuk_dt2($id)
    {
        // dd($request->all());


        $user = Auth::user();
        $data = BarangMasuk::where('status', 2)
            ->where('id_proyek', $user->id_proyek)
            ->get();
        // dd($id);

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) use ($id) {
                $see = '<a href="' . url("/barang_masuk_detail/{$row->id}/{$id}") . '"
                            class="btn btn-info m-1"
                            data-id="{$row->id}">
                            <i class="bi bi-database-down"></i>
                        </a>';
                $update = $see . '<button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-no_bapb="' . $row->no_bapb . '">
                                <i class="bi bi-pencil"></i></button>';
                return $update;
            })
            ->addColumn('status', function ($row) use ($id) {
                if ($row->status == 2) {
                    return '<span class="badge bg-success p-2">DITANDATANGANI KOMERSIAL</span>';
                } else {
                    return '<span class="badge bg-secondary p-2">BELUM DITANDATANGANI</span>';
                }
            })
            ->addColumn('ttd_karyawan', function ($row) {

                if (!$row->ttd_karyawan) {
                    return '-';
                }

                $signature = asset('storage/signatures/' . basename($row->ttd_karyawan));

                return '<img src="' . $signature . '"
                        style="
                            max-width: 120px;
                            max-height: 60px;
                            width: auto;
                            height: auto;
                            object-fit: contain;
                            display: block;
                            margin: auto;
                        ">';
            })->addColumn('ttd_atasan2', function ($row) {

                if (!$row->ttd_atasan2) {
                    return '-';
                }

                $signature = asset('storage/signatures/' . basename($row->ttd_atasan2));

                return '<img src="' . $signature . '"
                        style="
                            max-width: 120px;
                            max-height: 60px;
                            width: auto;
                            height: auto;
                            object-fit: contain;
                            display: block;
                            margin: auto;
                        ">';
            })
            ->addColumn('created_at', function ($row) use ($id) {
                $created_at = Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
                return $created_at;
            })

            ->rawColumns([
                'aksi',
                'status',
                'ttd_karyawan',
                'ttd_atasan2',
                'created_at',
            ])
            ->make(true);
    }
    public function barang_masuk_dt3(Request $request, $id)
    {
        // dd($request->all());
        $start = Carbon::parse($request->start_date)->format('Y-m-d');
        $end = Carbon::parse($request->end_date)->format('Y-m-d');
        // dd($start, $end);
        $user = Auth::user();
        $data = BarangMasuk::where('status', 3)
            ->where('id_proyek', $user->id_proyek)
            ->whereBetween('tanggal_masuk', [$start, $end])
            ->get();
        // dd($id);

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) use ($id) {
                $see = '<a href="' . url("/barang_masuk_komersial_detail/{$row->id}/{$id}") . '"
                            class="btn btn-info m-1"
                            data-id="{$row->id}">
                            <i class="bi bi-database-down"></i>
                        </a>';
                return $see;
            })
            ->addColumn('status', function ($row) use ($id) {
                if ($row->status == 3) {
                    return '<span class="badge bg-success p-2">DITANDATANGANI KEUANGAN</span>';
                } else {
                    return '<span class="badge bg-secondary p-2">BELUM DITANDATANGANI</span>';
                }
            })
            ->addColumn('ttd_karyawan', function ($row) {

                if (!$row->ttd_karyawan) {
                    return '-';
                }

                $signature = asset('storage/signatures/' . basename($row->ttd_karyawan));

                return '<img src="' . $signature . '"
                        style="
                            max-width: 120px;
                            max-height: 60px;
                            width: auto;
                            height: auto;
                            object-fit: contain;
                            display: block;
                            margin: auto;
                        ">';
            })->addColumn('ttd_atasan3', function ($row) {

                if (!$row->ttd_atasan3) {
                    return '-';
                }

                $signature = asset('storage/signatures/' . basename($row->ttd_atasan3));

                return '<img src="' . $signature . '"
                        style="
                            max-width: 120px;
                            max-height: 60px;
                            width: auto;
                            height: auto;
                            object-fit: contain;
                            display: block;
                            margin: auto;
                        ">';
            })
            ->addColumn('waktu_atasan3', function ($row) use ($id) {
                $waktu_atasan3 = Carbon::parse($row->waktu_atasan3)->format('d-m-Y H:i:s');
                return $waktu_atasan3;
            })

            ->rawColumns([
                'aksi',
                'status',
                'ttd_karyawan',
                'ttd_atasan3',
                'waktu_atasan3',
            ])
            ->make(true);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'id_bapb_update' => 'required|exists:barang_masuk,id',
                'no_bapb' => 'required',
                'tanggal_masuk' => 'required',
                'menu_id' => 'required|uuid|exists:master_menu,id',

            ];
            $messages = [
                'no_bapb.required' => 'No. BAPB wajib diisi',
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
            $bapb_unik = BarangMasuk::where('no_bapb', $request->no_bapb)
                ->where('id_proyek', $user->id_proyek)
                ->where('id', '!=', $request->id_bapb_update)
                ->exists();
            // dd($bapb_unik);
            if ($bapb_unik) {
                Log::create([
                    'id' => Uuid::uuid4(),
                    'id_karyawan' => $karyawan->id,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'aksi' => 'UPDATE',
                    'deskripsi' => 'Tertolak karena tidak unik ' . $request->no_bapb,
                    'id_menu' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'nama_tabel' => 'barang_bapb',
                    'id_tabel_lama' => null,
                    'keterangan' => 'failed',
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'No. BAPB sudah digunakan dalam proyek ini!'
                ], 422);
            }
            // dd($karyawan);
            $bapb = BarangMasuk::where('id', $request->id_bapb_update)->first();
            $bapb->update([
                'no_bapb' => $request->no_bapb,
                'tanggal_masuk' => $request->tanggal_masuk,
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'updated_at' => now(),
            ]);
            BarangMasukDetail::where('id_barang_masuk', $request->id_bapb_update)->update([
                'no_bapb' => $request->no_bapb,
                'tanggal_masuk' => $request->tanggal_masuk,
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
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
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

}
