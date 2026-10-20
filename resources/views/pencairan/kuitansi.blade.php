<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuitansi Tanda Terima Beasiswa - {{ $pencairan->id }}</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 8mm 14mm 8mm 14mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5pt;
            color: #222;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #005a36;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .title-receipt {
            text-align: center;
            font-size: 12.5pt;
            font-weight: bold;
            color: #005a36;
            letter-spacing: 0.5px;
            margin: 2px 0 1px 0;
            text-decoration: underline;
        }
        .receipt-number {
            text-align: center;
            font-size: 8.5pt;
            color: #555;
            margin-bottom: 8px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .content-table td {
            padding: 3px 2px;
            vertical-align: top;
            font-size: 9.5pt;
        }
        .nominal-box {
            background-color: #f0f7f3;
            border: 1px dashed #005a36;
            padding: 4px 12px;
            font-size: 12pt;
            font-weight: bold;
            color: #005a36;
            display: inline-block;
        }
        .terbilang-box {
            font-style: italic;
            background-color: #fcfcfc;
            border-left: 2.5px solid #005a36;
            padding: 2px 8px;
            margin-top: 3px;
            color: #444;
            font-size: 8.5pt;
        }
        .signature-table {
            width: 100%;
            margin-top: 8px;
            page-break-inside: avoid;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 9pt;
        }
        .sig-space {
            height: 42px;
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

        // Fungsi terbilang sederhana
        function terbilangIndonesia($angka) {
            $angka = abs($angka);
            $baca = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
            $terbilang = "";
            if ($angka < 12) {
                $terbilang = " " . $baca[$angka];
            } elseif ($angka < 20) {
                $terbilang = terbilangIndonesia($angka - 10) . " Belas";
            } elseif ($angka < 100) {
                $terbilang = terbilangIndonesia(floor($angka / 10)) . " Puluh" . terbilangIndonesia($angka % 10);
            } elseif ($angka < 200) {
                $terbilang = " Seratus" . terbilangIndonesia($angka - 100);
            } elseif ($angka < 1000) {
                $terbilang = terbilangIndonesia(floor($angka / 100)) . " Ratus" . terbilangIndonesia($angka % 100);
            } elseif ($angka < 2000) {
                $terbilang = " Seribu" . terbilangIndonesia($angka - 1000);
            } elseif ($angka < 1000000) {
                $terbilang = terbilangIndonesia(floor($angka / 1000)) . " Ribu" . terbilangIndonesia($angka % 1000);
            } elseif ($angka < 1000000000) {
                $terbilang = terbilangIndonesia(floor($angka / 1000000)) . " Juta" . terbilangIndonesia($angka % 1000000);
            }
            return trim($terbilang);
        }
        $terbilangKata = terbilangIndonesia((int)$pencairan->nominal) . ' Rupiah';
    @endphp

    {{-- KOP KUITANSI --}}
    <table class="header-table">
        <tr>
            <td style="width: 58px; vertical-align: middle;">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" style="width: 46px; height: auto;">
                @endif
            </td>
            <td style="vertical-align: middle;">
                <div style="font-size: 11.5pt; font-weight: bold; color: #005a36;">LEMBAGA AMIL ZAKAT INFAK SHADAQAH IBRAHIMY (LAZIMY)</div>
                <div style="font-size: 9pt; font-weight: bold; color: #333;">PONDOK PESANTREN SALAFIYAH SYAFI'IYAH SUKOREJO</div>
                <div style="font-size: 7.5pt; color: #555;">Sumberejo, Banyuputih, Situbondo, Jawa Timur 68374</div>
            </td>
            <td style="width: 140px; text-align: right; vertical-align: middle;">
                <div style="font-size: 7.5pt; color: #555;">Formulir Keuangan</div>
                <div style="font-size: 9pt; font-weight: bold; color: #005a36;">BUKTI KAS KELUAR</div>
            </td>
        </tr>
    </table>

    <div class="title-receipt">KUITANSI TANDA TERIMA BEASISWA</div>
    <div class="receipt-number">Nomor: KW/LAZIMY/{{ date('Y', strtotime($pencairan->tanggal_cair)) }}/{{ sprintf('%05d', $pencairan->id) }}</div>

    <table class="content-table">
        <tr>
            <td style="width: 22%;">Telah Diterima Dari</td>
            <td style="width: 2%;">:</td>
            <td style="width: 76%;"><strong>Bendahara LAZIMY P2S3 Sukorejo</strong></td>
        </tr>
        <tr>
            <td>Uang Sejumlah</td>
            <td>:</td>
            <td>
                <div class="nominal-box">Rp {{ number_format($pencairan->nominal, 0, ',', '.') }},-</div>
                <div class="terbilang-box"><em># {{ $terbilangKata }} #</em></div>
            </td>
        </tr>
        <tr>
            <td>Untuk Penyaluran</td>
            <td>:</td>
            <td>
                <strong>Bantuan Beasiswa Santri — {{ $pencairan->kategori_label }}</strong>
                <div style="font-size: 8.5pt; color: #555; margin-top: 1px;">
                    Metode: {{ strtoupper($pencairan->metode_pencairan) }}
                    @if($pencairan->catatan) &bull; Catatan: {{ $pencairan->catatan }} @endif
                </div>
            </td>
        </tr>
        <tr>
            <td>Atas Nama Santri</td>
            <td>:</td>
            <td>
                <strong>{{ $pencairan->pengajuan->santri->nama_lengkap ?? '-' }}</strong> 
                (NIS: {{ $pencairan->pengajuan->nis }}) 
                @if($pencairan->pengajuan->santri?->kamar)
                    &bull; Kamar {{ $pencairan->pengajuan->santri->kamar }}
                @endif
                @if($pencairan->pengajuan->lembaga1?->nama_lembaga)
                    &bull; {{ $pencairan->pengajuan->lembaga1->nama_lembaga }}
                @endif
            </td>
        </tr>
    </table>

    {{-- TANDA TANGAN PENERIMA & BENDAHARA --}}
    <table class="signature-table">
        <tr>
            <td>
                Penerima / Santri,<br>
                <div style="font-size: 8pt; color: #666;">(Yang Menerima Uang)</div>
                <div class="sig-space"></div>
                <strong><u>{{ $pencairan->nama_penerima ?: ($pencairan->pengajuan->santri->nama_lengkap ?? '................................') }}</u></strong>
            </td>
            <td>
                Sukorejo, {{ \Carbon\Carbon::parse($pencairan->tanggal_cair)->locale('id')->translatedFormat('d F Y') }}<br>
                Bendahara / Petugas Penyalur,<br>
                <div style="font-size: 8pt; color: #666;">LAZIMY P2S3</div>
                <div class="sig-space"></div>
                <strong><u>{{ $pencairan->user->nama_lengkap ?? $pencairan->user->username ?? 'Petugas LAZIMY' }}</u></strong>
            </td>
        </tr>
    </table>
</body>
</html>
