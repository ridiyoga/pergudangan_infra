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
    <h3 class="h3 mb-0">Barang Masuk</h3>
    <div class="my-3">
        <div class="shadow mb-2 p-2 p-sm-3 table-responsive bg-white">
            <button id="btn_tambah_barang" type="button" class="btn btn-primary my-3"><i
                    class="bi bi-plus-circle"></i>&nbsp;Tambah</button>
            <button id="btn_tambah_ttd" type="button" class="btn btn-danger my-3"><i
                    class="bi bi-check-all"></i>&nbsp;Selesai</button>
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
                        <th>SUPPLIER</th>
                        <th>KETERANGAN</th>
                        <th>PENGINPUT / PENGEDIT</th>
                        <th>FOTO SURAT JALAN</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</main>
{{-- Modal Create --}}
<div class="modal fade" id="modal_barang" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_tambah_barang">
                    @csrf
                    <input type="hidden" name="menu_id" value="{{ $menu_id }}">
                    <input type="hidden" name="id_barang_masuk" value="{{ $id_bapb }}">
                    <div class="mb-2">
                        <label for="no_bapb" class="form-label">No. BAPB</label>
                        <input type="text" class="form-control" placeholder="No. BAPB" value="{{ $bapb->no_bapb }}"
                            id="no_bapb_add" name="no_bapb" readonly>
                    </div>

                    <div class="mb-2">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" placeholder="Tanggal Masuk" id="tanggal_masuk_add"
                            name="tanggal_masuk">
                    </div>
                    <div class="mb-2">
                        <label for="surat_jalan" class="form-label">No. Surat Jalan</label>
                        <input type="text" class="form-control" placeholder="No. Surat Jalan" id="surat_jalan_add"
                            name="surat_jalan">
                    </div>

                    <div class="mb-2">
                        <label for="id_kategori_add" class="form-label">Kategori</label>
                        <select class="form-select" id="id_kategori_add" name="id_kategori">
                            <option value=""></option>
                            @foreach ($kategori as $kk)
                                <option value="{{ $kk->id }}">{{ $kk->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="id_barang_add" class="form-label">Nama Barang</label>
                        <select class="form-select" id="id_barang_add" name="id_barang">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="jumlah" class="form-label">Jumlah Barang</label>
                        <input type="text" class="form-control" placeholder="Jumlah Barang" id="jumlah_add"
                            name="jumlah">
                    </div>
                    <div class="mb-2">
                        <label for="id_supplier_add" class="form-label">Supplier</label>
                        <select class="form-select" id="id_supplier_add" name="id_supplier">
                            <option value=""></option>
                            @foreach ($supplier as $sp)
                                <option value="{{ $sp->id }}">{{ $sp->nama_supplier }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea type="text" class="form-control" placeholder="Keterangan" id="keterangan_add" name="keterangan"></textarea>
                    </div>
                    <div class="row g-2">
                        <!-- Label Utama -->
                        <label for="foto" class="form-label fw-bold text-dark mb-2">
                            <i class="fa-solid fa-file-medical text-primary me-2"></i>Foto Terkini
                        </label>

                        <!-- Container Alert -->
                        <div class="alert_pulang mb-2"></div>

                        <!-- Upload Box -->
                        <div
                            class="file-upload-wrapper text-center p-4 rounded-3 border border-2 border-dashed bg-light position-relative overflow-hidden">

                            <!-- Input File Overlay -->
                            <input type="file" id="foto" name="foto"
                                class="position-absolute top-0 start-0 w-100 h-100 opacity-0 z-3"
                                accept="image/*,.pdf" style="cursor: pointer;" />

                            <!-- Tampilan Default (Belum Ada File) -->
                            <div class="file-upload-content" id="upload_default">
                                <div class="icon-circle mb-3 mx-auto bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <i class="fa-solid fa-cloud-arrow-up fs-4 text-primary"></i>
                                </div>
                                <h6 class="fw-semibold text-dark mb-1">Foto Terkini</h6>
                                <p class="text-muted small mb-0">Klik atau seret file ke area ini</p>
                                <span class="badge bg-white text-secondary border mt-2 font-monospace">Format: JPG,
                                    PNG,
                                    PDF (Max 2MB)</span>
                            </div>

                            <!-- Tampilan Sukses (Setelah File Dipilih) -->
                            <div class="file-upload-content d-none" id="upload_success">
                                <!-- Preview Gambar (Otomatis Tampil Jika Berupa Foto) -->
                                <div class="mb-3 d-none" id="image_preview_container">
                                    <img id="image_preview" src="" alt="Preview Foto"
                                        class="img-thumbnail rounded" style="max-height: 150px; object-fit: cover;" />
                                </div>

                                <!-- Icon Default Jika File Adalah PDF -->
                                <div id="pdf_icon_container"
                                    class="icon-circle mb-3 mx-auto bg-success bg-opacity-10 text-success shadow-sm rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <i class="fa-solid fa-file-circle-check fs-3"></i>
                                </div>

                                <h6 class="fw-semibold text-dark mb-1 text-truncate px-3" id="file_name_display">
                                    Nama_File.pdf</h6>
                                <p class="text-muted small mb-2" id="file_size_display">0 KB</p>

                                <span class="badge bg-success text-white mb-2">
                                    <i class="fa-solid fa-check me-1"></i>File Berhasil Dipilih
                                </span>

                                <div>
                                    <small class="text-primary text-decoration-underline fw-semibold">Klik area ini
                                        untuk
                                        mengganti file</small>
                                </div>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_create_barang">Simpan</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Delete --}}
<div class="modal fade" id="modal_delete_barang" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <i class="bi bi-exclamation-circle-fill text-danger"></i>&nbsp;
                    <p>Apakah anda ingin menghapus data ini?</p>
                </div>
                <form id="form_delete_barang">
                    @csrf
                    <input type="hidden" id="menu_id_delete" name="menu_id" value="{{ $menu_id }}">
                    <input type="hidden" class="form-control" id="id_barang_masuk_delete"
                        name="id_barang_masuk_delete">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn_delete_barang">Hapus</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Update --}}
<div class="modal fade" id="modal_update_barang" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_update_barang">
                    @csrf
                    <input type="hidden" name="menu_id" value="{{ $menu_id }}">
                    <input type="hidden" id="id_barang_masuk_detail_update" name="id_barang_masuk_detail">
                    <div class="mb-2">
                        <label for="no_bapb" class="form-label">No. BAPB</label>
                        <input type="text" class="form-control" placeholder="No. BAPB"
                            value="{{ $bapb->no_bapb }}" id="no_bapb_add" name="no_bapb" disabled>
                    </div>

                    <div class="mb-2">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" placeholder="Tanggal Masuk"
                            id="tanggal_masuk_update" name="tanggal_masuk">
                    </div>
                    <div class="mb-2">
                        <label for="surat_jalan" class="form-label">No. Surat Jalan</label>
                        <input type="text" class="form-control" placeholder="No. Surat Jalan"
                            id="surat_jalan_update" name="surat_jalan">
                    </div>

                    <div class="mb-2">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" placeholder="Kategori" id="kategori_update"
                            name="kategori" disabled>
                    </div>
                    <div class="mb-2">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" placeholder="Nama Barang" id="nama_barang_update"
                            name="nama_barang" disabled>
                    </div>
                    <div class="mb-2">
                        <label for="jumlah" class="form-label">Jumlah Barang</label>
                        <input type="text" class="form-control" placeholder="Jumlah Barang" id="jumlah_update"
                            name="jumlah">
                    </div>
                    <div class="mb-2">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" class="form-control" placeholder="Supplier Barang"
                            id="supplier_update" name="supplier" disabled>
                    </div>
                    <div class="mb-2">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea type="text" class="form-control" placeholder="Keterangan" id="keterangan_update" name="keterangan"></textarea>
                    </div>
                    <div class="row g-2">
                        <!-- Label Utama -->
                        <label for="foto_update" class="form-label fw-bold text-dark mb-2">
                            <i class="fa-solid fa-file-medical text-primary me-2"></i>Foto Terkini
                        </label>

                        <!-- Container Alert -->
                        <div class="alert_pulang mb-2"></div>

                        <!-- Upload Box -->
                        <div
                            class="file-upload-wrapper text-center p-4 rounded-3 border border-2 border-dashed bg-light position-relative overflow-hidden">

                            <!-- Input File Overlay -->
                            <input type="file" id="foto_update" name="foto"
                                class="position-absolute top-0 start-0 w-100 h-100 opacity-0 z-3"
                                accept="image/*,.pdf" style="cursor: pointer;" />

                            <!-- Tampilan Default (Belum Ada File) -->
                            <div class="file-upload-content" id="upload_default_update">
                                <div class="icon-circle mb-3 mx-auto bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <i class="fa-solid fa-cloud-arrow-up fs-4 text-primary"></i>
                                </div>
                                <h6 class="fw-semibold text-dark mb-1">Foto Terkini</h6>
                                <p class="text-muted small mb-0">Klik atau seret file ke area ini</p>
                                <span class="badge bg-white text-secondary border mt-2 font-monospace">Format: JPG,
                                    PNG,
                                    PDF (Max 2MB)</span>
                            </div>

                            <!-- Tampilan Sukses (Setelah File Dipilih) -->
                            <div class="file-upload-content d-none" id="upload_success_update">
                                <!-- Preview Gambar (Otomatis Tampil Jika Berupa Foto) -->
                                <div class="mb-3 d-none" id="image_preview_container_update">
                                    <img id="image_preview_update" src="" alt="Preview Foto"
                                        class="img-thumbnail rounded" style="max-height: 150px; object-fit: cover;" />
                                </div>

                                <!-- Icon Default Jika File Adalah PDF -->
                                <div id="pdf_icon_container_update"
                                    class="icon-circle mb-3 mx-auto bg-success bg-opacity-10 text-success shadow-sm rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <i class="fa-solid fa-file-circle-check fs-3"></i>
                                </div>

                                <h6 class="fw-semibold text-dark mb-1 text-truncate px-3"
                                    id="file_name_display_update">
                                    Nama_File.pdf</h6>
                                <p class="text-muted small mb-2" id="file_size_display_update">0 KB</p>

                                <span class="badge bg-success text-white mb-2">
                                    <i class="fa-solid fa-check me-1"></i>File Berhasil Dipilih
                                </span>

                                <div>
                                    <small class="text-primary text-decoration-underline fw-semibold">Klik area ini
                                        untuk
                                        mengganti file</small>
                                </div>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_update_barang">Simpan</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal TTD --}}
<div class="modal fade" id="modal_ttd" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah No. BAPB</h5>
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

        function showPhotoPreview(suffix, fileName, fileType, imageUrl, sizeText) {
            const $form = suffix ? $('#form_update_barang') : $('#form_tambah_barang');
            const $default = $form.find('#upload_default' + suffix);
            const $success = $form.find('#upload_success' + suffix);
            const $imageContainer = $form.find('#image_preview_container' + suffix);
            const $image = $form.find('#image_preview' + suffix);
            const $pdfIcon = $form.find('#pdf_icon_container' + suffix);

            if (!fileName) {
                $default.removeClass('d-none');
                $success.addClass('d-none');
                $imageContainer.addClass('d-none');
                $image.attr('src', '');
                return;
            }

            $form.find('#file_name_display' + suffix).text(fileName);
            $form.find('#file_size_display' + suffix).text(sizeText || '');
            if (fileType && fileType.startsWith('image/') && imageUrl) {
                $image.attr('src', imageUrl);
                $imageContainer.removeClass('d-none');
                $pdfIcon.addClass('d-none');
            } else {
                $image.attr('src', '');
                $imageContainer.addClass('d-none');
                $pdfIcon.removeClass('d-none');
            }

            $default.addClass('d-none');
            $success.removeClass('d-none');
        }

        $('#id_kategori_add').select2({
            dropdownParent: $('#modal_barang'),
            width: '100%',
            placeholder: 'Pilih Kategori',
            allowClear: true
        });
        $('#id_barang_add').select2({
            dropdownParent: $('#modal_barang'),
            width: '100%',
            placeholder: 'Pilih Barang',
            allowClear: true
        });
        // $('#modal_barang').on('shown.bs.modal', function() {
        if (!$('#id_supplier_add').hasClass('select2-hidden-accessible')) {
            $('#id_supplier_add').select2({
                dropdownParent: $('#modal_barang'),
                width: '100%',
                placeholder: 'Pilih Supplier',
                allowClear: true
            });
        }

        //     $('#id_supplier_add').select2('close');
        // });
        // $('#modal_barang').on('hide.bs.modal', function() {
        //     if ($('#id_supplier_add').hasClass('select2-hidden-accessible')) {
        //         $('#id_supplier_add').select2('close');
        //     }
        // });
        $('#id_kategori_update').select2({
            dropdownParent: $('#modal_update_barang'),
            width: '100%',
            placeholder: 'Pilih Kategori',
            allowClear: true
        });
        $('#id_barang_update').select2({
            dropdownParent: $('#modal_update_barang'),
            width: '100%',
            placeholder: 'Pilih Barang',
            allowClear: true
        });
        $('#id_supplier_update').select2({
            dropdownParent: $('#modal_update_barang'),
            width: '100%',
            placeholder: 'Pilih Supplier',
            allowClear: true
        });
        $('#jumlah_add').on('input', function() {
            this.value = this.value.replace(/[^0-9.]/g, '');

            // Hanya mengizinkan satu titik desimal
            let parts = this.value.split('.');
            if (parts.length > 2) {
                this.value = parts[0] + '.' + parts.slice(1).join('');
            }
        });

        $('#id_kategori_add').on('change', function() {
            showSelectLoading('#id_barang_add');
            let id_kategori = $(this).val();
            console.log(id_kategori);
            let url = "{{ url('/barang_masuk_detail/get_barang') }}" + "/" + id_kategori;

            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    hideSelectLoading('#id_barang_add');
                    $('#id_barang_add').html(response);
                    $('#id_barang_add').trigger('change.select2');
                },
                error: function(data) {
                    hideSelectLoading(
                        '#id_barang_add'
                    ); // Memastikan indikator loading tambahan juga tertutup

                    // Ambil pesan error (dengan fallback jika responseJSON/message kosong)
                    let errorMessage = data.responseJSON && data.responseJSON.message ?
                        data.responseJSON.message :
                        'Terjadi kesalahan saat mengambil data.';

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errorMessage,
                        timer: 4200,
                        showConfirmButton: false
                    });
                }
            });
        });

        function showSelectLoading(selectId) {
            $(selectId).prop('disabled', true)
                .closest('.form-floating')
                .find('.select-loader')
                .removeClass('d-none');
        }

        function showTableLoading() {
            $('#tableLoading').removeClass('d-none');
        }

        function hideTableLoading() {
            $('#tableLoading').addClass('d-none');
        }

        function hideSelectLoading(selectId) {
            $(selectId).prop('disabled', false)
                .closest('.form-floating')
                .find('.select-loader')
                .addClass('d-none');
        }

        $(document).on('click', '#btn_tambah_barang', function(e) {
            $('#modal_barang').modal('show');
        });
        $(document).on('click', '#btn_delete_datatable', function(e) {
            var id = $(this).data("id");
            $('#id_barang_masuk_delete').val(id);
            $('#modal_delete_barang').modal('show');
        });
        $(document).on('click', '#btn_update_datatable', function(e) {
            var id = $(this).data("id");
            var tanggal_masuk = $(this).data("tanggal_masuk");
            var surat_jalan = $(this).data("surat_jalan");
            var nama_kategori = $(this).data("nama_kategori");
            var nama_supplier = $(this).data("nama_supplier");
            var nama_barang = $(this).data("nama_barang");
            var jumlah = $(this).data("jumlah");
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
            $('#keterangan_update').val(keterangan);
            const fotoUrl = foto ?
                "{{ asset('storage/foto_surat_jalan') }}/" + encodeURIComponent(foto) :
                '';
            const fotoType = foto && /\.(jpe?g|png)$/i.test(foto) ? 'image/preview' : 'application/pdf';
            showPhotoPreview('_update', foto, fotoType, fotoUrl, '');

            $('#modal_update_barang').modal('show');
        });
        const table = $('#tabel_barang').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: "{{ url('/barang_masuk_detail_dt') }}" + '/' + '{{ $id_bapb }}',
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
        });
        $(document).on('click', '#btn_create_barang', function(e) {
            e.preventDefault();
            const formData = new FormData($('#form_tambah_barang')[0]);

            $.ajax({
                url: "{{ url('/barang_masuk_detail_create') }}",
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

                    $('#form_tambah_barang')[0].reset();
                    $('#id_barang_add').html('<option value=""></option>');
                    $('#form_tambah_barang').find('select').trigger('change.select2');
                    $('#upload_default').removeClass('d-none');
                    $('#upload_success, #image_preview_container').addClass('d-none');
                    $('#pdf_icon_container').removeClass('d-none');
                    $('#image_preview').attr('src', '');
                    $('#file_name_display, #file_size_display').text('');
                    $('#modal_barang').modal('hide');

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
        $(document).on('click', '#btn_delete_barang', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/barang_masuk_detail_delete') }}",
                type: "POST",
                data: $('#form_delete_barang').serialize(),
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
                        text: 'Data Berhasil Dihapus',
                        icon: 'success'
                    });

                    $('#form_delete_barang')[0].reset();
                    $('#modal_delete_barang').modal('hide');

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
                        text: xhr.responseJSON?.message || 'Data Gagal Dihapus',
                        icon: 'error'
                    })
                }
            });
        });
        $(document).on('click', '#btn_update_barang', function(e) {
            e.preventDefault();
            const formData = new FormData($('#form_update_barang')[0]);

            $.ajax({
                url: "{{ url('/barang_masuk_detail_update') }}",
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
                    showPhotoPreview('_update', '', '', '', '');
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
        $('#foto, #foto_update').on('change', function() {
            const file = this.files[0];
            const suffix = this.id === 'foto_update' ? '_update' : '';

            if (file) {
                let sizeText = (file.size < 1024 * 1024) ?
                    (file.size / 1024).toFixed(1) + ' KB' :
                    (file.size / (1024 * 1024)).toFixed(2) + ' MB';

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        showPhotoPreview(suffix, file.name, file.type, e.target.result, sizeText);
                    };
                    reader.readAsDataURL(file);
                } else {
                    showPhotoPreview(suffix, file.name, file.type, '', sizeText);
                }
            } else {
                showPhotoPreview(suffix, '', '', '', '');
            }
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
                url: "{{ url('/barang_masuk_detail/selesai_ttd') }}",
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
                                "{{ url('/barang_masuk') }}/" +
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
