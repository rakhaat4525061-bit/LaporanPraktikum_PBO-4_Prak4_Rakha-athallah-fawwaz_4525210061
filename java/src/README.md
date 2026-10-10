# Sesi 4 — Pewarisan: Hierarki Pegawai

Latihan keempat PBO: membangun hierarki kelas pegawai dengan **pewarisan** (`extends`), **kelas abstract**, **override** dengan `super` / `parent`, dan **polimorfisme**. Diimplementasikan dalam **Java** dan **PHP**.

## Identitas

| Nama                  | NIM        |
|-----------------------|------------|
| Rakha Athallah Fawwaz | 4525210061 |

## Hierarki Kelas

```
Pegawai (abstract)
├── PegawaiTetap
│   └── Dosen
├── PegawaiKontrak
└── PegawaiHarian
```

| Kelas            | Aturan Gaji                                                                 | Override `hitungGaji()` |
|------------------|-----------------------------------------------------------------------------|-------------------------|
| `Pegawai`        | Gaji pokok apa adanya                                                       | (dasar)                 |
| `PegawaiTetap`   | Gaji pokok + tunjangan masa kerja (2% per tahun, maksimum 40%)              | Ya                      |
| `PegawaiKontrak` | Gaji pokok apa adanya, tanpa tunjangan masa kerja                           | Tidak                   |
| `Dosen`          | Gaji `PegawaiTetap` + tunjangan jabatan/fungsional                          | Ya                      |
| `PegawaiHarian`  | Upah per hari × jumlah hari kerja                                           | Ya                      |

> Aturan `Dosen` dan `PegawaiHarian` adalah asumsi penulis karena soal tidak menetapkannya.

## Invariant

| No | Invariant                                          | Cara Menjaganya                                              |
|----|----------------------------------------------------|--------------------------------------------------------------|
| 1  | Gaji pokok tidak boleh negatif                     | Validasi di constructor `Pegawai`                            |
| 2  | NIP, nama, dan gaji pokok tidak berubah            | `final` (Java) / `readonly` (PHP)                            |
| 3  | Tunjangan masa kerja tidak melebihi 40%            | `Math.min` / `min` terhadap `TUNJANGAN_MAKSIMUM`             |
| 4  | Tunjangan tambahan dan hari kerja tidak negatif    | Validasi di constructor `Dosen` dan `PegawaiHarian`          |
| 5  | Nilai `NaN` tidak dianggap angka yang sah          | `isNaN` / `is_nan` dicek terpisah                            |

## Struktur Berkas

```
.
├── java/
│   ├── Pegawai.java
│   ├── PegawaiTetap.java
│   ├── PegawaiKontrak.java
│   ├── Dosen.java
│   ├── PegawaiHarian.java
│   └── Main.java
├── php/
│   ├── Pegawai.php        (seluruh hierarki dalam satu berkas)
│   └── main.php
├── catatan.md
└── README.md
```

## Konsep Utama

### 1. Pewarisan: turunan hanya menulis yang membedakannya

`PegawaiTetap` memakai `super.hitungGaji()` (Java) / `parent::hitungGaji()` (PHP) untuk gaji dasar, lalu menambahkan tunjangan. Rumus induk **tidak disalin**, sehingga jika rumus dasar berubah, semua turunan ikut berubah.

### 2. Tidak semua turunan perlu override

`PegawaiKontrak` tidak meng-override `hitungGaji()`, karena perilaku induk (gaji pokok apa adanya) sudah sama dengan aturan pegawai kontrak. Override hanya dibutuhkan jika perilakunya **berbeda atau bertambah**.

### 3. Constructor induk wajib dipanggil

- **Java:** `super(...)` harus menjadi pernyataan pertama. Jika dihapus, kompilasi gagal karena `Pegawai` tidak punya constructor tanpa parameter.
- **PHP:** `parent::__construct(...)` tidak dipaksa oleh kompilator. Jika dihapus, error baru muncul saat properti induk dibaca (`must not be accessed before initialization`).

### 4. Kelas abstract

