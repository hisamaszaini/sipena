$(document).ready(function() {
    $('#nik').on('keyup', function() {
        var nik = $(this).val();

        if (nik.length >= 16) {
            $.ajax({
                url: "/biodata-autocomplete",
                type: "GET",
                data: { nik: nik },
                success: function(response) {
                    if (response.success) {
                        // Jika data biodata ditemukan, otomatis isi form biodata
                        $('#nba').val(response.data.nba);
                        $('#nama').val(response.data.nama);
                        $('#email').val(response.data.email);
                        $('#no_telp').val(response.data.no_telp);
                        $('#jenis_kelamin').val(response.data.jenis_kelamin);
                        $('#agama').val(response.data.agama);
                        $('#kecamatan_id').val(response.data.kecamatan_id);
                        $('#tempat_lahir').val(response.data.tempat_lahir);
                        $('#tanggal_lahir').val(response.data.tanggal_lahir);
                        $('#alamat_tinggal').val(response.data.alamat_tinggal);
                        $('#alamat_asal').val(response.data.alamat_asal);
                    }
                },
                error: function(xhr) {
                    console.log('Terjadi kesalahan saat mengambil data biodata');
                }
            });
        }
    });
});