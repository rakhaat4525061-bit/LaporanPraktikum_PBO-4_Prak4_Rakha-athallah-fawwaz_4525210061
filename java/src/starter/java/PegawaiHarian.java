/** Pegawai harian: gajiPokok dipakai sebagai upah per hari, dikalikan jumlah hari kerja. */
public class PegawaiHarian extends Pegawai {

    private final int hariKerja;

    public PegawaiHarian(String nip, String nama, double upahPerHari, int hariKerja) {
        super(nip, nama, upahPerHari);
        if (hariKerja < 0) {
            throw new IllegalArgumentException(
                    "Hari kerja tidak boleh negatif, tetapi diterima: " + hariKerja);
        }
        this.hariKerja = hariKerja;
    }

    @Override
    public double hitungGaji() {
        return super.hitungGaji() * hariKerja;
    }

    @Override
    public String jenis() { return "HARIAN"; }

    public int getHariKerja() { return hariKerja; }
}