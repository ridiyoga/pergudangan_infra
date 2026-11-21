@include('template_atas')
@include('template_css')
@include('sweetalert::alert')
<main class="flex-grow-1 p-4">
    <h3 class="h3 mb-0">Master Supplier</h3>
    <div class="my-3">
        <div class="shadow mb-2 p-3 table-responsive bg-white">
            <button id="btn_tambah_supplier" type="button" class="btn btn-primary my-3"><i
                    class="bi bi-plus-circle"></i>&nbsp;Tambah</button>
            <table id="tabel_supplier" class="table" style="font-size: small;">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>NAMA SUPPLIER</th>
                        <th>PENGINPUT / PENGEDIT</th>
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
<div class="modal fade" id="modal_supplier" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_tambah_supplier">
                    @csrf
                    <div class="mb-2">
                        <label for="nama_supplier" class="form-label">Nama Supplier</label>
                        <input type="text" class="form-control" placeholder="Nama Supplier" id="nama_supplier_add"
                            name="nama_supplier">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_create_supplier">Simpan</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Delete --}}
{{-- <div class="modal fade" id="modal_delete_supplier" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <i class="bi bi-exclamation-circle-fill text-danger"></i>&nbsp;
                    <p>Apakah anda ingin menghapus data ini?</p>
                </div>
                <form id="form_delete_supplier">
                    @csrf
                    <input type="hidden" class="form-control" id="id_supplier_delete" name="id_supplier_delete">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn_delete_supplier">Hapus</button>
            </div>
        </div>
    </div>
</div> --}}
{{-- Modal Update --}}
<div class="modal fade" id="modal_update_supplier" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_update_supplier">
                    @csrf
                    <div class="mb-2">
                        <label for="nama_supplier" class="form-label">Nama Supplier</label>
                        <input type="hidden" class="form-control" id="id_supplier_update" name="id_supplier_update">
                        <input type="text" class="form-control" id="nama_supplier_update" name="nama_supplier">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_update_supplier">Simpan</button>
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

        $(document).on('click', '#btn_tambah_supplier', function(e) {
            $('#modal_supplier').modal('show');
        });
        // $(document).on('click', '#btn_delete_datatable', function(e) {
        //     var id = $(this).data("id");
        //     $('#id_supplier_delete').val(id);
        //     $('#modal_delete_supplier').modal('show');
        // });
        $(document).on('click', '#btn_update_datatable', function(e) {
            var id = $(this).data("id");
            var nama_supplier = $(this).data("nama_supplier");
            $('#id_supplier_update').val(id);
            $('#nama_supplier_update').val(nama_supplier);
            $('#modal_update_supplier').modal('show');
        });
        const table = $('#tabel_supplier').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/supplier_dt') }}",
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
                    data: "nama_supplier",
                    name: "nama_supplier"
                },
                {
                    data: "nama_karyawan",
                    name: "nama_karyawan"
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
        $(document).on('click', '#btn_create_supplier', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/supplier_create') }}",
                type: "POST",
                data: $('#form_tambah_supplier').serialize(),
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

                    $('#form_tambah_supplier')[0].reset();
                    $('#modal_supplier').modal('hide');

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
        // $(document).on('click', '#btn_delete_supplier', function(e) {
        //     e.preventDefault();

        //     $.ajax({
        //         url: "{{ url('/supplier_delete') }}",
        //         type: "POST",
        //         data: $('#form_delete_supplier').serialize(),
        //         dataType: 'json',
        //         beforeSend: function() {
        //             Swal.fire({
        //                 title: 'Memproses...',
        //                 allowOutsideClick: false,
        //                 didOpen: () => Swal.showLoading()
        //             });
        //         },

        //         success: function(data) {
        //             if (data.errors) {
        //                 showValidationErrors(data.errors);
        //                 return;
        //             }

        //             Swal.fire({
        //                 title: 'Sukses!',
        //                 text: 'Data Berhasil Dihapus',
        //                 icon: 'success'
        //             });

        //             $('#form_delete_supplier')[0].reset();
        //             $('#modal_delete_supplier').modal('hide');

        //             table.ajax.reload();
        //         },

        //         error: function(xhr) {
        //             const errors = xhr.responseJSON?.errors;
        //             if (errors) {
        //                 showValidationErrors(errors);
        //                 return;
        //             }

        //             Swal.fire({
        //                 title: 'Error!',
        //                 text: xhr.responseJSON?.message || 'Data Gagal Dihapus',
        //                 icon: 'error'
        //             })
        //         }
        //     });
        // });
        $(document).on('click', '#btn_update_supplier', function(e) {
            e.preventDefault();
            // console.log('asoy');

            $.ajax({
                url: "{{ url('/supplier_update') }}",
                type: "POST",
                data: $('#form_update_supplier').serialize(),
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

                    $('#form_update_supplier')[0].reset();
                    $('#modal_update_supplier').modal('hide');

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
