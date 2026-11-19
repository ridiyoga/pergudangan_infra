@include('template_atas')
@include('template_css')
@include('sweetalert::alert')
<main class="flex-grow-1 p-4">
    <h3 class="h3 mb-0">Master Log</h3>
    <div class="my-3">
        <div class="shadow mb-2 p-3 table-responsive bg-white">
            <table id="tabel_log" class="table" style="font-size: small;">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>NAMA KARYAWAN</th>
                        <th>AKSI</th>
                        <th>DESKRIPSI</th>
                        <th>NAMA MENU</th>
                        <th>NAMA TABEL</th>
                        <th>ID TABEL</th>
                        <th>KETERANGAN</th>
                        <th>DIBUAT PADA</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</main>
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
        const table = $('#tabel_log').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/log_dt') }}",
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
                    data: "aksi",
                    name: "aksi"
                },
                {
                    data: "deskripsi",
                    name: "deskripsi"
                },
                {
                    data: "nama_menu",
                    name: "nama_menu"
                },
                {
                    data: "nama_tabel",
                    name: "nama_tabel"
                },
                {
                    data: "id_tabel_baru",
                    name: "id_tabel_baru"
                },
                {
                    data: "keterangan",
                    name: "keterangan"
                },
                {
                    data: "created_at",
                    name: "created_at"
                }
            ],
            order: [
                [7, 'desc']
            ],
        });
    });
</script>
{{-- End Modal --}}

@include('template_bawah')
