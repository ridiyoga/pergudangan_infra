@include('template_atas')
@include('template_css')
@include('sweetalert::alert')
<main class="flex-grow-1 p-4">
    <h3 class="h3 mb-0">Master Menu</h3>
    <div class="my-3">
        <div class="shadow mb-2 p-3 table-responsive bg-white">
            <button id="btn_tambah_menu" type="button" class="btn btn-primary my-3"><i
                    class="bi bi-plus-circle"></i>&nbsp;Tambah</button>
            <table id="tabel_menu" class="table" style="font-size: small;">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>NAMA MENU</th>
                        <th>ROUTE</th>
                        <th>ICON</th>
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
<div class="modal fade" id="modal_menu" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_tambah_menu">
                    @csrf
                    <div class="mb-2">
                        <label for="nama_menu" class="form-label">Nama Menu</label>
                        <input type="text" class="form-control" id="nama_menu" name="nama_menu">
                    </div>
                    <div class="mb-2">
                        <label for="nama_menu" class="form-label">Route</label>
                        <input type="text" class="form-control" id="route" name="route">
                    </div>
                    <div class="mb-2">
                        <label for="nama_menu" class="form-label">Bootstrap Icon</label>
                        <input type="text" class="form-control" id="icon" name="icon">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_create_menu">Simpan</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Delete --}}
<div class="modal fade" id="modal_delete_menu" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <i class="bi bi-exclamation-circle-fill text-danger"></i>&nbsp;
                    <p>Apakah anda ingin menghapus data ini?</p>
                </div>
                <form id="form_delete_menu">
                    @csrf
                    <input type="hidden" class="form-control" id="id_menu_delete" name="id_menu_delete">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn_delete_menu">Hapus</button>
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
    $(document).ready(function() {
        function showValidationErrors(errors) {
            Swal.fire({
                title: 'Validasi gagal',
                text: Object.values(errors).flat().join('\n'),
                icon: 'error'
            });
        }

        $(document).on('click', '#btn_tambah_menu', function(e) {
            $('#modal_menu').modal('show');
        });
        $(document).on('click', '#btn_delete_datatable', function(e) {
            var id = $(this).data("id");
            $('#id_menu_delete').val(id);
            $('#modal_delete_menu').modal('show');
        });
        const table = $('#tabel_menu').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/menu_dt') }}",
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
                    data: "nama_menu",
                    name: "nama_menu"
                },
                {
                    data: "route",
                    name: "route"
                },
                {
                    data: "icon",
                    name: "icon"
                },
                {
                    data: "aksi",
                    name: "aksi",
                    orderable: false,
                    searchable: false
                },
            ]
        });
        $(document).on('click', '#btn_create_menu', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/menu_create') }}",
                type: "POST",
                data: $('#form_tambah_menu').serialize(),
                dataType: 'json',

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

                    $('#form_tambah_menu')[0].reset();
                    bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('modal_menu')
                    ).hide();

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
                    });
                }
            });
        });
        $(document).on('click', '#btn_delete_menu', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/menu_delete') }}",
                type: "POST",
                data: $('#form_delete_menu').serialize(),
                dataType: 'json',

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

                    $('#form_delete_menu')[0].reset();
                    bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('modal_delete_menu')
                    ).hide();

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
                    });
                }
            });
        });
    });
</script>
{{-- End Modal --}}

@include('template_bawah')
