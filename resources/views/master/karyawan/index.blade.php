@include('template_atas')
@include('template_css')
@include('sweetalert::alert')
<main class="flex-grow-1 p-4">
    <h3 class="h3 mb-0">Master Karyawan</h3>
    <div class="my-3">
        <div class="shadow mb-2 p-3 table-responsive bg-white">
            <button id="btn_tambah_karyawan" type="button" class="btn btn-primary my-3"><i
                    class="bi bi-plus-circle"></i>&nbsp;Tambah</button>
            <table id="tabel_karyawan" class="table" style="font-size: small;">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>NAMA KARYAWAN</th>
                        <th>KTP</th>
                        <th>EMAIL</th>
                        <th>WHATSAPP</th>
                        <th>JABATAN</th>
                        <th>AKTIF?</th>
                        <th>AREA</th>
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
<div class="modal fade" id="modal_karyawan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_tambah_karyawan">
                    @csrf
                    <div class="mb-2">
                        <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                        <input type="text" class="form-control" placeholder="Nama Karyawan" id="nama_karyawan_add"
                            name="nama_karyawan">
                    </div>
                    <div class="mb-2">
                        <label for="no_ktp" class="form-label">No. KTP (Opsional)</label>
                        <input type="number" class="form-control" placeholder="No. KTP" id="no_ktp_add" name="no_ktp">
                    </div>
                    <div class="mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" placeholder="email@mail.com" id="email_add"
                            name="email">
                    </div>
                    <div class="mb-2">
                        <label for="no_wa" class="form-label">No. Whatsapp </label>
                        <input type="number" class="form-control" placeholder="No. Whatsapp" id="no_wa_add"
                            name="no_wa">
                    </div>
                    <div class="mb-2">
                        <label for="id_jabatan_add" class="form-label">Jabatan</label>
                        <select class="form-select" id="id_jabatan_add" name="id_jabatan">
                            <option value=""></option>
                            @foreach ($jabatan as $jbtn)
                                <option value="{{ $jbtn->id }}">{{ $jbtn->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="is_active_add" class="form-label">Aktif?</label>
                        <select class="form-select" id="is_active_add" name="is_active">
                            <option value=""></option>
                            <option value="1">AKTIF</option>
                            <option value="2">NON AKTIF</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="id_area_add" class="form-label">Area</label>
                        <select class="form-select" id="id_area_add" name="id_area">
                            <option value=""></option>
                            @foreach ($area as $ar)
                                <option value="{{ $ar->id }}">{{ $ar->nama_area }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_create_karyawan">Simpan</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Delete --}}
<div class="modal fade" id="modal_delete_karyawan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <i class="bi bi-exclamation-circle-fill text-danger"></i>&nbsp;
                    <p>Apakah anda ingin menghapus data ini?</p>
                </div>
                <form id="form_delete_karyawan">
                    @csrf
                    <input type="hidden" class="form-control" id="id_karyawan_delete" name="id_karyawan_delete">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn_delete_karyawan">Hapus</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Update --}}
<div class="modal fade" id="modal_update_karyawan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_update_karyawan">
                    @csrf
                    <div class="mb-2">
                        <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                        <input type="hidden" class="form-control" id="id_karyawan_update"
                            name="id_karyawan_update">
                        <input type="text" class="form-control" id="nama_karyawan_update" name="nama_karyawan">
                    </div>
                    <div class="mb-2">
                        <label for="no_ktp" class="form-label">No. KTP (Opsional)</label>
                        <input type="number" class="form-control" placeholder="No. KTP" id="no_ktp_update"
                            name="no_ktp">
                    </div>
                    <div class="mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" placeholder="email@mail.com" id="email_update"
                            name="email">
                    </div>
                    <div class="mb-2">
                        <label for="no_wa" class="form-label">No. Whatsapp </label>
                        <input type="number" class="form-control" placeholder="No. Whatsapp" id="no_wa_update"
                            name="no_wa">
                    </div>
                    <div class="mb-2">
                        <label for="id_jabatan_update" class="form-label">Jabatan</label>
                        <select class="form-select" id="id_jabatan_update" name="id_jabatan">
                            <option value=""></option>
                            @foreach ($jabatan as $jbtn)
                                <option value="{{ $jbtn->id }}">{{ $jbtn->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="is_active_update" class="form-label">Aktif?</label>
                        <select class="form-select" id="is_active_update" name="is_active">
                            <option value=""></option>
                            <option value="1">AKTIF</option>
                            <option value="2">NON AKTIF</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="id_area_update" class="form-label">Area</label>
                        <select class="form-select" id="id_area_update" name="id_area">
                            <option value=""></option>
                            @foreach ($area as $ar)
                                <option value="{{ $ar->id }}">{{ $ar->nama_area }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_update_karyawan">Simpan</button>
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
    $(document).on('input', '#nama_karyawan_add', function() {
        this.value = this.value.toUpperCase();
    });
    $(document).on('input', '#nama_karyawan_update', function() {
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
        // select 2
        $('#id_jabatan_add').select2({
            dropdownParent: $('#modal_karyawan'),
            width: '100%',
            placeholder: 'Pilih Jabatan',
            allowClear: true
        });
        $('#is_active_add').select2({
            dropdownParent: $('#modal_karyawan'),
            width: '100%',
            placeholder: 'Aktif / Non Aktif',
            allowClear: true
        });
        $('#id_area_add').select2({
            dropdownParent: $('#modal_karyawan'),
            width: '100%',
            placeholder: 'Pilih Area',
            allowClear: true
        });

        $('#id_jabatan_update').select2({
            dropdownParent: $('#modal_update_karyawan'),
            width: '100%',
            placeholder: 'Pilih Jabatan',
            allowClear: true
        });
        $('#is_active_update').select2({
            dropdownParent: $('#modal_update_karyawan'),
            width: '100%',
            placeholder: 'Aktif / Non Aktif',
            allowClear: true
        });
        $('#id_area_update').select2({
            dropdownParent: $('#modal_update_karyawan'),
            width: '100%',
            placeholder: 'Pilih Area',
            allowClear: true
        });

        $(document).on('click', '#btn_tambah_karyawan', function(e) {
            $('#modal_karyawan').modal('show');
        });
        $(document).on('click', '#btn_delete_datatable', function(e) {
            var id = $(this).data("id");
            $('#id_karyawan_delete').val(id);
            $('#modal_delete_karyawan').modal('show');
        });
        $(document).on('click', '#btn_update_datatable', function(e) {
            var id = $(this).data("id");
            var nama_karyawan = $(this).data("nama_karyawan");
            var no_ktp = $(this).data("no_ktp");
            var no_wa = $(this).data("no_wa");
            var email = $(this).data("email");
            var id_jabatan = $(this).data("id_jabatan");
            var is_active = $(this).data("is_active");
            var id_area = $(this).data("id_area");

            $('#id_karyawan_update').val(id);
            $('#nama_karyawan_update').val(nama_karyawan);
            $('#no_ktp_update').val(no_ktp);
            $('#no_wa_update').val(no_wa);
            $('#email_update').val(email);
            $('#id_jabatan_update').val(id_jabatan).trigger('change');
            $('#is_active_update').val(is_active).trigger('change');
            $('#id_area_update').val(id_area).trigger('change');

            $('#modal_update_karyawan').modal('show');
        });
        const table = $('#tabel_karyawan').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/karyawan_dt') }}",
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
                    data: "no_ktp",
                    name: "no_ktp"
                },
                {
                    data: "email",
                    name: "email"
                },
                {
                    data: "no_wa",
                    name: "no_wa"
                },
                {
                    data: "nama_jabatan",
                    name: "nama_jabatan"
                },
                {
                    data: "is_active",
                    name: "is_active"
                },
                {
                    data: "nama_area",
                    name: "nama_area"
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
        $(document).on('click', '#btn_create_karyawan', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/karyawan_create') }}",
                type: "POST",
                data: $('#form_tambah_karyawan').serialize(),
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

                    $('#form_tambah_karyawan')[0].reset();
                    $('#id_jabatan_add, #is_active_add, #id_area_add').val('').trigger(
                        'change');
                    $('#modal_karyawan').modal('hide');

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
        $(document).on('click', '#btn_delete_karyawan', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('/karyawan_delete') }}",
                type: "POST",
                data: $('#form_delete_karyawan').serialize(),
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

                    $('#form_delete_karyawan')[0].reset();
                    $('#modal_delete_karyawan').modal('hide');

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
        $(document).on('click', '#btn_update_karyawan', function(e) {
            e.preventDefault();
            // console.log('asoy');

            $.ajax({
                url: "{{ url('/karyawan_update') }}",
                type: "POST",
                data: $('#form_update_karyawan').serialize(),
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

                    $('#form_update_karyawan')[0].reset();
                    $('#modal_update_karyawan').modal('hide');

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
