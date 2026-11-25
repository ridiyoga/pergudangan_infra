@include('template_atas')
@include('template_css')
<style>
    .kbw-signature {
        width: 100%;
        height: 160px;
        border: 3px solid #f3e8ff !important;
        background: #ffffff;
    }

    .signature-area canvas {
        width: 100% !important;
        height: 100% !important;
    }

    /* Efek saat area tanda tangan di-hover / aktif */
    .signature-area {
        transition: all 0.25s ease-in-out;
    }

    .signature-area:hover,
    .signature-area:focus-within {
        border-color: #2563eb !important;
        /* Biru terang */
        background-color: #ffffff !important;
        /* Berubah jadi putih bersih */
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.12) !important;
        /* Glow halus */
    }
</style>
@include('sweetalert::alert')
<main class="flex-grow-1 p-2 p-sm-3 p-lg-4">
    <h3 class="h3 mb-0">Barang Masuk (Komersial)</h3>
    <div class="my-3">
        <div class="shadow mb-2 p-2 p-sm-3 table-responsive bg-white">
            @if ($bapb->status == 1 || $bapb->status == 2)
                <button id="btn_tambah_ttd" type="button" class="btn btn-danger my-3"><i
                        class="bi bi-check-all"></i>&nbsp;Selesai</button>
            @endif
            <table id="tabel_barang" class="table" style="font-size: small;">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>NAMA BARANG</th>
                        <th>NO. BAPB</th>
                        <th>TANGGAL MASUK</th>
                        <th>NO. SURAT JALAN</th>
                        <th>JUMLAH BARANG</th>
                        <th>SATUAN</th>
                        <th>HARGA</th>
                        <th>SUPPLIER</th>
                        <th>KETERANGAN</th>
                        <th>PENGINPUT</th>
                        <th>FOTO SURAT JALAN</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-end">TOTAL HARGA:</th>
                        <th id="total_harga" class="text-nowrap">Rp 0</th>
                        <th colspan="5"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</main>
{{-- Modal Update --}}

