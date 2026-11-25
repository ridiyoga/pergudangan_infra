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
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="gudang-tab" data-bs-toggle="tab" data-bs-target="#home"
                        type="button" role="tab" aria-controls="home" aria-selected="true">Gudang</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="komersial-tab" data-bs-toggle="tab" data-bs-target="#komersial"
                        type="button" role="tab" aria-controls="komersial" aria-selected="false">Komersial</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="keuangan-tab" data-bs-toggle="tab" data-bs-target="#keuangan"
                        type="button" role="tab" aria-controls="keuangan" aria-selected="false">Keuangan</button>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="gudang-tab" tabindex="0">
                    <button id="btn_tambah_barang_masuk" type="button" class="btn btn-primary my-3"><i
                            class="bi bi-plus-circle"></i>&nbsp;Tambah</button>
                    <table id="tabel_barang_masuk" class="table" style="font-size: small;">
                        <thead>
                            <tr>
                                <th>NO.</th>
                                <th>No. BAPB</th>
                                <th>PENGINPUT / PENGEDIT</th>
                                <th>STATUS</th>
                                <th>TANDA TANGAN</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="komersial" role="tabpanel" aria-labelledby="komersial-tab" tabindex="0">
                    <div class="my-3">
                        <div class="row gy-4 align-items-end my-3">
                            <div class="col-lg-6 col-md-6col-sm-12">
                                <div class="form-floating form-floating-outline">
                                    <div id="reportrange" style="white-space: nowrap;">
                                        <button class="btn btn-outline-secondary w-100 ">
                                            <span class="fw-bold">FILTER&nbsp;DATE&nbsp;:&nbsp;</span>
                                            <span class="date_daterange"></span>
                                            <input type="date" id="start_date" name="start_date" hidden
                                                value="">
                                            <input type="date" id="end_date" name="end_date" hidden value="">
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-12 col-sm-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-info w-100" id="btn_filter">
                                    <i class="mdi mdi-filter-outline"></i>&nbsp;Filter
                                </button>
                            </div>
                        </div>
                        <div id="content_null">
                            <div class="alert alert-secondary" role="alert">
                                <div class="alert-content text-center">
                                    <svg width="120" height="120" viewBox="0 0 120 120" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">

                                        <rect x="25" y="25" width="70" height="70" rx="8" ry="8"
                                            stroke="#CED4DA" stroke-width="2" />

                                        <path d="M35 35H85" stroke="#E6E8EB" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M35 45H75" stroke="#E6E8EB" stroke-width="1.5"
                                            stroke-linecap="round" />
                                        <path d="M35 55H85" stroke="#E6E8EB" stroke-width="1.5"
                                            stroke-linecap="round" />

                                        <path d="M40 70L80 70" stroke="#7E8A9A" stroke-width="2"
                                            stroke-dasharray="4 4" stroke-linecap="round" />
                                        <path d="M40 80L70 80" stroke="#7E8A9A" stroke-width="2"
                                            stroke-dasharray="4 4" stroke-linecap="round" />

                                    </svg>
                                    <h4 class="alert-heading text-center">Tidak Ada Data</h4>
                                    <p class="text-center">Filter Data Terlebih Dahulu</p>
                                </div>
                            </div>
                        </div>
                        <div id="table_komersial_div">
                            <table id="tabel_komersial" class="table" style="font-size: small;">
                                <thead>
                                    <tr>
                                        <th>NO.</th>
                                        <th>No. BAPB</th>
                                        <th>PENGINPUT / PENGEDIT</th>
                                        <th>TTD GUDANG</th>
                                        <th>STATUS</th>
                                        <th>NAMA KOMERSIAL</th>
                                        <th>TTD KOMERSIAL</th>
                                        <th>DIBUAT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="keuangan" role="tabpanel" aria-labelledby="keuangan-tab" tabindex="0">
                    ...
                </div>
            </div>
        </div>
    </div>
