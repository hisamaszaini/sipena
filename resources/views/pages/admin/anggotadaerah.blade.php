@extends('layouts.admin')

@section('content')
<div class="p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">{{ ucwords(Auth::user()->role) }} / {{ $title }}</h1>
    </div>
    <div class="bg-gray-800 p-4 rounded-lg mb-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-white">Daftar Anggota Daerah</h2>
            <div>
                <button id="openAddModal" class="py-1 px-2 bg-blue-700 text-white rounded-md">Tambah</button>
                <a href="{{ route('anggotadaerah.export') }}" class="py-1 px-2 bg-green-600 text-white rounded-md">Export Excel</a>
            </div>
        </div>
        <div class="overflow-hidden">
            <table id="dataTable" class="w-full table-auto text-sm text-white">
                <thead>
                    <tr class="border-b border-gray-700 text-center">
                        <th class="pb-2 text-left pl-2">NIK</th>
                        <th class="pb-2 text-left">Nama</th>
                        <th class="pb-2 text-left">Daerah</th>
                        <th class="pb-2 text-left">Jabatan</th>
                        <th class="pb-2 text-left">Status</th>
                        <th class="pb-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="modal-form" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-lg p-4 sm:p-6 w-full max-w-4xl mx-auto max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modal-title" class="text-xl font-bold text-white">Tambah Anggota Daerah</h3>
            <button class="text-gray-400 hover:text-white close-modal">
                ✖
            </button>
        </div>
        <form id="anggota-form" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <input type="hidden" name="id" id="anggota-id">
            <!-- Section Biodata -->
            <div class="md:col-span-2">
                <h4 class="text-lg font-semibold text-white mb-2">Biodata</h4>
            </div>
            <div class="md:col-span-1">
                <label for="nik" class="block text-white mb-1">NIK</label>
                <input type="text" id="nik" name="nik" required class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="nba" class="block text-white mb-1">NBA</label>
                <input type="text" id="nba" name="nba" class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="nama" class="block text-white mb-1">Nama</label>
                <input type="text" id="nama" name="nama" required class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="email" class="block text-white mb-1">Email</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="no_telp" class="block text-white mb-1">No. Telp</label>
                <input type="text" id="no_telp" name="no_telp" required class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="jenis_kelamin" class="block text-white mb-1">Jenis Kelamin</label>
                <select id="jenis_kelamin" name="jenis_kelamin" required class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                    <option value="">Pilih</option>
                    <option value="laki-laki">Laki-laki</option>
                    <option value="perempuan">Perempuan</option>
                </select>
            </div>
            <div class="md:col-span-1">
                <label for="agama" class="block text-white mb-1">Agama</label>
                <input type="text" id="agama" name="agama" class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="kecamatan_id" class="block text-white mb-1">Kecamatan</label>
                <select id="kecamatan_id" name="kecamatan_id" required class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                    <option value="">Pilih Kecamatan</option>
                    @foreach($kecamatan as $kec)
                        <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-1">
                <label for="tempat_lahir" class="block text-white mb-1">Tempat Lahir</label>
                <input type="text" id="tempat_lahir" name="tempat_lahir" class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="tanggal_lahir" class="block text-white mb-1">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="pendidikan_sekarang" class="block text-white mb-1">Pendidikan Sekarang</label>
                <input type="text" id="pendidikan_sekarang" name="pendidikan_sekarang" class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="pendidikan_terakhir" class="block text-white mb-1">Pendidikan Terakhir</label>
                <input type="text" id="pendidikan_terakhir" name="pendidikan_terakhir" class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="alamat_tinggal" class="block text-white mb-1">Alamat Tinggal</label>
                <input type="text" id="alamat_tinggal" name="alamat_tinggal" class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="alamat_asal" class="block text-white mb-1">Alamat Asal</label>
                <input type="text" id="alamat_asal" name="alamat_asal" class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <!-- Section Anggota Daerah -->
            <div class="md:col-span-2 mt-4">
                <h4 class="text-lg font-semibold text-white mb-2">Informasi Anggota</h4>
            </div>
            <div class="md:col-span-1">
                <label for="daerah_id" class="block text-white mb-1">Daerah</label>
                <select id="daerah_id" name="daerah_id" required class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                        <option value="">Pilih Daerah</option>
                    @foreach($daerah as $cb)
                        <option value="{{ $cb->id }}">{{ $cb->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-1">
                <label for="jabatan" class="block text-white mb-1">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan" required class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="status" class="block text-white mb-1">Status</label>
                <select id="status" name="status" required
                    class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Non-Aktif</option>
                </select>
            </div>
            <div class="col-span-1 md:col-span-2 mt-6 text-right">
                <button type="button" class="px-4 py-2 bg-gray-700 text-white rounded-md mr-2 close-modal">Batal</button>
                <button type="submit" id="submit-btn" class="px-4 py-2 bg-green-700 text-white rounded-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal -->
<div id="view-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-lg p-4 sm:p-6 w-full max-w-4xl mx-auto max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-white">Detail Anggota Daerah</h3>
            <button class="text-gray-400 hover:text-white close-view-modal">
                ✖
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Biodata -->
            <div class="md:col-span-1">
                <p class="text-gray-400 mb-1">NIK:</p>
                <p id="view-nik" class="text-white font-medium"></p>
            </div>
            <div class="md:col-span-1">
                <p class="text-gray-400 mb-1">Nama:</p>
                <p id="view-nama" class="text-white font-medium"></p>
            </div>
            <!-- Informasi Anggota -->
            <div class="md:col-span-1">
                <p class="text-gray-400 mb-1">Daerah:</p>
                <p id="view-daerah" class="text-white font-medium"></p>
            </div>
            <div class="md:col-span-1">
                <p class="text-gray-400 mb-1">Jabatan:</p>
                <p id="view-jabatan" class="text-white font-medium"></p>
            </div>
            <div class="md:col-span-1">
                <p class="text-gray-400 mb-1">Status:</p>
                <p id="view-status" class="text-white font-medium"></p>
            </div>
            <div class="col-span-1 md:col-span-2 mt-6 text-right">
                <button type="button" class="px-4 py-2 bg-gray-700 text-white rounded-md close-view-modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('anggotadaerah.index') }}"
        },
        columns: [
            { data: 'biodata.nik', name: 'biodata.nik', width: '15%', className: 'font-medium' },
            { data: 'biodata.nama', name: 'biodata.nama', width: '20%' },
            { data: 'daerah.nama', name: 'daerah.nama', width: '20%' },
            { data: 'jabatan', name: 'jabatan', width: '20%' },
            { data: 'status', name: 'status',
                render: function(data) {
                        return data === 'aktif' ?
                            '<span class="px-3 py-1 bg-green-600 rounded-lg text-sm text-white">Aktif</span>' :
                            '<span class="px-3 py-1 bg-red-700 rounded-lg text-sm text-white">Nonaktif</span>';
                    }, width: '15%' },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false, 
                width: '10%', 
                className: 'text-center',
                render: function(data, type, row) {
                    return `
                        <div class="flex justify-center space-x-2">
                            <button class="view-btn py-1.5 px-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition" data-id="${row.id}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="edit-btn py-1.5 px-3 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition" data-id="${row.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="delete-btn py-1.5 px-3 bg-red-600 text-white rounded-md hover:bg-red-700 transition" data-id="${row.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center mb-4"<"flex items-center"l><"ml-0 md:ml-auto"f>>' +
             '<"overflow-x-auto shadow-md rounded-lg border border-gray-700"tr>' +
             '<"flex flex-col md:flex-row justify-between items-center mt-4"<"text-sm text-gray-400"i><"pagination-container"p>>',
        language: {
            lengthMenu: '<span class="text-white mr-2">Tampilkan</span>' +
                '<select class="bg-gray-700 text-white border border-gray-600 rounded px-2 py-1 focus:outline-none focus:border-blue-500">' +
                '<option value="10">10</option>' +
                '<option value="25">25</option>' +
                '<option value="50">50</option>' +
                '<option value="100">100</option>' +
                '</select>' +
                '<span class="text-white ml-2">entri</span>',
            search: '<span class="text-white mr-2">Cari:</span>',
            searchPlaceholder: 'Ketik untuk mencari...',
            paginate: {
                first: '<span class="font-bold">«</span>',
                previous: '<span class="font-bold">‹</span>',
                next: '<span class="font-bold">›</span>',
                last: '<span class="font-bold">»</span>'
            },
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            emptyTable: "Tidak ada data yang tersedia"
        },
        initComplete: function () {
            $('.dataTables_filter input').addClass('bg-gray-700 text-white border border-gray-600 rounded px-3 py-1 focus:outline-none focus:border-blue-500 w-64');
            $('.dataTables_filter label').addClass('flex items-center');
            $('#dataTable thead th').each(function () {
                if (!$(this).hasClass('no-sort')) {
                    $(this).append('<span class="ml-1 inline-block sort-icon"><i class="fas fa-sort text-gray-400 text-xs"></i></span>');
                }
            });
            $('#dataTable thead th').addClass('bg-gray-800 text-left px-4 py-3 font-semibold text-white');
            $('#dataTable tbody tr').addClass('border-b border-gray-700 hover:bg-gray-800 transition');
            $('#dataTable tbody td').addClass('px-4 py-3');
            $('.dataTables_paginate').addClass('flex items-center space-x-1');
            $('.paginate_button').addClass('px-3 py-1 bg-gray-700 text-white rounded hover:bg-gray-600 focus:outline-none transition');
            $('.paginate_button.current').removeClass('bg-gray-700').addClass('bg-blue-600 hover:bg-blue-700');
            $('.paginate_button.disabled').removeClass('hover:bg-gray-600').addClass('opacity-50 cursor-not-allowed');
        },
        drawCallback: function () {
            $('#dataTable tbody tr').each(function () {
                $(this).find('td').each(function (index) {
                    var columnTitle = $('#dataTable thead th').eq(index).text().trim();
                    $(this).attr('data-label', columnTitle);
                });
            });
            $('#dataTable tbody tr').addClass('border-b border-gray-700 hover:bg-gray-800 transition');
            $('#dataTable tbody td').addClass('px-4 py-3');
            $('#dataTable thead .sorting').find('.sort-icon i').removeClass().addClass('fas fa-sort text-gray-400 text-xs');
            $('#dataTable thead .sorting_asc').find('.sort-icon i').removeClass().addClass('fas fa-sort-up text-blue-400 text-xs');
            $('#dataTable thead .sorting_desc').find('.sort-icon i').removeClass().addClass('fas fa-sort-down text-blue-400 text-xs');
            $('.paginate_button').addClass('px-3 py-1 bg-gray-700 text-white rounded hover:bg-gray-600 focus:outline-none transition');
            $('.paginate_button.current').removeClass('bg-gray-700').addClass('bg-blue-600 hover:bg-blue-700');
            $('.paginate_button.disabled').removeClass('hover:bg-gray-600').addClass('opacity-50 cursor-not-allowed');
        },
        order: [[0, 'asc']],
        responsive: true,
        autoWidth: false
    });

    // Open Add Modal
    $('#openAddModal').click(function () {
        resetForm();
        $('#modal-title').text('Tambah Anggota Daerah');
        $('#modal-form').removeClass('hidden');
        $('#submit-btn').text('Simpan');
    });

    // Close Modal
    $('.close-modal').click(function () {
        $('#modal-form').addClass('hidden');
        resetForm();
    });

    // Close View Modal
    $('.close-view-modal').click(function () {
        $('#view-modal').addClass('hidden');
    });

    // Reset Form
    function resetForm() {
        $('#anggota-form')[0].reset();
        $('#anggota-id').val('');
    }

    // Submit Form
    $('#anggota-form').submit(function (e) {
        e.preventDefault();
        var id = $('#anggota-id').val();
        var url = id ? "{{ route('anggotadaerah.update', ':id') }}".replace(':id', id) : "{{ route('anggotadaerah.store') }}";
        var method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function (response) {
                $('#modal-form').addClass('hidden');
                resetForm();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message
                });
                table.ajax.reload();
            },
            error: function (xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '';

                if (errors) {
                    $.each(errors, function (key, value) {
                        errorMessage += value[0] + '<br>';
                    });
                } else {
                    errorMessage = 'Terjadi kesalahan saat menyimpan data!';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: errorMessage
                });
            }
        });
    });

    // View
    $(document).on('click', '.view-btn', function () {
        var id = $(this).data('id');

        $.get("{{ route('anggotadaerah.edit', ':id') }}".replace(':id', id), function (response) {
            $('#view-nik').text(response.biodata.nik);
            $('#view-nama').text(response.biodata.nama);
            $('#view-daerah').text(response.daerah.nama);
            $('#view-jabatan').text(response.jabatan);
            $('#view-status').text(response.status.toLowerCase().replace(/\b\w/g, char => char.toUpperCase()));
            $('#view-modal').removeClass('hidden');
        }).fail(function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Gagal memuat data anggota!'
            });
        });
    });

    // Edit
    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');

        $.get("{{ route('anggotadaerah.edit', ':id') }}".replace(':id', id), function (response) {
            $('#anggota-id').val(response.id);
            // Isi data biodata
            $('#nik').val(response.biodata.nik);
            $('#nba').val(response.biodata.nba);
            $('#nama').val(response.biodata.nama);
            $('#email').val(response.biodata.email);
            $('#no_telp').val(response.biodata.no_telp);
            $('#jenis_kelamin').val(response.biodata.jenis_kelamin);
            $('#agama').val(response.biodata.agama);
            $('#kecamatan_id').val(response.biodata.kecamatan_id);
            $('#tempat_lahir').val(response.biodata.tempat_lahir);
            $('#tanggal_lahir').val(response.biodata.tanggal_lahir);
            $('#pendidikan_terakhir').val(response.biodata.pendidikan_terakhir);
            $('#pendidikan_sekarang').val(response.biodata.pendidikan_sekarang);
            $('#alamat_tinggal').val(response.biodata.alamat_tinggal);
            $('#alamat_asal').val(response.biodata.alamat_asal);
            // Isi data anggota daerah
            $('#daerah_id').val(response.daerah_id);
            $('#jabatan').val(response.jabatan);
            $('#status').val(response.status);

            $('#modal-title').text('Edit Anggota Daerah');
            $('#submit-btn').text('Update');
            $('#modal-form').removeClass('hidden');
        }).fail(function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Gagal memuat data anggota!'
            });
        });
    });

    // Delete
    $(document).on('click', '.delete-btn', function () {
        var id = $(this).data('id');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('anggotadaerah.destroy', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    data: { "_token": "{{ csrf_token() }}" },
                    success: function (response) {
                        Swal.fire('Terhapus!', response.message, 'success');
                        table.ajax.reload();
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat menghapus data!'
                        });
                    }
                });
            }
        });
    });
});
</script>
<script src="{{ asset('js/biodata-autocomplete.js') }}"></script>
@endsection
