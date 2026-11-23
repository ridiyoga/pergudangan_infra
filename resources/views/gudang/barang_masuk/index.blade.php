@include('template_atas')
@include('template_css')
<link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
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
<main class="flex-grow-1 p-4">
    <h3 class="h3 mb-0">No. BAPB</h3>
    <div class="my-3">
        <div class="shadow mb-2 p-3 table-responsive bg-white">
            <button id="btn_tambah_barang_masuk" type="button" class="btn btn-primary my-3"><i
                    class="bi bi-plus-circle"></i>&nbsp;Tambah</button>
            <table id="tabel_barang_masuk" class="table" style="font-size: small;">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>No. BAPB</th>
                        <th>PENGINPUT / PENGEDIT</th>
                        <th>TANDA TANGAN</th>
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
<div class="modal fade" id="modal_barang_masuk" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah No. BAPB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_tambah_barang_masuk">
                    @csrf
                    <input type="hidden" id="menu_id_add" name="menu_id" value="{{ $menu_id }}">
                    <div class="mb-2">
                        <label for="no_bapb" class="form-label">No. BAPB</label>
                        <input type="text" class="form-control" placeholder="No. BAPB" id="no_bapb_add"
                            name="no_bapb">
                    </div>
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

                    <!-- Baris Kontrol Bawah -->
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
                <button type="button" class="btn btn-primary" id="btn_create_barang_masuk">Simpan</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Delete --}}
<div class="modal fade" id="modal_delete_barang_masuk" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus No. BAPB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <i class="bi bi-exclamation-circle-fill text-danger"></i>&nbsp;
                    <p>Apakah anda ingin menghapus data ini?</p>
                </div>
                <form id="form_delete_barang_masuk">
                    @csrf
                    <input type="hidden" id="menu_id_delete" name="menu_id" value="{{ $menu_id }}">
                    <input type="hidden" class="form-control" id="id_bapb_delete" name="id_bapb_delete">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn_delete_barang_masuk">Hapus</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Update --}}