<div class="modal fade" id="modal_update_barang" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <form id="form_update_barang">
                    @csrf
                    <input type="hidden" name="menu_id" value="{{ $menu_id }}">
                    <input type="hidden" id="id_barang_masuk_detail_update" name="id_barang_masuk_detail">

                    <div class="row g-3">

                        <!-- Kolom Kiri -->
                        <div class="col-12 col-md-6">
                            <div class="mb-2">
                                <label for="no_bapb_update" class="form-label">No. BAPB</label>
                                <input type="text" class="form-control" placeholder="No. BAPB"
                                    value="{{ $bapb->no_bapb }}" id="no_bapb_update" name="no_bapb" disabled>
                            </div>

                            <div class="mb-2">
                                <label for="tanggal_masuk_update" class="form-label">Tanggal Masuk</label>
                                <input type="date" class="form-control" id="tanggal_masuk_update"
                                    name="tanggal_masuk" disabled>
                            </div>

                            <div class="mb-2">
                                <label for="surat_jalan_update" class="form-label">No. Surat Jalan</label>
                                <input type="text" class="form-control" placeholder="No. Surat Jalan"
                                    id="surat_jalan_update" name="surat_jalan" disabled>
                            </div>

                            <div class="mb-2">
                                <label for="kategori_update" class="form-label">Kategori</label>
                                <input type="text" class="form-control" placeholder="Kategori" id="kategori_update"
                                    name="kategori" disabled>
                            </div>
                            <div class="mb-2">
                                <label for="supplier_update" class="form-label">Supplier</label>
                                <input type="text" class="form-control" placeholder="Supplier Barang"
                                    id="supplier_update" name="supplier" disabled>
                            </div>

                            <div class="mb-2">
                                <label for="keterangan_update" class="form-label">Keterangan</label>
                                <textarea class="form-control" placeholder="Keterangan" id="keterangan_update" name="keterangan" disabled></textarea>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-12 col-md-6">

                            <div class="mb-2">
                                <label for="nama_barang_update" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" placeholder="Nama Barang"
                                    id="nama_barang_update" name="nama_barang" disabled>
                            </div>
                            <div class="mb-2">
                                <label for="satuan_update" class="form-label">Satuan Barang</label>
                                <input type="text" class="form-control" placeholder="Satuan Barang"
                                    id="satuan_update" name="satuan" disabled>
                            </div>
                            <div class="mb-2">
                                <label for="harga_update" class="form-label">Harga Barang</label>
                                <input type="text" class="form-control" placeholder="Harga Barang"
                                    id="harga_update" name="harga">
                            </div>
                            <!-- Foto Surat Jalan -->
                            <div class="row g-2">
                                <div class="form-label fw-bold text-dark mb-2">
                                    <i class="fa-solid fa-file-medical text-primary me-2"></i>Foto Surat Jalan
                                </div>

                                <div class="alert_pulang mb-2"></div>

                                <div class="text-center rounded-3 border bg-light d-flex align-items-center justify-content-center overflow-hidden"
                                    style="height: 240px;">

                                    <img id="image_preview_update" src="" alt="Foto Surat Jalan"
                                        class="img-fluid rounded d-none"
                                        style="max-height: 220px; object-fit: contain;">

                                    <div id="pdf_preview_update" class="d-none">
                                        <i class="fa-solid fa-file-pdf fs-1 text-danger mb-2"></i>
                                        <p id="file_name_display_update" class="mb-2"></p>
                                        <a id="pdf_link_update" href="#" target="_blank" rel="noopener"
                                            class="btn btn-outline-primary btn-sm">Lihat dokumen</a>
                                    </div>

                                    <p id="photo_empty_update" class="text-muted mb-0 d-none">
                                        Foto belum tersedia
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-primary" id="btn_update_barang">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal TTD --}}
<div class="modal fade" id="modal_ttd" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tanda Tangan No. BAPB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_tambah_barang_masuk">
                    @csrf
                    <input type="hidden" id="menu_id_ttd_add" name="menu_id" value="{{ $menu_id }}">
                    <input type="hidden" id="id_bapb_add" name="id_bapb" value="{{ $bapb->id }}">

                    <!-- Canvas Container dengan Dashed Border & Accent Color -->
                    <div id="sig_add"
                        class="signature-area kbw-signature rounded-4 position-relative overflow-hidden mb-2"
                        style="width: 100%; height: 210px; border: 2px dashed #93c5fd; background-color: #f8fafc;">

                        <!-- Watermark / Petunjuk dengan Warna Cerah -->
                        <div
                            class="position-absolute top-50 start-50 translate-middle text-center pe-none user-select-none opacity-75">
                            <div
                                class="mb-2 d-inline-flex align-items-center justify-content-center rounded-circle bg-white shadow-sm p-3 text-primary">
                                <i class="fa-solid fa-pen-nib fs-4"></i>
                            </div>
                            <p class="small mb-0 text-secondary fw-medium">Tanda tangan di area ini</p>
                        </div>

                        <!-- Canvas -->
                        <canvas id="signature-pad-add"
                            style="width:100%; height:100%; touch-action: none !important; position: relative; z-index: 2; cursor: crosshair;"
                            hidden></canvas>
                    </div>
                    <input type="hidden" id="signature_add" name="signature">

                    <!-- Baris Kontrol Tanda Tangan Add -->
                    <div class="d-flex justify-content-between align-items-center px-1 mb-2">
                        <span class="text-muted small" style="font-size: 0.8rem;">
                            <i class="fa-regular fa-circle-check text-success me-1"></i>Gunakan mouse untuk tanda
                            tangan
                        </span>

                        <button type="button" class="btn btn-outline-danger btn-sm" id="clear_signature_add">
                            <span class="mdi mdi-trash-can-outline"></span>
                            <span>Hapus</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_selesai">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    $(document).on('input', 'input[type="text"]', function() {
        this.value = this.value.toUpperCase();
    });
    $(document).ready(function() {
        function showValidationErrors(errors) {
            Swal.fire({
                title: 'Validasi gagal',
                text: Object.values(errors).flat().join('\n'),
                icon: 'error'
            })
        }

        const createCanvas = $('#signature-pad-add')[0];
        const createSignaturePad = new SignaturePad(createCanvas);

        function resizeCanvas(canvas, signaturePad) {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        }

        function showPhotoPreview(fileName, imageUrl) {
            const $image = $('#image_preview_update');
            const $pdf = $('#pdf_preview_update');
            const $empty = $('#photo_empty_update');

            $image.addClass('d-none').attr('src', '');
            $pdf.addClass('d-none');
            $empty.addClass('d-none');

            if (!fileName) {
                $empty.removeClass('d-none');
                return;
            }

            if (/\.(jpe?g|png)$/i.test(fileName)) {
                $image.attr('src', imageUrl).removeClass('d-none');
                return;
            }

            $('#file_name_display_update').text(fileName);
            $('#pdf_link_update').attr('href', imageUrl);
            $pdf.removeClass('d-none');
        }
        $('#harga_update').on('input', function() {
            let angka = $(this).val().replace(/[^0-9]/g, '');

            $(this).val(
                angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.')
            );
        });
        $(document).on('click', '#btn_update_datatable', function(e) {
            var id = $(this).data("id");
            var tanggal_masuk = $(this).data("tanggal_masuk");
            var surat_jalan = $(this).data("surat_jalan");
            var nama_kategori = $(this).data("nama_kategori");
            var nama_supplier = $(this).data("nama_supplier");
            var nama_barang = $(this).data("nama_barang");
            var jumlah = $(this).data("jumlah");
            var satuan = $(this).data("satuan");
            var harga = $(this).data("harga");
            var keterangan = $(this).data("keterangan");
            var foto = $(this).data("foto");

            $('#form_update_barang')[0].reset();
            $('#id_barang_masuk_detail_update').val(id);
            $('#tanggal_masuk_update').val(tanggal_masuk);
            $('#surat_jalan_update').val(surat_jalan);
            $('#kategori_update').val(nama_kategori);
            $('#supplier_update').val(nama_supplier);
            $('#nama_barang_update').val(nama_barang);
            $('#jumlah_update').val(jumlah);
            $('#satuan_update').val(satuan);
            $('#harga_update').val(harga);
            $('#keterangan_update').val(keterangan);
            const fotoUrl = foto ?
                "{{ asset('storage/foto_surat_jalan') }}/" + encodeURIComponent(foto) :
                '';
            showPhotoPreview(foto, fotoUrl);

            $('#modal_update_barang').modal('show');
        });
        const table = $('#tabel_barang').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: "{{ url('/barang_masuk_komersial_detail_dt') }}" + '/' + '{{ $id_bapb }}',
                type: "GET"
            },
            columns: [{
                    data: "id",
                    name: "id",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: "nama_barang",
                    name: "nama_barang"
                },
                {
                    data: "no_bapb",
                    name: "no_bapb"
                },
                {
                    data: "tanggal_masuk",
                    name: "tanggal_masuk"
                },
                {
                    data: "surat_jalan",
                    name: "surat_jalan"
                },
                {
                    data: "jumlah",
                    name: "jumlah"
                },
                {
                    data: "satuan",
                    name: "satuan"
                },
                {
                    data: "harga",
                    name: "harga",
                    className: "text-end"
                },
                {
                    data: "nama_supplier",
                    name: "nama_supplier"
                },
                {
                    data: "keterangan",
                    name: "keterangan"
                },
                {
                    data: "nama_karyawan",
                    name: "nama_karyawan"
                },

                {
                    data: "foto_surat_jalan",
                    name: "foto_surat_jalan"
                },
                {
                    data: "aksi",
                    name: "aksi",
                    orderable: false,
                    searchable: false
                },
            ],
            order: [
                [1, 'asc']
            ],
            drawCallback: function(settings) {
                let response = settings.json;

                if (response && response.total_harga !== undefined) {
                    $('#total_harga')
                        .html(response.total_harga)
                        .addClass('text-end');
                }
            }
        });
        $(document).on('click', '#btn_update_barang', function(e) {
            e.preventDefault();
            const formData = new FormData($('#form_update_barang')[0]);

            $.ajax({
                url: "{{ url('/barang_masuk_komersial_detail_update') }}",
                type: "POST",
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },

                success: function(data) {
                    if (data.errors) {
                        showValidationErrors(data.errors);
                        return;
                    }

                    Swal.fire({
                        title: 'Sukses!',
                        text: 'Data Berhasil Disimpan',
                        icon: 'success'
                    });

                    $('#form_update_barang')[0].reset();
                    showPhotoPreview('', '');
                    $('#modal_update_barang').modal('hide');

                    table.ajax.reload();

                },

                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        showValidationErrors(errors);
                        return;
                    }

                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Data Gagal Disimpan',
                        icon: 'error'
                    })
                }
            });
        });
        $(document).on('click', '#btn_tambah_ttd', function(e) {
            $('#modal_ttd').modal('show');
        });
        $('#modal_ttd').on('shown.bs.modal', function() {
            $('#signature-pad-add').removeAttr('hidden');
            resizeCanvas(createCanvas, createSignaturePad);
        });
        $('#clear_signature_add').on('click', function() {
            createSignaturePad.clear();
            $('#signature_add').val('');
        });
        $(document).on('click', '#btn_selesai', function(e) {
            e.preventDefault();
            $('#signature_add').val(
                createSignaturePad.isEmpty() ? '' : createSignaturePad.toDataURL()
            );

            $.ajax({
                url: "{{ url('/barang_masuk_komersial_detail/selesai_ttd') }}",
                type: "POST",
                data: $('#form_tambah_barang_masuk').serialize(),
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },

                success: function(data) {
                    if (data.errors) {
                        showValidationErrors(data.errors);
                        return;
                    }

                    Swal.fire({
                        title: 'Sukses!',
                        text: 'Data Berhasil Disimpan',
                        icon: 'success'
                    }).then(() => {
                        table.ajax.reload(() => {
                            window.location.href =
                                "{{ url('/barang_masuk_komersial') }}/" +
                                "{{ $menu_id }}";
                        });
                    });

                    $('#form_tambah_barang_masuk')[0].reset();
                    createSignaturePad.clear();
                    $('#modal_ttd').modal('hide');

                },

                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        showValidationErrors(errors);
                        return;
                    }

                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Data Gagal Disimpan',
                        icon: 'error'
                    })
                }
            });
        });
    });
</script>
{{-- End Modal --}}

@include('template_bawah')
