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
                                <th>TANGGAL MASUK</th>
                                <th>NAMA SUPPLIER</th>
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
                                    <th>TANGGAL MASUK</th>
                                    <th>NAMA SUPPLIER</th>
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
                <div class="tab-pane" id="keuangan" role="tabpanel" aria-labelledby="keuangan-tab" tabindex="0">
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
                        <div id="table_keuangan_div">
                            <table id="tabel_keuangan" class="table" style="font-size: small;">
                                <thead>
                                    <tr>
                                        <th>NO.</th>
                                        <th>No. BAPB</th>
                                        <th>TANGGAL MASUK</th>
                                        <th>NAMA SUPPLIER</th>
                                        <th>GUDANG</th>
                                        <th>KOMERSIAL</th>
                                        <th>STATUS</th>
                                        <th>KEUANGAN</th>
                                        <th>TTD KEUANGAN</th>
                                        <th>DSISETUJUI PADA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

<!-- DataTables JS -->
<!-- Date Range Picker CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<!-- Date Range Picker JS -->
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
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
                    data: "tanggal_masuk",
                    name: "tanggal_masuk"
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
                    data: "tanggal_masuk",
                    name: "tanggal_masuk"
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

        // Filter table

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
        $('#table_keuangan_div').hide();
        $('#btn_filter').click(function(e) {

            $('#table_keuangan_div').show();
            $('#content_null').empty();
            table3.ajax.reload(null, true);


        });
        var start_date = $('#start_date').val() || '';
        var end_date = $('#end_date').val() || '';
        const table3 = $('#tabel_keuangan').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,

            ajax: {
                url: "{{ url('/barang_masuk_keuangan_dt2/') }}" + '/' + "{{ $menu_id }}",
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
                    data: "tanggal_masuk",
                    name: "tanggal_masuk"
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
                    data: "nama_atasan2",
                    name: "nama_atasan2"
                },
                {
                    data: "status",
                    name: "status"
                },
                {
                    data: "nama_atasan3",
                    name: "nama_atasan3"
                },
                {
                    data: "ttd_atasan3",
                    name: "ttd_atasan3"
                },
                {
                    data: "waktu_atasan3",
                    name: "waktu_atasan3"
                },
            ],
            order: [
                [1, 'asc']
            ],
        });
    });
</script>

@include('template_bawah')
