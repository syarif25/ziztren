@extends('layouts.app')

@section('title', 'Pencairan & Pencairan Beasiswa')
@section('page-title', 'Pencairan Dana')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="javascript: void(0);">LAZIMY</a></li>
    <li class="breadcrumb-item active">Pencairan Dana</li>
@endsection

@section('content')
    {{-- Header & Statistik Pencairan --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 mb-0 shadow-sm border-start border-4 border-primary">
                <div class="card-body p-3">
                    <span class="text-muted text-uppercase font-size-12 fw-semibold">Jumlah Dana ACC</span>
                    <h3 class="fw-bold text-dark my-2 font-size-20">Rp {{ number_format($totalKomitmenDana, 0, ',', '.') }}</h3>
                    <small class="text-muted"><i class="ri-user-follow-line me-1"></i>{{ $totalPengajuanAcc }} Berkas disetujui</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 mb-0 shadow-sm border-start border-4 border-success">
                <div class="card-body p-3">
                    <span class="text-muted text-uppercase font-size-12 fw-semibold">Total Terealisasi</span>
                    <h3 class="fw-bold text-success my-2 font-size-20">Rp {{ number_format($totalTerealisasi, 0, ',', '.') }}</h3>
                    <!-- <small class="text-success"><i class="ri-checkbox-circle-line me-1"></i>Dana berhasil disalurkan</small> -->
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 mb-0 shadow-sm border-start border-4 border-danger">
                <div class="card-body p-3">
                    <span class="text-muted text-uppercase font-size-12 fw-semibold">Sisa Belum Cair</span>
                    <h3 class="fw-bold text-danger my-2 font-size-20">Rp {{ number_format($sisaBelumCair, 0, ',', '.') }}</h3>
                    <!-- <small class="text-muted"><i class="ri-time-line me-1"></i>Kewajiban beasiswa tertunda</small> -->
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 mb-0 shadow-sm border-start border-4 border-info">
                <div class="card-body p-3">
                    <span class="text-muted text-uppercase font-size-12 fw-semibold">Rincian Pencairan</span>
                    <div class="mt-2 font-size-12">
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="ri-bank-card-line text-primary me-1"></i>Transfer (SPP/UTAP):</span>
                            <strong class="text-dark">Rp {{ number_format($realisasiTransfer, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="ri-hand-coin-line text-success me-1"></i>Tunai (Living Cost):</span>
                            <strong class="text-dark">Rp {{ number_format($realisasiTunai, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Santri Penerima --}}
    <div class="card shadow-sm">
        <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0 fw-bold text-dark"><i class="ri-list-check-2 me-1 text-primary"></i> Daftar Pencairan Beasiswa Santri</h5>
                <!-- <small class="text-muted">Menampilkan seluruh berkas santri yang telah disetujui (ACC) dan dalam tahap pencairan.</small> -->
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="reloadTable();"><i class="ri-refresh-line me-1"></i>Refresh</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable-pencairan" class="table table-bordered table-hover align-middle w-100 font-size-13">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 35px;" class="text-center">No</th>
                            <th>Santri & Asrama</th>
                            <th>Periode</th>
                            <th>Hak Bantuan</th>
                            <th style="width: 170px;">Telah Cair</th>
                            <th>Sisa Dana</th>
                            <th class="text-center" style="width: 150px;">Status</th>
                            <th class="text-center" style="width: 110px;">Aksi</th>
                        </tr>
                        {{-- Baris Filter per Kolom --}}
                        <tr class="table-filter-row bg-white">
                            <th class="text-center p-1"></th>
                            <th class="p-1">
                                <input type="text" id="filter_santri" class="form-control form-control-sm font-size-12" placeholder="Cari santri / NIS / asrama...">
                            </th>
                            <th class="p-1">
                                <select id="filter_periode" class="form-select form-select-sm font-size-12">
                                    <option value="">Semua Periode</option>
                                    @foreach($periodes as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_periode }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th class="p-1"></th>
                            <th class="p-1"></th>
                            <th class="p-1"></th>
                            <th class="p-1">
                                <select id="filter_status" class="form-select form-select-sm font-size-12">
                                    <option value="">Semua Status</option>
                                    <option value="siap">Siap Dicairkan (0%)</option>
                                    <option value="sebagian">Pencairan Sebagian</option>
                                    <option value="tuntas">Tuntas Dicairkan (100%)</option>
                                </select>
                            </th>
                            <th class="text-center p-1">
                                <button type="button" id="btn_reset_filter" class="btn btn-sm btn-outline-danger py-1 px-2 font-size-11 w-100" title="Reset Semua Filter Kolom">
                                    <i class="ri-refresh-line me-1"></i>Reset
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Pencairan Dana --}}
    <div class="modal fade" id="modalCatatPencairan" tabindex="-1" role="dialog" aria-labelledby="modalCatatLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="formPencairan" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="pencairan_pengajuan_id" name="pengajuan_id">
                    <div class="modal-header">
                        <h5 class="modal-title font-size-16" id="modalCatatLabel">Pencairan Dana Beasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        {{-- Identitas Santri --}}
                        <table class="table table-sm table-bordered align-middle mb-3 font-size-13">
                            <tbody>
                                <tr>
                                    <th class="bg-light" style="width: 18%;">Nama Santri</th>
                                    <td class="fw-bold text-dark" style="width: 32%;" id="modal_santri_nama">-</td>
                                    <th class="bg-light" style="width: 18%;">NIS</th>
                                    <td class="fw-semibold text-dark" style="width: 32%;" id="modal_santri_nis">-</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Kamar</th>
                                    <td id="modal_santri_kamar">-</td>
                                    <th class="bg-light">Periode</th>
                                    <td id="modal_santri_periode">-</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered align-middle mb-0 font-size-12">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Pilih</th>
                                        <th>Komponen Biaya</th>
                                        <th>Hak Plafon</th>
                                        <th>Sudah Dicairkan</th>
                                        <th>Sisa Plafon</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_komponen_info"></tbody>
                            </table>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label font-size-13 fw-semibold">Komponen yang Dicairkan <span class="text-danger">*</span></label>
                                <input type="text" id="display_kategori" class="form-control fw-bold text-dark" readonly style="background-color: #f8f9fa;" placeholder="Pilih radio button pada tabel di atas">
                                <input type="hidden" name="kategori_bantuan" id="input_kategori" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-size-13 fw-semibold">Nominal Pencairan (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text font-size-13">Rp</span>
                                    <input type="text" class="form-control fw-bold text-dark" id="display_nominal" placeholder="0" required autocomplete="off">
                                    <input type="hidden" name="nominal" id="input_nominal" required>
                                </div>
                                <small class="text-muted" id="nominal_help">Maksimal sesuai sisa plafon.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label font-size-13 fw-semibold">Metode Pencairan <span class="text-danger">*</span></label>
                                <select class="form-select" name="metode_pencairan" id="input_metode" required>
                                    <option value="tunai">Tunai Langsung (Tanda Tangan Santri)</option>
                                    <option value="transfer">Transfer Bank (Struk/Mutasi Rekening)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-size-13 fw-semibold">Tanggal Pencairan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_cair" id="input_tanggal" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label font-size-13 fw-semibold">Catatan Petugas (Opsional)</label>
                                <textarea class="form-control" name="catatan" id="input_catatan" rows="2" placeholder="Catatan tambahan penyerahan dana..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-sm" id="btnSubmitPencairan">
                            <i class="ri-save-line me-1"></i>Simpan Pencairan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    var tablePencairan;
    var cachedKomponen = [];

    $(document).ready(function() {
        tablePencairan = $('#datatable-pencairan').DataTable({
            processing: true,
            serverSide: true,
            orderCellsTop: true,
            ajax: {
                url: "{{ route('pencairan.data') }}",
                data: function(d) {
                    d.filter_santri = $('#filter_santri').val();
                    d.filter_periode = $('#filter_periode').val();
                    d.filter_status = $('#filter_status').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'santri_info', name: 'santri.nama_lengkap' },
                { data: 'periode_info', name: 'periode.tahun_masehi' },
                { data: 'hak_bantuan', name: 'hak_bantuan', orderable: false, searchable: false },
                { data: 'tercairkan', name: 'tercairkan', orderable: false, searchable: false },
                { data: 'sisa_dana', name: 'sisa_dana', orderable: false, searchable: false },
                { data: 'status_badge', name: 'status_badge', className: 'text-center', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                paginate: { previous: "<i class='ri-arrow-left-s-line'>", next: "<i class='ri-arrow-right-s-line'>" }
            }
        });

        // Event handler filter per kolom
        var searchTimer;
        $('#filter_santri').on('keyup change', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                tablePencairan.ajax.reload();
            }, 350);
        });

        $('#filter_periode, #filter_status').on('change', function() {
            tablePencairan.ajax.reload();
        });

        $('#btn_reset_filter').on('click', function() {
            $('#filter_santri').val('');
            $('#filter_periode').val('');
            $('#filter_status').val('');
            tablePencairan.ajax.reload();
        });

        // Buka modal pencairan
        $(document).on('click', '.btn-catat', function() {
            var pengajuanId = $(this).data('id');
            $('#formPencairan')[0].reset();
            $('#pencairan_pengajuan_id').val(pengajuanId);
            $('#tbody_komponen_info').html('<tr><td colspan="5" class="text-center py-2 text-muted">Memuat data plafon...</td></tr>');
            $('#modalCatatPencairan').modal('show');

            $.get("{{ url('/pencairan/info') }}/" + pengajuanId, function(res) {
                $('#modal_santri_nama').text(res.pengajuan.nama);
                $('#modal_santri_nis').text(res.pengajuan.nis);
                $('#modal_santri_kamar').text(res.pengajuan.kamar);
                $('#modal_santri_periode').text(res.pengajuan.periode);
                cachedKomponen = res.komponen;

                var rowsHtml = '';
                var selectOptions = '<option value="">-- Pilih Komponen --</option>';

                if (res.komponen.length === 0) {
                    rowsHtml = '<tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada komponen bantuan yang diajukan untuk santri ini.</td></tr>';
                } else {
                    res.komponen.forEach(function(item) {
                        var sisaFmt = 'Rp ' + Number(item.sisa).toLocaleString('id-ID');
                        var cairFmt = 'Rp ' + Number(item.cair).toLocaleString('id-ID');
                        var hakFmt = 'Rp ' + Number(item.hak).toLocaleString('id-ID');
                        var disabled = item.sisa <= 0 ? 'disabled' : '';
                        var badge = item.sisa <= 0 ? '<span class="badge bg-secondary font-size-10">Lunas</span>' : '<span class="badge bg-success font-size-10">Tersedia</span>';

                        rowsHtml += '<tr>' +
                            '<td class="text-center"><input type="radio" name="radio_komponen" value="' + item.key + '" ' + disabled + ' class="select-komponen-radio"></td>' +
                            '<td><strong>' + item.label + '</strong></td>' +
                            '<td class="text-end">' + hakFmt + '</td>' +
                            '<td class="text-end text-success">' + cairFmt + '</td>' +
                            '<td class="text-end fw-bold text-dark">' + sisaFmt + ' ' + badge + '</td>' +
                            '</tr>';

                        selectOptions += '<option value="' + item.key + '" ' + disabled + '>' + item.label + (item.sisa <= 0 ? ' (Lunas)' : '') + '</option>';
                    });
                }

                $('#tbody_komponen_info').html(rowsHtml);

                // Otomatis pilih komponen pertama yang masih memiliki sisa plafon
                var firstAvailable = res.komponen.find(function(item) { return item.sisa > 0; });
                if (firstAvailable) {
                    $('input[name="radio_komponen"][value="' + firstAvailable.key + '"]').prop('checked', true).trigger('change');
                } else {
                    $('#display_kategori').val('');
                    $('#input_kategori').val('');
                    $('#display_nominal').val('');
                    $('#input_nominal').val('');
                    $('#nominal_help').html('<span class="text-muted">Tidak ada komponen yang tersedia untuk dicairkan.</span>');
                }
            });
        });

        // Format angka ribuan dengan titik
        function formatRupiahInput(val) {
            if (val === undefined || val === null || val === '') return '';
            var number_string = val.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        }

        function parseRupiahInput(str) {
            if (!str) return 0;
            return parseInt(str.toString().replace(/\./g, ''), 10) || 0;
        }

        // Event saat radio komponen dipilih di tabel
        $(document).on('change', '.select-komponen-radio', function() {
            var selectedKey = $(this).val();
            var target = cachedKomponen.find(function(k) { return k.key === selectedKey; });
            if (target) {
                // Set Komponen yang Dicairkan (Readonly)
                $('#display_kategori').val(target.label);
                $('#input_kategori').val(target.key);

                // Set Nominal Pencairan dengan format titik ribuan
                var sisaVal = target.sisa;
                $('#input_nominal').val(sisaVal);
                $('#display_nominal').val(formatRupiahInput(sisaVal));

                // Set Metode Default
                $('#input_metode').val(target.metode);
                $('#nominal_help').html('Sisa plafon komponen ini: <strong class="text-success">Rp ' + Number(target.sisa).toLocaleString('id-ID') + '</strong>');
            }
        });

        // Event input nominal dengan pemisah titik ribuan
        $('#display_nominal').on('input', function() {
            var formatted = formatRupiahInput($(this).val());
            $(this).val(formatted);
            var raw = parseRupiahInput(formatted);
            $('#input_nominal').val(raw);
        });

        // Submit form pencairan
        $('#formPencairan').on('submit', function(e) {
            e.preventDefault();
            var rawNominal = parseRupiahInput($('#display_nominal').val());
            $('#input_nominal').val(rawNominal);

            if (!rawNominal || rawNominal <= 0) {
                Swal.fire('Perhatian', 'Silakan masukkan nominal pencairan yang valid.', 'warning');
                return;
            }

            var formData = new FormData(this);
            formData.set('nominal', rawNominal);

            var btn = $('#btnSubmitPencairan');
            btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i>Menyimpan...');

            $.ajax({
                url: "{{ route('pencairan.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="ri-save-line me-1"></i>Simpan Pencairan');
                    if (res.success) {
                        $('#modalCatatPencairan').modal('hide');
                        Swal.fire({
                            title: 'Pencairan Berhasil!',
                            text: res.message,
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: '<i class="ri-printer-line me-1"></i>Cetak Kuitansi',
                            cancelButtonText: 'Tutup'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.open("{{ url('/pencairan/kuitansi') }}/" + res.pencairan_id, '_blank');
                            }
                        });
                        tablePencairan.ajax.reload(null, false);
                    }
                },
                error: function(err) {
                    btn.prop('disabled', false).html('<i class="ri-save-line me-1"></i>Simpan Pencairan');
                    var msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Terjadi kesalahan sistem.';
                    Swal.fire('Gagal!', msg, 'error');
                }
            });
        });
    });

    function reloadTable() {
        tablePencairan.ajax.reload(null, false);
    }
</script>
@endpush