<div class="modal fade" id="modal_update_barang_masuk" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah No. BAPB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_update_barang_masuk">
                    @csrf
                    <input type="hidden" id="menu_id_update" name="menu_id" value="{{ $menu_id }}">
                    <div class="mb-2">
                        <label for="no_bapb" class="form-label">No. BAPB</label>
                        <input type="hidden" class="form-control" id="id_bapb_update" name="id_bapb_update">
                        <input type="text" class="form-control" id="no_bapb_update" name="no_bapb">
                    </div>
                    <!-- Canvas Container dengan Dashed Border & Accent Color -->
                    <div id="sig_update"
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
                        <canvas id="signature-pad-update"
                            style="width:100%; height:100%; touch-action: none !important; position: relative; z-index: 2; cursor: crosshair;"
                            hidden></canvas>
                    </div>
                    <input type="hidden" id="signature_update" name="signature">

                    <!-- Baris Kontrol Bawah -->
                    <div class="d-flex justify-content-between align-items-center px-1 mb-2">
                        <span class="text-muted small" style="font-size: 0.8rem;">
                            <i class="fa-regular fa-circle-check text-success me-1"></i>Gunakan mouse untuk tanda
                            tangan
                        </span>

                        <button type="button" class="btn btn-outline-danger btn-sm" id="clear_signature_update">
                            <span class="mdi mdi-trash-can-outline"></span>
                            <span>Hapus</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_update_barang_masuk">Simpan</button>
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
        const updateCanvas = $('#signature-pad-update')[0];
        const createSignaturePad = new SignaturePad(createCanvas);
        const updateSignaturePad = new SignaturePad(updateCanvas);

        function resizeCanvas(canvas, signaturePad) {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        }

        $(document).on('click', '#btn_tambah_barang_masuk', function(e) {
            $('#modal_barang_masuk').modal('show');
        });
        $(document).on('click', '#btn_delete_datatable', function(e) {
            var id = $(this).data("id");
            $('#id_bapb_delete').val(id);
            $('#modal_delete_barang_masuk').modal('show');
        });
        $(document).on('click', '#btn_update_datatable', function(e) {
            var id = $(this).data("id");
            var no_bapb = $(this).data("no_bapb");
            $('#id_bapb_update').val(id);
            $('#no_bapb_update').val(no_bapb);
            $('#signature-pad-update').removeAttr('hidden');
            $('#signature_update').val('');
            updateSignaturePad.clear();
            $('#modal_update_barang_masuk').modal('show');
        });
        $(document).on('click', '#btn_tambah_barang_masuk', function() {
            $('#signature-pad-add').removeAttr('hidden');
            $('#signature_add').val('');
            createSignaturePad.clear();
            let id = $(this).data('id');
            $('#id_tidak_masuk').val(id);
        });

        $('#modal_barang_masuk').on('shown.bs.modal', function() {
            resizeCanvas(createCanvas, createSignaturePad);

            console.log({
                width: createCanvas.width,
                height: createCanvas.height
            });
        });
        $('#modal_update_barang_masuk').on('shown.bs.modal', function() {
            resizeCanvas(updateCanvas, updateSignaturePad);

            console.log({
                width: updateCanvas.width,
                height: updateCanvas.height
            });
        });


        $(window).on('resize', function() {
            if ($('#modal_barang_masuk').hasClass('show')) {
                resizeCanvas(createCanvas, createSignaturePad);
            }
            if ($('#modal_update_barang_masuk').hasClass('show')) {
                resizeCanvas(updateCanvas, updateSignaturePad);
            }
        });

        $('#clear_signature_add').on('click', function() {
            createSignaturePad.clear();
            $('#signature_add').val('');
        });
        $('#clear_signature_update').on('click', function() {
            updateSignaturePad.clear();
            $('#signature_update').val('');
        });

        // TTD Versi Web End
        const table = $('#tabel_barang_masuk').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/barang_masuk_dt') }}" + '/' + '{{ $menu_id }}',
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
                    data: "no_bapb",
                    name: "no_bapb"
                },
                {
                    data: "nama_karyawan",
                    name: "nama_karyawan"
                },
                {
                    data: "ttd_karyawan",
                    name: "ttd_karyawan"
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
        $(document).on('click', '#btn_create_barang_masuk', function(e) {
            e.preventDefault();
            $('#signature_add').val(
                createSignaturePad.isEmpty() ? '' : createSignaturePad.toDataURL()
            );

            $.ajax({
                url: "{{ url('/barang_masuk_create') }}",
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
                                "{{ url('/barang_masuk_detail') }}/" + data
                                .id_bapb +
                                "/{{ $menu_id }}";
                        });
                    });

                    $('#form_tambah_barang_masuk')[0].reset();
                    createSignaturePad.clear();
                    $('#modal_barang_masuk').modal('hide');

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
        $(document).on('click', '#btn_delete_barang_masuk', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/barang_masuk_delete') }}",
                type: "POST",
                data: $('#form_delete_barang_masuk').serialize(),
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



                    $('#form_delete_barang_masuk')[0].reset();
                    $('#modal_delete_barang_masuk').modal('hide');

                    table.ajax.reload();

                    Swal.fire({
                        title: 'Sukses!',
                        text: 'Data Berhasil Dihapus',
                        icon: 'success'
                    });

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
        $(document).on('click', '#btn_update_barang_masuk', function(e) {
            e.preventDefault();
            // console.log('asoy');
            $('#signature_update').val(
                updateSignaturePad.isEmpty() ? '' : updateSignaturePad.toDataURL()
            );

            $.ajax({
                url: "{{ url('/barang_masuk_update') }}",
                type: "POST",
                data: $('#form_update_barang_masuk').serialize(),
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

                    $('#form_update_barang_masuk')[0].reset();
                    updateSignaturePad.clear();
                    $('#modal_update_barang_masuk').modal('hide');

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
    });
</script>
{{-- End Modal --}}

@include('template_bawah')