</main>
{{-- Modal Create --}}
<div class="modal fade" id="modal_barang_masuk" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah No. BAPB</h5>
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
                    {{-- <div id="sig_add"
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
                    <input type="hidden" id="e" name="signature"> --}}

                    {{-- <!-- Baris Kontrol Tanda Tangan Add -->
                    <div class="d-flex justify-content-between align-items-center px-1 mb-2">
                        <span class="text-muted small" style="font-size: 0.8rem;">
                            <i class="fa-regular fa-circle-check text-success me-1"></i>Gunakan mouse untuk tanda
                            tangan
                        </span>

                        <button type="button" class="btn btn-outline-danger btn-sm" id="clear_signature_add">
                            <span class="mdi mdi-trash-can-outline"></span>
                            <span>Hapus</span>
                        </button>
                    </div> --}}
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
                <h5 class="modal-title">Ubah No. BAPB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_update_barang_masuk">
                    @csrf
                    <input type="hidden" id="menu_id_update" name="menu_id" value="{{ $menu_id }}">
                    <div class="mb-2">
                        <label for="no_bapb_update" class="form-label">No. BAPB</label>
                        <input type="hidden" class="form-control" id="id_bapb_update" name="id_bapb_update">
                        <input type="text" class="form-control" id="no_bapb_update" name="no_bapb">
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
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>

<!-- Date Range Picker CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

<!-- Date Range Picker JS -->
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
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
            $('#modal_update_barang_masuk').modal('show');
        });
        // $(document).on('click', '#btn_tambah_barang_masuk', function() {
        //     $('#signature-pad-add').removeAttr('hidden');
        //     $('#signature_add').val('');
        //     createSignaturePad.clear();
        //     let id = $(this).data('id');
        //     $('#id_tidak_masuk').val(id);
        // });

        // $('#modal_barang_masuk').on('shown.bs.modal', function() {
        //     resizeCanvas(createCanvas, createSignaturePad);
        //     console.log({
        //         width: createCanvas.width,
        //         height: createCanvas.height
        //     });
        // });
        const table = $('#tabel_barang_masuk').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/barang_masuk_dt/') }}" + '/' + "{{ $menu_id }}",
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
                    data: "status",
                    name: "status"
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
        $('#gudang-tab').on('click', function() {
            table.ajax.reload(null, false);
        });

        const start = moment().subtract(29, 'days');
        const end = moment();

        function cb(start, end) {
            lstart = start.format('YYYY-MM-DD');
            lend = end.format('YYYY-MM-DD');
            $('#start_date').val(lstart);
            $('#end_date').val(lend);
            $('#reportrange .date_daterange').html(start.format('D MMMM YYYY') + ' - ' + end.format(
                'D MMMM YYYY'));
            // console.log(start, end);

        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')]
            }
        }, cb);

        cb(start, end);
        $('#table_komersial_div').hide();
        $('#btn_filter').click(function(e) {

            $('#table_komersial_div').show();
            $('#content_null').empty();
            table2.ajax.reload(null, true);


        });
        var start_date = $('#start_date').val() || '';
        var end_date = $('#end_date').val() || '';
        const table2 = $('#tabel_komersial').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,

            ajax: {
                url: "{{ url('/barang_masuk_dt2/') }}" + '/' + "{{ $menu_id }}",
                data: function(d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
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
                    data: "status",
                    name: "status"
                },
                {
                    data: "nama_atasan2",
                    name: "nama_atasan2"
                },
                {
                    data: "ttd_atasan2",
                    name: "ttd_atasan2"
                },
                {
                    data: "created_at",
                    name: "created_at"
                },
            ],
            order: [
                [1, 'asc']
            ],
        });
        $('#komersial-tab').on('click', function() {
            table2.ajax.reload(null, false);
        });
        $(document).on('click', '#btn_create_barang_masuk', function(e) {
            e.preventDefault();
            // $('#signature_add').val(
            //     createSignaturePad.isEmpty() ? '' : createSignaturePad.toDataURL()
            // );

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
                    // createSignaturePad.clear();
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
