@include('template_atas')
@include('template_css')
@include('sweetalert::alert')
<main class="flex-grow-1 p-4">
    <h3 class="h3 mb-0">Master User</h3>
    <div class="my-3">
        <div class="shadow mb-2 p-3 table-responsive bg-white">
            <button id="btn_tambah_user" type="button" class="btn btn-primary my-3"><i
                    class="bi bi-plus-circle"></i>&nbsp;Tambah</button>
            <table id="tabel_user" class="table" style="font-size: small;">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>NAMA USER</th>
                        <th>USERNAME</th>
                        <th>PROYEK</th>
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
<div class="modal fade" id="modal_user" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_tambah_user">
                    @csrf
                    <div class="mb-2">
                        <label for="id_karyawan_add" class="form-label">Nama Karyawan</label>
                        <select class="form-select" id="id_karyawan_add" name="id_karyawan">
                            <option value=""></option>
                            @foreach ($karyawan as $ky)
                                <option value="{{ $ky->id }}">{{ $ky->nama_karyawan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" placeholder="Username" id="username_add"
                            name="username">
                    </div>
                    <div class="mb-2">
                        <label for="password_add" class="form-label">Password</label>

                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="Password" id="password_add"
                                name="password">

                            <button class="btn btn-outline-secondary" type="button" id="toggle_password">
                                <i class="bi bi-eye" id="icon_password"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="password_confirm" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" placeholder="Konfirmasi Password"
                            id="password_confirm_add" name="password_confirm">
                    </div>
                    <div class="mb-2">
                        <label for="id_proyek_add" class="form-label">Proyek</label>
                        <select class="form-select" id="id_proyek_add" name="id_proyek">
                            <option value=""></option>
                            @foreach ($proyek as $py)
                                <option value="{{ $py->id }}">{{ $py->nama_proyek }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_create_user">Simpan</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Delete --}}
<div class="modal fade" id="modal_delete_user" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <i class="bi bi-exclamation-circle-fill text-danger"></i>&nbsp;
                    <p>Apakah anda ingin menghapus data ini?</p>
                </div>
                <form id="form_delete_user">
                    @csrf
                    <input type="hidden" class="form-control" id="id_user_delete" name="id_user_delete">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn_delete_user">Hapus</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Update --}}
<div class="modal fade" id="modal_update_user" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_update_user">
                    @csrf
                    <div class="mb-2">
                        <label for="id_karyawan_update" class="form-label">Nama Karyawan</label>
                        <select class="form-select" id="id_karyawan_update" name="id_karyawan">
                            <option value=""></option>
                            @foreach ($karyawan as $ky)
                                <option value="{{ $ky->id }}">{{ $ky->nama_karyawan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="username" class="form-label">Username</label>
                        <input type="hidden" class="form-control" id="id_user_update" name="id_user_update">
                        <input type="text" class="form-control" id="username_update" name="username">
                    </div>
                    <div class="mb-2">
                        <label for="id_proyek_update" class="form-label">Proyek</label>
                        <select class="form-select" id="id_proyek_update" name="id_proyek">
                            <option value=""></option>
                            @foreach ($proyek as $py)
                                <option value="{{ $py->id }}">{{ $py->nama_proyek }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_update_user">Simpan</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Password --}}
<div class="modal fade" id="modal_password_karyawan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_password_karyawan">
                    @csrf
                    <input type="hidden" class="form-control" id="id_karyawan_password"
                        name="id_karyawan_password">
                    <div class="mb-2">
                        <label for="password_update" class="form-label">Password</label>

                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="Password" id="password_update"
                                name="password">

                            <button class="btn btn-outline-secondary" type="button" id="toggle_password_update">
                                <i class="bi bi-eye" id="icon_password_update"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="password_confirm" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" placeholder="Konfirmasi Password"
                            id="password_confirm_update" name="password_confirm">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn_password_karyawan">Hapus</button>
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
            })
        }

        $('#id_proyek_add').select2({
            dropdownParent: $('#modal_user'),
            width: '100%',
            placeholder: 'Pilih Proyek',
            allowClear: true
        });
        $('#id_karyawan_add').select2({
            dropdownParent: $('#modal_user'),
            width: '100%',
            placeholder: 'Pilih Karyawan',
            allowClear: true
        });
        $('#id_proyek_update').select2({
            dropdownParent: $('#modal_update_user'),
            width: '100%',
            placeholder: 'Pilih Proyek',
            allowClear: true
        });
        $('#id_karyawan_update').select2({
            dropdownParent: $('#modal_update_user'),
            width: '100%',
            placeholder: 'Pilih Karyawan',
            allowClear: true
        });

        $(document).on('click', '#toggle_password', function() {
            const password = $('#password_add');
            const icon = $('#icon_password');

            if (password.attr('type') === 'password') {
                password.attr('type', 'text');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                password.attr('type', 'password');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });

        $(document).on('click', '#toggle_password_update', function() {
            const password = $('#password_update');
            const icon = $('#icon_password_update');

            if (password.attr('type') === 'password') {
                password.attr('type', 'text');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                password.attr('type', 'password');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });


        $(document).on('click', '#btn_tambah_user', function(e) {
            $('#modal_user').modal('show');
        });
        $(document).on('click', '#btn_delete_datatable', function(e) {
            var id = $(this).data("id");
            $('#id_user_delete').val(id);
            $('#modal_delete_user').modal('show');
        });
        $(document).on('click', '#btn_update_datatable', function(e) {
            var id = $(this).data("id");
            var id_karyawan = $(this).data("id_karyawan");
            var id_proyek = $(this).data("id_proyek");
            var username = $(this).data("username");

            $('#id_user_update').val(id);
            $('#id_karyawan_update').val(id_karyawan).trigger('change');
            $('#id_proyek_update').val(id_proyek).trigger('change');
            $('#username_update').val(username);

            $('#modal_update_user').modal('show');
        });
        $(document).on('click', '#btn_password_datatable', function(e) {
            var id = $(this).data("id");

            $('#id_karyawan_password').val(id);

            $('#modal_password_karyawan').modal('show');
        });
        const table = $('#tabel_user').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/user_dt') }}",
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
                    data: "nama_karyawan",
                    name: "nama_karyawan"
                },
                {
                    data: "username",
                    name: "username"
                },
                {
                    data: "nama_proyek",
                    name: "nama_proyek"
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
        $(document).on('click', '#btn_create_user', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/user_create') }}",
                type: "POST",
                data: $('#form_tambah_user').serialize(),
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
                    $('#id_karyawan_add').val('').trigger('change');
                    $('#id_proyek_add').val('').trigger('change');
                    $('#form_tambah_user')[0].reset();
                    $('#modal_user').modal('hide');

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
        $(document).on('click', '#btn_delete_user', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/user_delete') }}",
                type: "POST",
                data: $('#form_delete_user').serialize(),
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

                    $('#form_delete_user')[0].reset();
                    $('#modal_delete_user').modal('hide');

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
        $(document).on('click', '#btn_update_user', function(e) {
            e.preventDefault();
            // console.log('asoy');

            $.ajax({
                url: "{{ url('/user_update') }}",
                type: "POST",
                data: $('#form_update_user').serialize(),
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

                    $('#form_update_user')[0].reset();
                    $('#modal_update_user').modal('hide');

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
