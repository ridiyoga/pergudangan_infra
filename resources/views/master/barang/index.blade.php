@include('template_atas')
@include('template_css')
@include('sweetalert::alert')
<main class="flex-grow-1 p-4">
    <h3 class="h3 mb-0">Master Barang</h3>
    <div class="my-3">
        <div class="shadow mb-2 p-3 table-responsive bg-white">
            <button id="btn_tambah_barang" type="button" class="btn btn-primary my-3"><i
                    class="bi bi-plus-circle"></i>&nbsp;Tambah</button>
            <table id="tabel_barang" class="table" style="font-size: small;">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>NAMA BARANG</th>
                        <th>KODE BARANG</th>
                        <th>SATUAN</th>
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
                    <div class="mb-2">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" placeholder="Nama Barang" id="nama_barang_add"
                            name="nama_barang">
                    </div>
                    <div class="mb-2">
                        <label for="kode_barang" class="form-label">Kode Barang</label>
                        <input type="text" class="form-control" placeholder="Kode Barang" id="kode_barang_add"
                            name="kode_barang">
                    </div>
                    <div class="mb-2">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" class="form-control" placeholder="Satuan" id="satuan_add" name="satuan">
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
{{-- <div class="modal fade" id="modal_delete_barang" tabindex="-1">
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
                    <input type="hidden" class="form-control" id="id_barang_delete" name="id_barang_delete">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn_delete_barang">Hapus</button>
            </div>
        </div>
    </div>
</div> --}}
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
                    <div class="mb-2">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="hidden" class="form-control" id="id_barang_update" name="id_barang_update">
                        <input type="text" class="form-control" id="nama_barang_update" name="nama_barang">
                    </div>
                    <div class="mb-2">
                        <label for="kode_barang" class="form-label">Kode Barang</label>
                        <input type="text" class="form-control" placeholder="Kode Barang" id="kode_barang_update"
                            name="kode_barang">
                    </div>
                    <div class="mb-2">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" class="form-control" id="satuan_update" name="satuan">
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

        $(document).on('click', '#btn_tambah_barang', function(e) {
            $('#modal_barang').modal('show');
        });
        // $(document).on('click', '#btn_delete_datatable', function(e) {
        //     var id = $(this).data("id");
        //     $('#id_barang_delete').val(id);
        //     $('#modal_delete_barang').modal('show');
        // });
        $(document).on('click', '#btn_update_datatable', function(e) {
            var id = $(this).data("id");
            var nama_barang = $(this).data("nama_barang");
            var kode_barang = $(this).data("kode_barang");
            var satuan = $(this).data("satuan");
            $('#id_barang_update').val(id);
            $('#nama_barang_update').val(nama_barang);
            $('#kode_barang_update').val(kode_barang);
            $('#satuan_update').val(satuan);
            $('#modal_update_barang').modal('show');
        });
        const table = $('#tabel_barang').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/barang_dt') }}",
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
                    data: "kode_barang",
                    name: "kode_barang"
                },
                {
                    data: "satuan",
                    name: "satuan"
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
        $(document).on('click', '#btn_create_barang', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/barang_create') }}",
                type: "POST",
                data: $('#form_tambah_barang').serialize(),
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

                    $('#form_tambah_barang')[0].reset();
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
        // $(document).on('click', '#btn_delete_barang', function(e) {
        //     e.preventDefault();

        //     $.ajax({
        //         url: "{{ url('/barang_delete') }}",
        //         type: "POST",
        //         data: $('#form_delete_barang').serialize(),
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

        //             $('#form_delete_barang')[0].reset();
        //             $('#modal_delete_barang').modal('hide');

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
        $(document).on('click', '#btn_update_barang', function(e) {
            e.preventDefault();
            // console.log('asoy');

            $.ajax({
                url: "{{ url('/barang_update') }}",
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
    });
</script>
{{-- End Modal --}}

@include('template_bawah')
