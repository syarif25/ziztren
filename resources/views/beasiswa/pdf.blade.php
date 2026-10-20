<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Form Pengajuan Beasiswa - {{ $pengajuan->nis }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 10mm 15mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            line-height: 1.35;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        /* === KOP SURAT === */
        table.header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        table.header-table td {
            vertical-align: middle;
        }
        .header-logo {
            width: 190px;
            text-align: left;
        }
        .header-logo img {
            height: 65px;
            width: auto;
            max-width: 190px;
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
            color: #111;
        }
        .header-title h3 {
            margin: 4px 0 0;
            font-size: 11pt;
            font-weight: normal;
            color: #222;
        }

        .header-divider {
            border: none;
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            height: 3px;
            margin: 4px 0 10px;
        }

        .section-divider {
            border: none;
            border-top: 1px solid #ccc;
            margin: 8px 0;
        }

        .opening-text {
            margin: 0 0 8px;
            font-size: 9.5pt;
        }

        /* === MAIN FORM TABLE === */
        table.form-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.form-table > tbody > tr > td {
            vertical-align: top;
            padding: 3px 0;
        }
        td.label-col {
            width: 140px;
            color: #333;
            white-space: nowrap;
        }
        td.colon-col {
            width: 12px;
            text-align: center;
        }
        td.val-col {
            color: #000;
        }

        /* === SUB TABLES === */
        table.inner-grid {
            width: 100%;
            border-collapse: collapse;
        }
        table.inner-grid td {
            padding: 0;
            vertical-align: top;
        }

        /* === SECTION BLOCKS === */
        .section-header {
            font-weight: bold;
            font-size: 9.5pt;
            color: #111;
            margin: 8px 0 4px;
        }

        /* === DOC & BANTUAN TABLE === */
        table.doc-list-table {
            width: 100%;
            border-collapse: collapse;
            margin-left: 10px;
            margin-top: 3px;
        }
        table.doc-list-table td {
            padding: 2.5px 10px 2.5px 0;
            vertical-align: top;
            font-size: 9pt;
        }

        /* === SIGNATURE === */
        table.signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
        }
        table.signature-table td {
            width: 50%;
            vertical-align: top;
            font-size: 9.5pt;
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
                <h2>PERMOHONAN BEASISWA ZISTREN</h2>
                <h3>PP. SALAFIYAH SYAFI'IYAH SUKOREJO SITUBONDO</h3>
            </td>
        </tr>
    </table>

    <div class="header-divider"></div>

    <p class="opening-text">Yang bertandatangan di bawah ini, saya:</p>

    @php
        // Jenis Kelamin
        $jkRaw = $pengajuan->santri->jenis_kelamin ?? '';
        $jkFormatted = $jkRaw === 'L' ? 'Laki-laki' : ($jkRaw === 'P' ? 'Perempuan' : ($jkRaw ?: '-'));

        // Tempat, Tanggal Lahir
        $tempatLahir = ucwords(strtolower(trim($pengajuan->santri->tempat_lahir ?? '')));
        $tglLahir = !empty($pengajuan->santri->tanggal_lahir) ? \Carbon\Carbon::parse($pengajuan->santri->tanggal_lahir)->locale('id')->translatedFormat('d F Y') : '';
        $ttlFormatted = '-';
        if ($tempatLahir && $tglLahir) {
            $ttlFormatted = $tempatLahir . ', ' . $tglLahir;
        } elseif ($tempatLahir) {
            $ttlFormatted = $tempatLahir;
        } elseif ($tglLahir) {
            $ttlFormatted = $tglLahir;
        }

        // Anak Ke
        $anakKe = $pengajuan->santri->anak_ke ?? '';
        $jmlSaudara = $pengajuan->santri->jumlah_saudara ?? '';
        $anakKeFormatted = '-';
        if ($anakKe && $jmlSaudara) {
            $anakKeFormatted = $anakKe . ' dari ' . $jmlSaudara . ' bersaudara';
        } elseif ($anakKe) {
            $anakKeFormatted = 'Anak ke-' . $anakKe;
        }

        // Alamat lengkap
        $alamatArray = array_filter([
            $pengajuan->santri->alamat_lengkap ?? null,
            !empty($pengajuan->santri->desa->name) ? 'Desa/Kel. ' . ucwords(strtolower($pengajuan->santri->desa->name)) : null,
            !empty($pengajuan->santri->kecamatan->name) ? 'Kec. ' . ucwords(strtolower($pengajuan->santri->kecamatan->name)) : null,
            !empty($pengajuan->santri->kabupaten->name) ? ucwords(strtolower($pengajuan->santri->kabupaten->name)) : null,
            !empty($pengajuan->santri->provinsi->name) ? 'Prov. ' . ucwords(strtolower($pengajuan->santri->provinsi->name)) : null,
        ]);
        $alamatFormatted = count($alamatArray) > 0 ? implode(', ', $alamatArray) : '-';

        // Bantuan Biaya Studi Terpilih
        $bantuanTerpilih = [];
        if (($pengajuan->jumlah_utap ?? 0) > 0) {
            $bantuanTerpilih[] = ['nama' => 'UTAP', 'nominal' => $pengajuan->jumlah_utap];
        }
        $sppTotal = ($pengajuan->spp_gasal ?? 0) + ($pengajuan->spp_genap ?? 0);
        if ($sppTotal > 0) {
            $bantuanTerpilih[] = ['nama' => 'SPP Smt. Gasal/Genap', 'nominal' => $sppTotal];
        }
        if (($pengajuan->living_cost ?? 0) > 0) {
            $bantuanTerpilih[] = ['nama' => 'Living Kost', 'nominal' => $pengajuan->living_cost];
        }
        if (($pengajuan->biaya_lain ?? 0) > 0) {
            $bantuanTerpilih[] = ['nama' => 'Biaya Ujian/Lain', 'nominal' => $pengajuan->biaya_lain];
        }

        // Dokumen Lampiran yang Terpilih / Diunggah
        $docsMaster = [
            'Surat Perjanjian'                    => 'Surat Perjanjian',
            'Kartu Keluarga'                      => 'Photo Copy Kartu Keluarga',
            'SK Tidak Mampu Desa'                 => 'SK Tidak Mampu dari Pemerintah Desa',
            'SK IKSASS'                           => 'SK Tidak Mampu dari IKSASS Rayon/Sub Rayon',
            'Buku Harian Santri (BHS)'            => 'Photo Copy Buku Harian Santri',
            'SK Aktif Asrama'                     => 'SK Aktif Asrama',
            'SK Aktif Madrasah, Sekolah, Qiroati' => 'SK Aktif Madrasah',
            'Raport Qiroati Amsilati'             => "Photo Copy Raport/Ijazah Qiro'ati/Amsilatuna",
            'Raport Madrasah'                     => 'Photo Copy Raport/Ijazah Madrasah',
            'Raport Sekolah PT'                   => 'Photo Copy Raport/Ijazah Sekolah/Perguruan Tinggi',
            'Fhoto Rumah'                         => 'Photo Rumah (Tampak Depan dan Dalam)',
            'Fhoto Bukti Rekening PLN'            => 'Photo Copy Bukti Pembayaran Rekening Listrik (PLN)',
        ];
        $uploadedKeys = $pengajuan->dokumen ? $pengajuan->dokumen->pluck('jenis_dokumen')->toArray() : [];
        $dokumenTerlampir = [];
        foreach ($docsMaster as $k => $label) {
            if (in_array($k, $uploadedKeys)) {
                $dokumenTerlampir[] = $label;
            }
        }
    @endphp

    {{-- ====== DATA DIRI ====== --}}
    <table class="form-table">
        <tr>
            <td class="label-col">No. Induk Santri (NIS)</td>
            <td class="colon-col">:</td>
            <td class="val-col"><strong>{{ $pengajuan->nis }}</strong></td>
        </tr>
        <tr>
            <td class="label-col">Nama Lengkap</td>
            <td class="colon-col">:</td>
            <td class="val-col"><strong>{{ $pengajuan->santri->nama_lengkap ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td class="label-col">Jenis Kelamin</td>
            <td class="colon-col">:</td>
            <td class="val-col">{{ $jkFormatted }}</td>
        </tr>
        <tr>
            <td class="label-col">Tempat, Tgl. Lahir</td>
            <td class="colon-col">:</td>
            <td class="val-col">{{ $ttlFormatted }}</td>
        </tr>
        <tr>
            <td class="label-col">Anak Ke</td>
            <td class="colon-col">:</td>
            <td class="val-col">{{ $anakKeFormatted }}</td>
        </tr>
        <tr>
            <td class="label-col">Asrama / Kamar</td>
            <td class="colon-col">:</td>
            <td class="val-col">{{ $pengajuan->santri->kamar ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col">Nama Wali</td>
            <td class="colon-col">:</td>
            <td class="val-col">{{ $pengajuan->santri->nama_wali ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col">Pekerjaan Wali</td>
            <td class="colon-col">:</td>
            <td class="val-col">{{ $pengajuan->santri->pekerjaan_wali ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col">No. HP / WA Wali</td>
            <td class="colon-col">:</td>
            <td class="val-col">{{ $pengajuan->santri->no_hp_wali ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col">Alamat Lengkap</td>
            <td class="colon-col">:</td>
            <td class="val-col">{{ $alamatFormatted }}</td>
        </tr>
        <tr>
            <td class="label-col">Pendidikan</td>
            <td class="colon-col">:</td>
            <td class="val-col">
                <div style="margin-bottom: 2px;">Tahun Pelajaran: <strong>{{ $pengajuan->periode->tahun_masehi ?? '-' }}</strong></div>
                @php
                    $hasQiroati = !empty($pengajuan->lembagaQiroati) || !empty($pengajuan->kelas_qiroati);
                    $hasMadrasah = !empty($pengajuan->lembaga1) || !empty($pengajuan->kelas_pendidikan_1);
                    $hasSekolah = !empty($pengajuan->lembaga2) || !empty($pengajuan->kelas_pendidikan_2);
                @endphp
                <table class="inner-grid" style="margin-top: 2px;">
                    @if($hasQiroati)
                    <tr>
                        <td style="width: 125px; padding: 1.5px 0; color: #444;">&bull; Qiro'ati / Amsilati</td>
                        <td style="width: 10px; padding: 1.5px 0;">:</td>
                        <td style="padding: 1.5px 0;">Kelas/Smt {{ $pengajuan->kelas_qiroati ?? '-' }} &mdash; {{ $pengajuan->lembagaQiroati->nama_lembaga ?? '-' }}</td>
                    </tr>
                    @endif
                    @if($hasMadrasah)
                    <tr>
                        <td style="width: 125px; padding: 1.5px 0; color: #444;">&bull; Madrasah</td>
                        <td style="width: 10px; padding: 1.5px 0;">:</td>
                        <td style="padding: 1.5px 0;">Kelas/Smt {{ $pengajuan->kelas_pendidikan_1 ?? '-' }} &mdash; {{ $pengajuan->lembaga1->nama_lembaga ?? '-' }}</td>
                    </tr>
                    @endif
                    @if($hasSekolah)
                    <tr>
                        <td style="width: 125px; padding: 1.5px 0; color: #444;">&bull; Sekolah / PT</td>
                        <td style="width: 10px; padding: 1.5px 0;">:</td>
                        <td style="padding: 1.5px 0;">Kelas/Smt {{ $pengajuan->kelas_pendidikan_2 ?? '-' }} &mdash; {{ $pengajuan->lembaga2->nama_lembaga ?? '-' }}</td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <hr class="section-divider">

    {{-- ====== JENIS BANTUAN TERPILIH ====== --}}
    <div class="section-header">Mengajukan permohonan bantuan biaya studi :</div>
    @if(count($bantuanTerpilih) > 0)
        @php
            $hurufBantuan = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
            $bIdx = 0;
        @endphp
        <table class="doc-list-table">
            @foreach(array_chunk($bantuanTerpilih, 2) as $bRow)
            <tr>
                @foreach($bRow as $bItem)
                <td style="width: 50%;">
                    <strong>{{ $hurufBantuan[$bIdx++] ?? '-' }}.</strong>&nbsp;
                    <strong>{{ $bItem['nama'] }}</strong>
                    <span style="color: #444;">(Rp {{ number_format($bItem['nominal'], 0, ',', '.') }})</span>
                </td>
                @endforeach
                @if(count($bRow) == 1)
                <td style="width: 50%;"></td>
                @endif
            </tr>
            @endforeach
        </table>
    @else
        <div style="margin-left: 10px; color: #666;">- Tidak ada bantuan yang dipilih -</div>
    @endif

    <hr class="section-divider">

    {{-- ====== DOKUMEN PERSYARATAN TERLAMPIR ====== --}}
    <div class="section-header">Sebagai pertimbangan Pengasuh, kami lampirkan dokumen persyaratan sebagai berikut :</div>
    @if(count($dokumenTerlampir) > 0)
        @php
            $hurufDokumen = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l'];
            $dIdx = 0;
        @endphp
        <table class="doc-list-table">
            @foreach(array_chunk($dokumenTerlampir, 2) as $dRow)
            <tr>
                @foreach($dRow as $docName)
                <td style="width: 50%;">
                    <strong>{{ $hurufDokumen[$dIdx++] ?? '-' }}.</strong>&nbsp;
                    {{ $docName }}
                </td>
                @endforeach
                @if(count($dRow) == 1)
                <td style="width: 50%;"></td>
                @endif
            </tr>
            @endforeach
        </table>
    @else
        <div style="margin-left: 10px; font-style: italic; color: #666;">(Belum ada dokumen penunjang yang dilampirkan)</div>
    @endif

    {{-- ====== TANDA TANGAN ====== --}}
    <table class="signature-table">
        <tr>
            <td style="text-align: left; padding-left: 20px;">
                Mengetahui,<br>
                Wali / Ka. Kamar
                <div style="height: 52px; margin: 3px 0;">
                    @php
                        $ttdWaliPath = $pengajuan->tanda_tangan_wali ? storage_path('app/public/' . $pengajuan->tanda_tangan_wali) : null;
                        $ttdWaliBase64 = '';
                        if ($ttdWaliPath && file_exists($ttdWaliPath)) {
                            $ttdWaliBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ttdWaliPath));
                        }
                    @endphp
                    @if($ttdWaliBase64)
                        <img src="{{ $ttdWaliBase64 }}" style="height: 50px; width: auto; max-width: 150px; vertical-align: middle;">
                    @else
                        <br><br>
                    @endif
                </div>
                <strong><u>{{ !empty($pengajuan->santri->nama_wali) ? $pengajuan->santri->nama_wali : '..................................................' }}</u></strong>
            </td>
            <td style="text-align: right; padding-right: 20px;">
                Sukorejo, {{ $pengajuan->created_at ? \Carbon\Carbon::parse($pengajuan->created_at)->locale('id')->translatedFormat('d F Y') : date('d F Y') }}<br>
                Pemohon,
                <div style="height: 52px; margin: 3px 0;">
                    @php
                        $ttdPath = $pengajuan->tanda_tangan ? storage_path('app/public/' . $pengajuan->tanda_tangan) : null;
                        $ttdBase64 = '';
                        if ($ttdPath && file_exists($ttdPath)) {
                            $ttdBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPath));
                        }
                    @endphp
                    @if($ttdBase64)
                        <img src="{{ $ttdBase64 }}" style="height: 50px; width: auto; max-width: 150px; vertical-align: middle;">
                    @else
                        <br><br>
                    @endif
                </div>
                <strong><u>{{ !empty($pengajuan->santri->nama_lengkap) ? $pengajuan->santri->nama_lengkap : '..................................................' }}</u></strong><br>
            </td>
        </tr>
    </table>

</body>
</html>
