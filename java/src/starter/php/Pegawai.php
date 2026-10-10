<?php
declare(strict_types=1);

/**
 * Sesi 4 — hierarki pegawai (PHP).
 * Seluruh hierarki ditaruh dalam satu berkas agar mudah dibaca berdampingan
 * dengan versi Java. Mulai sesi 9, satu kelas = satu berkas.
 */
abstract class Pegawai
{
    public function __construct(
        protected readonly string $nip,
        protected readonly string $nama,
        protected readonly float  $gajiPokok,
    ) {
        // TODO 1: tolak gaji pokok negatif
        if (is_nan($gajiPokok) || $gajiPokok < 0) {
            throw new InvalidArgumentException(
                'Gaji pokok tidak boleh negatif, tetapi diterima: ' . $gajiPokok
            );
        }
    }

    /** TODO 2: kembalikan gaji pokok apa adanya. */
    public function hitungGaji(): float
    {
        return $this->gajiPokok;
    }

    abstract public function jenis(): string;

    public function getNama(): string { return $this->nama; }
    public function getNip(): string  { return $this->nip; }

    public function __toString(): string
    {
        return sprintf('%-14s %-9s %-20s Rp%s',
            $this->nip, $this->jenis(), $this->nama,
            number_format($this->hitungGaji(), 2, ',', '.'));
    }
}

class PegawaiTetap extends Pegawai
{
    protected const float TUNJANGAN_PER_TAHUN = 0.02;
    protected const float TUNJANGAN_MAKSIMUM  = 0.40;

    public function __construct(
        string $nip, string $nama, float $gajiPokok,
        protected readonly int $masaKerjaTahun,
    ) {
        // WAJIB. TODO 3 (Langkah 3): hapus sementara baris ini,
        //         jalankan, salin pesan kesalahannya, lalu kembalikan.
        parent::__construct($nip, $nama, $gajiPokok);
    }

    /**
     * TODO 4: gaji dasar induk + tunjangan masa kerja.
     *         Memakai parent::hitungGaji(), rumus induk tidak disalin.
     */
    public function hitungGaji(): float
    {
        $gajiDasar       = parent::hitungGaji();
        $persenTunjangan = min($this->masaKerjaTahun * self::TUNJANGAN_PER_TAHUN, self::TUNJANGAN_MAKSIMUM);

        return $gajiDasar + $gajiDasar * $persenTunjangan;
    }

    public function jenis(): string { return 'TETAP'; }
}

class PegawaiKontrak extends Pegawai
{
    public function __construct(
        string $nip, string $nama, float $gajiPokok,
        private readonly int $bulanKontrak,
    ) {
        parent::__construct($nip, $nama, $gajiPokok);
    }

    // Tidak meng-override hitungGaji(): rumus induk (gaji pokok apa adanya)
    // sudah sama dengan aturan pegawai kontrak.

    public function jenis(): string { return 'KONTRAK'; }

    public function getBulanKontrak(): int { return $this->bulanKontrak; }
}

// TODO Langkah 4: Dosen dan PegawaiHarian.

/** Dosen: pegawai tetap (dapat tunjangan masa kerja) + tunjangan fungsional. */
class Dosen extends PegawaiTetap
{
    private readonly float $tunjanganFungsional;

    public function __construct(
        string $nip, string $nama, float $gajiPokok, int $masaKerjaTahun,
        float $tunjanganFungsional,
    ) {
        parent::__construct($nip, $nama, $gajiPokok, $masaKerjaTahun);

        if (is_nan($tunjanganFungsional) || $tunjanganFungsional < 0) {
            throw new InvalidArgumentException(
                'Tunjangan fungsional tidak boleh negatif, tetapi diterima: ' . $tunjanganFungsional
            );
        }
        $this->tunjanganFungsional = $tunjanganFungsional;
    }

    public function hitungGaji(): float
    {
        // parent::hitungGaji() = gaji pokok + tunjangan masa kerja (dari PegawaiTetap)
        return parent::hitungGaji() + $this->tunjanganFungsional;
    }

    public function jenis(): string { return 'DOSEN'; }
}

/** Pegawai harian: $gajiPokok dipakai sebagai upah per hari, dikali hari kerja. */
class PegawaiHarian extends Pegawai
{
    public function __construct(
        string $nip, string $nama, float $upahPerHari,
        private readonly int $hariKerja,
    ) {
        parent::__construct($nip, $nama, $upahPerHari);

        if ($hariKerja < 0) {
            throw new InvalidArgumentException(
                'Hari kerja tidak boleh negatif, tetapi diterima: ' . $hariKerja
            );
        }
    }

    public function hitungGaji(): float
    {
        return parent::hitungGaji() * $this->hariKerja;
    }

    public function jenis(): string { return 'HARIAN'; }

    public function getHariKerja(): int { return $this->hariKerja; }
}