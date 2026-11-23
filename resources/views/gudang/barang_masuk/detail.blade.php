@include('template_atas')
@include('template_css')
<style>
    .border-dashed {
        border-style: dashed !important;
    }

    #tabel_barang_wrapper {
        width: 100%;
        min-width: 0;
    }

    #tabel_barang_wrapper .dt-layout-row {
        flex-wrap: wrap;
        row-gap: 0.75rem;
    }

    #tabel_barang_wrapper .dt-layout-cell {
        min-width: 0;
    }

    #tabel_barang_wrapper .dt-search input {
        max-width: 100%;
    }

    #tabel_barang_wrapper .dt-paging {
        overflow-x: auto;
    }

    .file-upload-wrapper {
        transition: background-color 0.2s ease-in-out;
    }

    .file-upload-wrapper:hover {
        background-color: #e9ecef !important;
    }

    @media (max-width: 575.98px) {
        #tabel_barang_wrapper .dt-layout-cell {
            flex: 1 1 100%;
        }

        #tabel_barang_wrapper .dt-search {
            text-align: left;
        }
    }
</style>
@include('sweetalert::alert')
<main class="flex-grow-1 p-2 p-sm-3 p-lg-4">
    <h3 class="h3 mb-0">Barang Masuk</h3>
    <div class="my-3">
        <div class="shadow mb-2 p-2 p-sm-3 table-responsive bg-white">
            <button id="btn_tambah_barang" type="button" class="btn btn-primary my-3"><i
                    class="bi bi-plus-circle"></i>&nbsp;Tambah</button>
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
                    <div class="mb-2">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="hidden" class="form-control" id="id_barang_update" name="id_barang_update">
                        <input type="text" class="form-control" id="nama_barang_update" name="nama_barang">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
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
        $('#id_supplier_add').select2({
            dropdownParent: $('#modal_barang'),
            width: '100%',
            placeholder: 'Pilih Supplier',
            allowClear: true
        });
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

            // Tampilkan SweetAlert Loading Spinner sebelum request AJAX dikirim
            Swal.fire({
                title: 'Memuat Data...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: url,
                method: 'GET',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    hideSelectLoading('#id_barang_add');
                    $('#id_barang_add').html(response);

                    // Tutup SweetAlert setelah data berhasil dimuat
                    Swal.close();
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
            var nama_barang = $(this).data("nama_barang");
            $('#id_barang_update').val(id);
            $('#nama_barang_update').val(nama_barang);
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
            // console.log('asoy');

            $.ajax({
                url: "{{ url('/barang_masuk_detail_update') }}",
                type: "POST",
                data: $('#form_update_barang').serialize(),
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
                    });

                    $('#form_update_barang')[0].reset();
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
        $('#foto').on('change', function() {
            const file = this.files[0];

            if (file) {
                // 1. Format Ukuran File
                let sizeText = (file.size < 1024 * 1024) ?
                    (file.size / 1024).toFixed(1) + ' KB' :
                    (file.size / (1024 * 1024)).toFixed(2) + ' MB';

                $('#file_name_display').text(file.name);
                $('#file_size_display').text(sizeText);

                // 2. Logic Preview Gambar vs File Lain (PDF)
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image_preview').attr('src', e.target.result);
                        $('#image_preview_container').removeClass('d-none');
                        $('#pdf_icon_container').addClass('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Jika PDF atau format lain non-gambar
                    $('#image_preview_container').addClass('d-none');
                    $('#pdf_icon_container').removeClass('d-none');
                }

                // 3. Switch Tampilan
                $('#upload_default').addClass('d-none');
                $('#upload_success').removeClass('d-none');
            } else {
                // Reset ke tampilan default jika batal memilih
                $('#upload_default').removeClass('d-none');
                $('#upload_success').addClass('d-none');
                $('#image_preview').attr('src', '');
            }
        });
    });
</script>
{{-- End Modal --}}

@include('template_bawah')
