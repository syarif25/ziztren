<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Pencairan Beasiswa - {{ $pengajuan->nis }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 18mm 12mm 18mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            line-height: 1.35;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* === KOP SURAT === */
        table.header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        table.header-table td {
            vertical-align: middle;
        }
        .header-logo {
            width: 95px;
            text-align: left;
        }
        .header-logo img {
            width: 85px;
            height: auto;
        }
        .header-title {
            text-align: left;
            padding-left: 14px;
        }
        .header-title h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            color: #000;
            line-height: 1.25;
        }
        .header-title h3 {
            margin: 3px 0 0;
            font-size: 10.5pt;
            font-weight: bold;
            color: #000;
        }
        .header-title p {
            margin: 3px 0 0;
            font-size: 8.5pt;
            color: #333;
        }

        .header-divider {
            border: none;
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            height: 3px;
            margin: 4px 0 10px;
        }

        /* === JUDUL SURAT === */
        .doc-title-block {
            text-align: center;
            margin-bottom: 14px;
        }
        .doc-title-block h4 {
            margin: 0;
            font-size: 11.5pt;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        .doc-title-block .doc-no {
            margin-top: 3px;
            font-size: 8.5pt;
            color: #222;
        }

        /* === FORM IDENTITAS === */
        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.info-table td {
            padding: 2.5px 2px;
            vertical-align: top;
            font-size: 9pt;
        }

        /* === TABEL TRANSAKSI RESMI === */
        table.grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 14px;
        }
        table.grid-table th {
            background-color: #f2f2f2;
            color: #000;
            font-weight: bold;
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 8.5pt;
        }
        table.grid-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 8.5pt;
            vertical-align: middle;
        }

        /* === TANDA TANGAN === */
        table.signature-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        table.signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 9pt;
        }
        .sig-space {
            height: 50px;
        }
    </style>
</head>
<body>
    @php
        $logoP2s3Path = public_path('assets/images/p2s3.png');
        $logoBase64 = '';
        if (file_exists($logoP2s3Path)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoP2s3Path));
        }
    @endphp

    {{-- KOP SURAT STANDAR RESMI --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}">
                @endif
            </td>
            <td class="header-title">
                <h2>LEMBAGA AMIL ZAKAT INFAK SHADAQAH IBRAHIMY (LAZIMY)</h2>
                <h3>PONDOK PESANTREN SALAFIYAH SYAFI'IYAH SUKOREJO</h3>
                <p>Sumberejo, Banyuputih, Situbondo, Jawa Timur 68374</p>
            </td>
        </tr>
    </table>

    <div class="header-divider"></div>

    {{-- JUDUL DOKUMEN --}}
    <div class="doc-title-block">
        <h4>REKAPITULASI PENCAIRAN BEASISWA</h4>
        <div class="doc-no">Nomor : {{ sprintf('%04d', $pengajuan->id) }}/........./{{ date('Y') }}</div>
    </div>

    {{-- IDENTITAS SANTRI --}}
    <table class="info-table">
        <tr>
            <td style="width: 130px;">Nama Santri</td>
            <td style="width: 10px;">:</td>
            <td style="font-weight: bold; width: 230px;">{{ $pengajuan->santri->nama_lengkap ?? '-' }}</td>
            <td style="width: 120px;">Lembaga / Sekolah</td>
            <td style="width: 10px;">:</td>
            <td>{{ $pengajuan->lembaga1->nama_lembaga ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nomor Induk (NIS)</td>
            <td>:</td>
            <td>{{ $pengajuan->nis }}</td>
            <td>Kamar</td>
            <td>:</td>
            <td>{{ $pengajuan->santri?->kamar ? 'Kamar ' . $pengajuan->santri->kamar : '-' }}</td>
        </tr>
        <tr>
            <td>Periode Beasiswa</td>
            <td>:</td>
            <td>{{ $pengajuan->periode->nama_periode ?? '-' }}</td>
            <td>Nama Wali</td>
            <td>:</td>
            <td>{{ $pengajuan->santri?->nama_wali ?? '-' }} ({{ $pengajuan->santri?->no_hp_wali ?? '-' }})</td>
        </tr>
        <tr>
            <td>Total Beasiswa</td>
            <td>:</td>
            <td style="font-weight: bold;">Rp {{ number_format($pengajuan->total_bantuan, 0, ',', '.') }}</td>
            <td>Status Realisasi</td>
            <td>:</td>
            <td>{{ $pengajuan->sisa_pencairan == 0 ? 'Tuntas 100%' : 'Pencairan Sebagian' }}</td>
        </tr>
    </table>

    {{-- TABEL RIWAYAT TRANSAKSI PENCAIRAN --}}
    <table class="grid-table">
        <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No</th>
                <th style="width: 75px; text-align: center;">Tanggal</th>
                <th>Komponen Bantuan</th>
                <th style="width: 70px; text-align: center;">Metode</th>
                <th style="width: 100px; text-align: right;">Nominal</th>
                <th>Catatan / Keterangan</th>
                <th style="width: 80px; text-align: center;">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuan->pencairans as $idx => $p)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($p->tanggal_cair)->format('d/m/Y') }}</td>
                    <td>{{ $p->kategori_label }}</td>
                    <td style="text-align: center;">{{ strtoupper($p->metode_pencairan) }}</td>
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                    <td>{{ $p->catatan ?: ($p->tahap_ke ?: '-') }}</td>
                    <td style="text-align: center;">{{ $p->user->nama_lengkap ?? $p->user->username ?? 'Petugas' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 10px;">
                        Belum ada catatan transaksi pencairan.
                    </td>
                </tr>
            @endforelse

            @if(count($pengajuan->pencairans) > 0)
                <tr style="font-weight: bold; background-color: #f9f9f9;">
                    <td colspan="4" style="text-align: right; padding-right: 8px;">TOTAL TELAH DICAIRKAN</td>
                    <td style="text-align: right;">Rp {{ number_format($pengajuan->total_dicairkan, 0, ',', '.') }}</td>
                    <td colspan="2" style="font-size: 8pt; color: #444;">
                        Sisa Plafon: Rp {{ number_format($pengajuan->sisa_pencairan, 0, ',', '.') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- TANDA TANGAN DUA PIHAK --}}
    <table class="signature-table">
        <tr>
            <td>
                Penerima / Santri,
                <div class="sig-space"></div>
                <strong><u>{{ $pengajuan->santri->nama_lengkap ?? '..................................................' }}</u></strong><br>
                <span>NIS: {{ $pengajuan->nis }}</span>
            </td>
            <td>
                Sukorejo, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>
                Bendahara LAZIMY P2S3,
                <div class="sig-space"></div>
                <strong><u>{{ auth()->user()->nama_lengkap ?? auth()->user()->username ?? 'Bendahara' }}</u></strong><br>
                <span>LAZIMY P2S3 Sukorejo</span>
            </td>
        </tr>
    </table>
</body>
</html>
