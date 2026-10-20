<?php

namespace App\Console\Commands;

use App\Models\Lembaga;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;

class SyncLembagaLocalCommand extends Command
{
    protected $signature = 'eregistrasi:sync-local';
    protected $description = 'Sinkronisasi data lembaga langsung dari database lokal nest_db_eregistrasi';

    public function handle()
    {
        $this->info('Menghubungkan ke database lokal eregistrasi_v2...');

        try {
            // Coba koneksi ke MySQL lokal (host.docker.internal / 127.0.0.1 / nest_db_eregistrasi)
            $hosts = ['host.docker.internal', '172.17.0.1', '127.0.0.1'];
            $pdo = null;

            foreach ($hosts as $host) {
                try {
                    $pdo = new PDO("mysql:host={$host};port=3307;dbname=eregistrasi_v2;charset=utf8mb4", 'admin_nest', 'password_nest', [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 3,
                    ]);
                    $this->line("Terhubung via {$host}:3307!");
                    break;
                } catch (\Exception $e) {
                    continue;
                }
            }

            if (!$pdo) {
                $this->error('Gagal terhubung ke MySQL lokal port 3307.');
                return 1;
            }

            $stmt = $pdo->query("SELECT id_pendidikan, nama_pendidikan, singkatan, tingkatan, biaya, utap FROM pendidikan ORDER BY id_pendidikan ASC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->info('Ditemukan ' . count($rows) . ' lembaga di eregistrasi_v2.');

            $inserted = 0;
            $updated = 0;

            foreach ($rows as $item) {
                $nama = trim($item['nama_pendidikan'] ?? '');
                if (!$nama) continue;

                $kode = trim($item['singkatan'] ?? '');
                if (!$kode) {
                    $kode = 'LMB-' . $item['id_pendidikan'];
                }

                $tingkatan = strtoupper($item['tingkatan'] ?? '');
                $jenis = str_contains($tingkatan, 'MADRASAH') ? 'sore' : 'pagi';

                $lembaga = Lembaga::where('kode_lembaga', $kode)
                    ->orWhere('nama_lembaga', $nama)
                    ->first();

                if ($lembaga) {
                    $lembaga->update([
                        'kode_lembaga' => $kode,
                        'nama_lembaga' => $nama,
                        'bidang' => $item['tingkatan'] ?? $lembaga->bidang,
                        'jenis_lembaga' => $jenis,
                    ]);
                    $updated++;
                } else {
                    Lembaga::create([
                        'kode_lembaga' => $kode,
                        'nama_lembaga' => $nama,
                        'bidang' => $item['tingkatan'] ?? 'UMUM',
                        'jenis_lembaga' => $jenis,
                    ]);
                    $inserted++;
                }
            }

            $this->info("Selesai! {$inserted} baru ditambahkan, {$updated} diperbarui.");
            return 0;

        } catch (\Exception $e) {
            $this->error('Terjadi error: ' . $e->getMessage());
            return 1;
        }
    }
}
