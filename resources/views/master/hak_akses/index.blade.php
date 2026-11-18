@include('template_atas')
@include('template_css')
@include('sweetalert::alert')
<main class="flex-grow-1 p-4">
    <h3 class="h3 mb-0">Master Hak Akses</h3>
    <div class="my-3">
        <div class="shadow mb-2 p-3 table-responsive bg-white">
            <table id="tabel_jabatan" class="table" style="font-size: small;">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>NAMA JABATAN</th>
                        <th>MENU</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</main>
{{-- Modal Update --}}
<div class="modal fade" id="modal_update_jabatan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_update_hak_akses">
                    @csrf
                    <div class="mb-2">
                        <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                        <input type="hidden" class="form-control" id="id_jabatan_update" name="id_jabatan">
                        <input type="text" class="form-control" id="nama_jabatan_update" name="nama_jabatan"
                            readonly>
                    </div>
                    <div class="mb-2 p-3 table-responsive bg-white">
                        <table id="tabel_menu" class="table" style="font-size: small;">
                            <thead>
                                <tr>
                                    <th>NO.</th>
                                    <th>NAMA MENU</th>
                                    <th>CHECK</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_update_akses">Simpan</button>
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

        const table_menu = $('#tabel_menu').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/hak_akses_menu_dt') }}",
                type: "GET",
                data: function(d) {
                    d.id_jabatan = $('#id_jabatan_update').val();
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
                    data: "nama_menu",
                    name: "nama_menu"
                },
                {
                    data: 'check',
                    name: 'check',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'asc']
            ],
        });

        $('#modal_update_jabatan').on('shown.bs.modal', function() {
            table_menu.columns.adjust();
        });

        $(document).on('click', '#btn_update_datatable', function(e) {
            var id = $(this).data("id");
            var nama_jabatan = $(this).data("nama_jabatan");
            $('#id_jabatan_update').val(id);
            $('#nama_jabatan_update').val(nama_jabatan);
            table_menu.ajax.reload();
            $('#modal_update_jabatan').modal('show');
        });
        const table = $('#tabel_jabatan').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/hak_akses_dt') }}",
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
                    data: "nama_jabatan",
                    name: "nama_jabatan"
                },
                {
                    data: "menu",
                    name: "menu"
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
        $(document).on('click', '#btn_update_akses', function(e) {
            e.preventDefault();
            // console.log('asoy');

            $.ajax({
                url: "{{ url('/hak_akses_create') }}",
                type: "POST",
                data: $('#form_update_hak_akses').serialize(),
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

                    $('#form_update_hak_akses')[0].reset();
                    $('#modal_update_jabatan').modal('hide');

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
