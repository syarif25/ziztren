<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekapitulasi Beasiswa ZISTREN</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 15mm 12mm 15mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            line-height: 1.3;
            color: #111;
            margin: 0;
            padding: 0;
        }

        /* === KOP SURAT === */
        table.header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        table.header-table td {
            vertical-align: middle;
        }
        .header-logo {
            width: 170px;
            text-align: left;
        }
        .header-logo img {
            height: 55px;
            width: auto;
            max-width: 170px;
        }
        .header-title {
            text-align: center;
            padding-right: 20px;
        }
        .header-title h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-title h3 {
            margin: 3px 0 0;
            font-size: 10.5pt;
            font-weight: normal;
        }

        .header-divider {
            border: none;
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            height: 3px;
            margin: 4px 0 10px;
        }

        /* === FILTER INFO === */
        table.filter-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
        }
        table.filter-info-table td {
            padding: 4px 8px;
            font-size: 8pt;
        }

        /* === DATA TABLE === */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #475569;
            padding: 4.5px 5px;
        }
        table.data-table th {
            background-color: #e2e8f0;
            font-size: 8pt;
            font-weight: bold;
            text-align: center;
        }
        table.data-table td {
            font-size: 8pt;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }

        /* === SIGNATURE === */
        table.sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table.sig-table td {
            width: 33.3%;
            vertical-align: top;
            text-align: center;
            font-size: 8.5pt;
        }
    </style>
</head>
<body>

    {{-- ====== KOP SURAT ====== --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if(file_exists(public_path('assets/images/p2s3.png')))
                    <img src="{{ public_path('assets/images/p2s3.png') }}" alt="Logo P2S3">
                @else
                    <div style="font-weight: bold; font-size: 11pt;">P2S3</div>
                @endif
            </td>
            <td class="header-title">
                <h2>LAPORAN REKAPITULASI BANTUAN BEASISWA ZISTREN</h2>
                <h3>PP. SALAFIYAH SYAFI'IYAH SUKOREJO SITUBONDO</h3>
            </td>
        </tr>
    </table>

    <div class="header-divider"></div>

    {{-- ====== FILTER METADATA ====== --}}
    <table class="filter-info-table">
        <tr>
            <td style="width: 25%;"><strong>Periode:</strong> {{ $filterInfo['periode'] }}</td>
            <td style="width: 25%;"><strong>Status:</strong> {{ $filterInfo['status'] }}</td>
            <td style="width: 25%;"><strong>Lembaga:</strong> {{ $filterInfo['lembaga'] }}</td>
            <td style="width: 25%;">
                <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB
            </td>
        </tr>
    </table>

    {{-- ====== TABEL DATA REKAP ====== --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 75px;">NIS</th>
                <th>Nama Santri</th>
                <th style="width: 30px;">L/P</th>
                <th>Lembaga / Sekolah</th>
                <th style="width: 80px;">Periode</th>
                <th style="width: 70px;">UTAP (Rp)</th>
                <th style="width: 75px;">SPP (Rp)</th>
                <th style="width: 70px;">Living (Rp)</th>
                <th style="width: 65px;">Lainnya (Rp)</th>
                <th style="width: 80px;">Total (Rp)</th>
                <th style="width: 55px;">Status</th>
                <th style="width: 70px;">Tgl Cair</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $idx => $p)
                @php
                    $spp = ($p->spp_gasal ?? 0) + ($p->spp_genap ?? 0);
                    $subtotal = ($p->jumlah_utap ?? 0) + $spp + ($p->living_cost ?? 0) + ($p->biaya_lain ?? 0);
                    $lembagas = [];
                    if ($p->lembaga1) $lembagas[] = $p->lembaga1->singkatan ?: $p->lembaga1->nama_lembaga;
                    if ($p->lembaga2) $lembagas[] = $p->lembaga2->singkatan ?: $p->lembaga2->nama_lembaga;
                    $lembagaText = count($lembagas) > 0 ? implode(' / ', $lembagas) : '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $p->nis }}</td>
                    <td><strong>{{ $p->santri->nama_lengkap ?? '-' }}</strong></td>
                    <td class="text-center">{{ $p->santri->jenis_kelamin ?? '-' }}</td>
                    <td>{{ $lembagaText }}</td>
                    <td class="text-center">{{ $p->periode ? 'Gel ' . $p->periode->gelombang . ' - ' . $p->periode->tahun_masehi : '-' }}</td>
                    <td class="text-right">{{ number_format($p->jumlah_utap ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($spp, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($p->living_cost ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($p->biaya_lain ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right fw-bold">{{ number_format($subtotal, 0, ',', '.') }}</td>
                    <td class="text-center">{{ ucfirst($p->status_pengajuan) }}</td>
                    <td class="text-center">{{ $p->tanggal_bantuan ? \Carbon\Carbon::parse($p->tanggal_bantuan)->format('d/m/Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center" style="padding: 15px; color: #64748b;">
                        <em>Tidak ada data pengajuan beasiswa yang sesuai dengan kriteria filter.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($pengajuans) > 0)
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="6" class="text-center">TOTAL KESELURUHAN ({{ $totals['santri'] }} SANTRI)</td>
                <td class="text-right">{{ number_format($totals['utap'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totals['spp'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totals['living'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totals['lain'], 0, ',', '.') }}</td>
                <td class="text-right fw-bold">{{ number_format($totals['grand'], 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- ====== TANDA TANGAN ====== --}}
    <table class="sig-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Ketua Lembaga / ZISTREN</strong>
                <br><br><br><br>
                ( .................................................. )
            </td>
            <td></td>
            <td>
                Sukorejo, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>
                <strong>Bendahara / Petugas Administrasi</strong>
                <br><br><br><br>
                ( <u>{{ auth()->user()->username ?? 'Petugas' }}</u> )
            </td>
        </tr>
    </table>

</body>
</html>
