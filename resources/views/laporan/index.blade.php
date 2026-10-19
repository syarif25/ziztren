@extends('layouts.app')

@section('title', 'Laporan & Rekapitulasi Beasiswa')
@section('page-title', 'Laporan & Rekapitulasi Beasiswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@push('styles')
    <style>
        .stat-card {
            border-radius: 10px;
            transition: all 0.25s ease-in-out;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }
        .filter-box {
            background-color: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 18px 20px;
        }
    </style>
@endpush

@section('content')
    {{-- ======================== FILTER CARD ======================== --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="ri-filter-3-line text-primary me-1"></i> Filter Rekapitulasi Data</h5>
                    <form id="filterForm" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label font-size-13 fw-bold">Periode / Gelombang</label>
                            <select class="form-select select2" id="filter_periode" name="periode_id">
                                <option value="">-- Semua Periode --</option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->id }}">Gel {{ $p->gelombang }} - Tahun {{ $p->tahun_masehi }} ({{ $p->nama_periode }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label font-size-13 fw-bold">Status Pengajuan</label>
                            <select class="form-select" id="filter_status" name="status_pengajuan">
                                <option value="">-- Semua Status --</option>
                                <option value="draft">Draft</option>
                                <option value="proses">Proses</option>
                                <option value="acc">ACC</option>
                                <option value="pencairan">Pencairan</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label font-size-13 fw-bold">Lembaga Pendidikan</label>
                            <select class="form-select select2" id="filter_lembaga" name="lembaga_id">
                                <option value="">-- Semua Lembaga --</option>
                                @foreach($lembagas as $l)
                                    <option value="{{ $l->id }}">{{ $l->nama_lembaga }} {{ $l->singkatan ? '('.$l->singkatan.')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label font-size-13 fw-bold">Tanggal Awal</label>
                            <input type="date" class="form-control" id="filter_tgl_awal" name="tanggal_awal">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label font-size-13 fw-bold">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="filter_tgl_akhir" name="tanggal_akhir">
                        </div>

                        <div class="col-12 mt-3 pt-2 border-top d-flex flex-wrap gap-2 justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <button type="button" id="btnFilter" class="btn btn-primary px-3">
                                    <i class="ri-search-line align-middle me-1"></i> Terapkan Filter
                                </button>
                                <button type="button" id="btnReset" class="btn btn-light px-3">
                                    <i class="ri-refresh-line align-middle me-1"></i> Reset
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" id="btnExportExcel" class="btn btn-success px-3">
                                    <i class="ri-file-excel-2-line align-middle me-1"></i> Export Excel (.csv)
                                </button>
                                <button type="button" id="btnCetakPdf" class="btn btn-danger px-3">
                                    <i class="ri-printer-line align-middle me-1"></i> Cetak Rekap PDF
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================== SUMMARY CARDS (STATISTIK DANA) ======================== --}}
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-white-50 font-size-13 mb-1">TOTAL DANA TERSALUR</p>
                            <h4 class="text-white mb-0" id="stat_grand_total">Rp 0</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-white bg-opacity-25 rounded-circle font-size-22">
                                <i class="ri-money-dollar-circle-line"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mt-3 pt-1 border-top border-white border-opacity-25 font-size-12 text-white-50 d-flex justify-content-between">
                        <span>Jumlah Santri:</span>
                        <strong class="text-white" id="stat_total_santri">0 Santri</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm border-start border-4 border-info">
                <div class="card-body">
                    <p class="text-muted font-size-13 mb-1">TOTAL BANTUAN UTAP</p>
                    <h4 class="text-dark mb-0" id="stat_total_utap">Rp 0</h4>
                    <p class="text-muted mb-0 font-size-12 mt-3 pt-1 border-top">Uang Tahunan & Pondok</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm border-start border-4 border-success">
                <div class="card-body">
                    <p class="text-muted font-size-13 mb-1">TOTAL BANTUAN SPP</p>
                    <h4 class="text-dark mb-0" id="stat_total_spp">Rp 0</h4>
                    <p class="text-muted mb-0 font-size-12 mt-3 pt-1 border-top">Semester Gasal & Genap</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm border-start border-4 border-warning">
                <div class="card-body">
                    <p class="text-muted font-size-13 mb-1">LIVING COST & BIAYA LAIN</p>
                    <h4 class="text-dark mb-0" id="stat_total_living">Rp 0</h4>
                    <p class="text-muted mb-0 font-size-12 mt-3 pt-1 border-top" id="stat_total_lain">Biaya Lain: Rp 0</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================== STATUS BADGES INFO ======================== --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2 align-items-center bg-white p-2 px-3 rounded shadow-sm border">
                <span class="font-size-13 fw-bold me-2"><i class="ri-pie-chart-line text-primary me-1"></i> Status Pengajuan:</span>
                <span class="badge bg-secondary font-size-12 px-2 py-1">Draft: <b id="count_draft">0</b></span>
                <span class="badge bg-info font-size-12 px-2 py-1">Proses: <b id="count_proses">0</b></span>
                <span class="badge bg-primary font-size-12 px-2 py-1">ACC: <b id="count_acc">0</b></span>
                <span class="badge bg-success font-size-12 px-2 py-1">Pencairan: <b id="count_pencairan">0</b></span>
            </div>
        </div>
    </div>

    {{-- ======================== TABEL DATA ======================== --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="laporanTable" class="table table-bordered table-hover align-middle w-100 font-size-13">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;">No</th>
                                    <th>NIS</th>
                                    <th>Nama Santri</th>
                                    <th>Lembaga</th>
                                    <th>Periode</th>
                                    <th>UTAP</th>
                                    <th>SPP Gasal+Genap</th>
                                    <th>Living Cost</th>
                                    <th>Biaya Lain</th>
                                    <th>Total Bantuan</th>
                                    <th>Status</th>
                                    <th>Tgl Pengajuan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable Server-Side
        var table = $('#laporanTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('laporan.data') }}",
                data: function(d) {
                    d.periode_id = $('#filter_periode').val();
                    d.status_pengajuan = $('#filter_status').val();
                    d.lembaga_id = $('#filter_lembaga').val();
                    d.tanggal_awal = $('#filter_tgl_awal').val();
                    d.tanggal_akhir = $('#filter_tgl_akhir').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'nis', name: 'nis', className: 'fw-bold' },
                { data: 'santri_nama', name: 'santri.nama_lengkap' },
                { data: 'lembaga_nama', name: 'lembaga1.nama_lembaga', orderable: false },
                { data: 'periode_text', name: 'periode.nama_periode', orderable: false },
                { data: 'jumlah_utap', name: 'jumlah_utap' },
                { data: 'total_spp', name: 'spp_gasal', orderable: false },
                { data: 'living_cost', name: 'living_cost' },
                { data: 'biaya_lain', name: 'biaya_lain' },
                { data: 'grand_total', name: 'grand_total', orderable: false },
                { data: 'status_pengajuan', name: 'status_pengajuan', className: 'text-center' },
                { data: 'created_at', name: 'created_at' }
            ],
            language: {
                search: "Cari Cepat:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada data pengajuan beasiswa yang cocok",
                info: "Menampilkan halaman _PAGE_ dari _PAGES_ (_TOTAL_ total data)",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Lanjut",
                    previous: "Kembali"
                }
            }
        });

        // Function Load Summary KPI Cards
        function loadSummary() {
            $.ajax({
                url: "{{ route('laporan.summary') }}",
                type: "GET",
                data: {
                    periode_id: $('#filter_periode').val(),
                    status_pengajuan: $('#filter_status').val(),
                    lembaga_id: $('#filter_lembaga').val(),
                    tanggal_awal: $('#filter_tgl_awal').val(),
                    tanggal_akhir: $('#filter_tgl_akhir').val()
                },
                success: function(res) {
                    $('#stat_grand_total').text(res.grand_total);
                    $('#stat_total_santri').text(res.total_santri + ' Santri');
                    $('#stat_total_utap').text(res.total_utap);
                    $('#stat_total_spp').text(res.total_spp);
                    $('#stat_total_living').text(res.total_living);
                    $('#stat_total_lain').text('Biaya Lain: ' + res.total_lain);

                    $('#count_draft').text(res.count_draft);
                    $('#count_proses').text(res.count_proses);
                    $('#count_acc').text(res.count_acc);
                    $('#count_pencairan').text(res.count_pencairan);
                }
            });
        }

        // Jalankan saat load awal
        loadSummary();

        // Terapkan Filter
        $('#btnFilter').on('click', function(e) {
            e.preventDefault();
            table.draw();
            loadSummary();
        });

        // Reset Filter
        $('#btnReset').on('click', function(e) {
            e.preventDefault();
            $('#filterForm')[0].reset();
            table.draw();
            loadSummary();
        });

        // Export Excel / CSV
        $('#btnExportExcel').on('click', function(e) {
            e.preventDefault();
            var params = $.param({
                periode_id: $('#filter_periode').val(),
                status_pengajuan: $('#filter_status').val(),
                lembaga_id: $('#filter_lembaga').val(),
                tanggal_awal: $('#filter_tgl_awal').val(),
                tanggal_akhir: $('#filter_tgl_akhir').val()
            });
            window.location.href = "{{ route('laporan.export.excel') }}?" + params;
        });

        // Cetak Rekap PDF
        $('#btnCetakPdf').on('click', function(e) {
            e.preventDefault();
            var params = $.param({
                periode_id: $('#filter_periode').val(),
                status_pengajuan: $('#filter_status').val(),
                lembaga_id: $('#filter_lembaga').val(),
                tanggal_awal: $('#filter_tgl_awal').val(),
                tanggal_akhir: $('#filter_tgl_akhir').val()
            });
            window.open("{{ route('laporan.cetak.pdf') }}?" + params, '_blank');
        });
    });
</script>
@endpush