`Pegawai` bersifat `abstract`: tidak bisa di-`new` langsung, dan method abstract `jenis()` wajib dilengkapi oleh turunan.

### 5. Polimorfisme

Loop pada `Main` memperlakukan semua objek sebagai `Pegawai`. Panggilan `p.hitungGaji()` dan `toString()` otomatis memakai versi milik kelas sebenarnya. Menambah `Dosen` dan `PegawaiHarian` tidak mengubah loop sama sekali.

## Cara Menjalankan

### Java

Prasyarat: JDK 8 atau lebih baru.

```bash
cd java
javac -d out *.java
java -cp out Main
```

### PHP

Prasyarat: PHP 8.3 atau lebih baru (konstanta bertipe `const float`).
Untuk PHP 8.0–8.2, hapus kata `float` pada deklarasi konstanta. `readonly` pada properti butuh PHP 8.1+.

```bash
cd php
php main.php
```

## Contoh Keluaran

**Java** (format angka mengikuti locale; contoh ini memakai en-US)

```
=== Daftar Gaji ===
  198701012010   TETAP     Ani Lestari          Rp7,800,000.00
  K-2024-007     KONTRAK   Budi Santoso         Rp5,000,000.00
  199005052015   DOSEN     Citra Dewi           Rp9,900,000.00
  H-2026-001     HARIAN    Dedi Pratama         Rp3,300,000.00

  Total beban gaji: Rp26,000,000.00

Periksa: Ani (pokok 6.000.000, masa kerja 15 tahun)
  tunjangan 15 x 2% = 30%, jadi gaji seharusnya Rp7.800.000,00
```

**PHP** menghasilkan isi yang sama dengan format Indonesia (`Rp7.800.000,00`).

### Rincian Perhitungan

| Pegawai          | Perhitungan                      | Gaji        |
|------------------|----------------------------------|-------------|
| Ani (Tetap)      | 6.000.000 × (1 + 15 × 2%)        | 7.800.000   |
| Budi (Kontrak)   | gaji pokok                       | 5.000.000   |
| Citra (Dosen)    | 7.000.000 × 1,20 + 1.500.000     | 9.900.000   |
| Dedi (Harian)    | 150.000 × 22 hari                | 3.300.000   |
| **Total**        |                                  | **26.000.000** |

## Perbandingan Java vs PHP

| Konsep                     | Java                                  | PHP                                              |
|----------------------------|---------------------------------------|--------------------------------------------------|
| Pewarisan                  | `extends`                             | `extends`                                        |
| Kelas abstract             | `public abstract class`               | `abstract class`                                 |
| Method abstract            | `public abstract String jenis();`     | `abstract public function jenis(): string;`      |
| Memanggil constructor induk| `super(...)`                          | `parent::__construct(...)`                       |
| Memanggil method induk     | `super.hitungGaji()`                  | `parent::hitungGaji()`                           |
| Penanda override           | `@Override` (opsional, dianjurkan)    | Tidak ada                                        |
| Akses untuk turunan        | `protected`                           | `protected`                                      |
| Atribut tak berubah        | `final`                               | `readonly`                                       |
| Lupa memanggil induk       | Gagal saat kompilasi                  | Gagal saat properti induk dibaca                 |
| Satu kelas per berkas      | Wajib untuk kelas `public`            | Tidak wajib (sesi ini satu berkas)               |

## Catatan Desain

- **Validasi hanya di induk:** gaji pokok negatif ditolak sekali di `Pegawai`, dan semua turunan otomatis terlindungi.
- **`protected` terbatas:** turunan boleh membaca `nip`, `nama`, dan `gajiPokok`, dunia luar tidak.
- **`Dosen extends PegawaiTetap`:** dosen berhak atas tunjangan masa kerja yang sama, dan hanya menambahkan tunjangannya sendiri lewat `super.hitungGaji()`.
- **Yang belum divalidasi:** `masaKerjaTahun` dan `bulanKontrak` belum diperiksa negatif. Nilai negatif pada `masaKerjaTahun` akan menurunkan gaji di bawah gaji pokok.