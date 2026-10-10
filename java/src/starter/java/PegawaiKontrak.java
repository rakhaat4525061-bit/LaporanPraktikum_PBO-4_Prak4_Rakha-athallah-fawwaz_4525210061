public class PegawaiKontrak extends Pegawai {

    private final int bulanKontrak;

    public PegawaiKontrak(String nip, String nama, double gajiPokok, int bulanKontrak) {
        super(nip, nama, gajiPokok);
        this.bulanKontrak = bulanKontrak;
    }

    // TODO 2: TIDAK perlu di-override.
    // Pegawai.hitungGaji() sudah mengembalikan gajiPokok apa adanya, dan itulah
    // yang berlaku untuk pegawai kontrak (tanpa tunjangan masa kerja).
    // Meng-override hanya untuk menulis ulang `return gajiPokok;` tidak menambah
    // perilaku baru, hanya menggandakan kode.

    @Override
    public String jenis() { return "KONTRAK"; }

    public int getBulanKontrak() { return bulanKontrak; }
}