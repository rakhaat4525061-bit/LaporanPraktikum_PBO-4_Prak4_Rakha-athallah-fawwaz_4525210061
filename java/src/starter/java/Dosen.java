/** Dosen adalah pegawai tetap, jadi mewarisi tunjangan masa kerja, lalu menambah tunjangan jabatan. */
public class Dosen extends PegawaiTetap {

    private final double tunjanganJabatan;

    public Dosen(String nip, String nama, double gajiPokok, int masaKerjaTahun, double tunjanganJabatan) {
        super(nip, nama, gajiPokok, masaKerjaTahun);
        if (Double.isNaN(tunjanganJabatan) || tunjanganJabatan < 0) {
            throw new IllegalArgumentException(
                    "Tunjangan jabatan tidak boleh negatif, tetapi diterima: " + tunjanganJabatan);
        }
        this.tunjanganJabatan = tunjanganJabatan;
    }

    @Override
    public double hitungGaji() {
        // super.hitungGaji() = gaji pokok + tunjangan masa kerja (dari PegawaiTetap)
        return super.hitungGaji() + tunjanganJabatan;
    }

    @Override
    public String jenis() { return "DOSEN"; }
}