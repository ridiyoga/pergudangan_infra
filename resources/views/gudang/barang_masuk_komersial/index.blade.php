@include('template_atas')
@include('template_css')
@include('sweetalert::alert')
<main class="flex-grow-1 p-4">
    <h3 class="h3 mb-0">No. BAPB (Komersial)</h3>
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
                                    <th>DSISETUJUI PADA</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="keuangan" role="tabpanel" aria-labelledby="keuangan-tab" tabindex="0">...
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tabel_barang_masuk').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/barang_masuk_komersial_dt/') }}" + '/' + "{{ $menu_id }}",
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
        const table2 = $('#tabel_komersial').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,

            ajax: {
                url: "{{ url('/barang_masuk_komersial_dt2/') }}" + '/' + "{{ $menu_id }}",
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
                    data: "waktu_atasan2",
                    name: "waktu_atasan2"
                },
                {
                    data: "aksi",
                    name: "aksi"
                },
            ],
            order: [
                [7, 'asc']
            ],
        });
        $('#komersial-tab').on('click', function() {
            table2.ajax.reload(null, false);
        });
    });
</script>

@include('template_bawah')
