@extends('layouts.operator')

@section('content')
<div class="p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">{{ ucwords(Auth::user()->role) }} / {{ $title }}</h1>
    </div>
    <div class="bg-gray-800 p-4 rounded-lg mb-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-white">Daftar Ranting</h2>
            <div>
                <button id="openAddModal" class="py-1 px-2 bg-blue-700 text-white rounded-md">Tambah</button>
                <a href="{{ route('ranting.export') }}" class="py-1 px-2 bg-green-600 text-white rounded-md">Export
                    Excel</a>
            </div>
        </div>
        <div class="overflow-hidden">
            <table id="dataTable" class="w-full table-auto text-sm text-white">
                <thead>
                    <tr class="border-b border-gray-700 text-center">
                        <th class="pb-2 text-left pl-2">Nama</th>
                        <th class="pb-2 text-left">Pimpinan</th>
                        <th class="pb-2 text-left">No Telp</th>
                        <th class="pb-2 text-left">Desa</th>
                        <th class="pb-2 text-left">Alamat</th>
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
            <h3 id="modal-title" class="text-xl font-bold text-white">Tambah Ranting</h3>
            <button class="text-gray-400 hover:text-white close-modal">
                ✖
            </button>
        </div>
        <form id="ranting-form" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <input type="hidden" name="id" id="ranting-id">
            <div class="md:col-span-1">
                <label for="nama" class="block text-white mb-1">Nama Ranting</label>
                <input type="text" id="nama" name="nama" required
                    class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="nomor_sk" class="block text-white mb-1">Nomor SK</label>
                <input type="text" id="nomor_sk" name="nomor_sk" required
                    class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="nama_pimpinan" class="block text-white mb-1">Nama Pimpinan</label>
                <input type="text" id="nama_pimpinan" name="nama_pimpinan" required
                    class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="no_telp" class="block text-white mb-1">Nomor Telpon</label>
                <input type="text" id="no_telp" name="no_telp" required
                    class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-1">
                <label for="desa" class="block text-white mb-1">Desa</label>
                <select id="desa" name="desa_id" required
                    class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                    <option value="">Pilih Desa</option>
                    @foreach($desa as $kec)
                    <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-1">
                <label for="cabang" class="block text-white mb-2">Cabang</label>
                <input type="text" value="{{ $user->cabang->nama }}" readonly
                    class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                <input type="hidden" name="cabang_id" value="{{ $user->cabang_id }}">
                </select>
                <input type="hidden" name="cabang_id" value="{{ $user->cabang_id }}">
            </div>
            <div class="md:col-span-2">
                <label for="alamat" class="block text-white mb-1">Alamat</label>
                <input type="text" id="alamat" name="alamat" required
                    class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
            </div>
            <div class="col-span-1 md:col-span-2 mt-6 text-right">
                <button type="button"
                    class="px-4 py-2 bg-gray-700 text-white rounded-md mr-2 close-modal">Batal</button>
                <button type="submit" id="submit-btn"
                    class="px-4 py-2 bg-green-700 text-white rounded-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal -->
<div id="view-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-lg p-4 sm:p-6 w-full max-w-4xl mx-auto max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-white">Detail Ranting</h3>
            <button class="text-gray-400 hover:text-white close-view-modal">
                ✖
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-1">
                <p class="text-gray-400 mb-1">Nama Ranting:</p>
                <p id="view-nama" class="text-white font-medium"></p>
            </div>
            <div class="md:col-span-1">
                <p class="text-gray-400 mb-1">Nomor SK:</p>
                <p id="view-nomor_sk" class="text-white font-medium"></p>
            </div>
            <div class="md:col-span-1">
                <p class="text-gray-400 mb-1">Pimpinan:</p>
                <p id="view-nama_pimpinan" class="text-white font-medium"></p>
            </div>
            <div class="md:col-span-1">
                <p class="text-gray-400 mb-1">Nomor Telpon:</p>
                <p id="view-no_telp" class="text-white font-medium"></p>
            </div>
            <div class="md:col-span-1">
                <p class="text-gray-400 mb-1">Cabang:</p>
                <p id="view-cabang" class="text-white font-medium"></p>
            </div>
            <div class="md:col-span-1">
                <p class="text-gray-400 mb-1">Desa:</p>
                <p id="view-desa" class="text-white font-medium"></p>
            </div>
            <div class="md:col-span-2">
                <p class="text-gray-400 mb-1">Alamat:</p>
                <p id="view-alamat" class="text-white font-medium"></p>
            </div>
            <div class="col-span-1 md:col-span-2 mt-6 text-right">
                <button type="button"
                    class="px-4 py-2 bg-gray-700 text-white rounded-md close-view-modal">Tutup</button>
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
                url: "{{ route('ranting.index') }}",
            },
            columns: [
                {
                    data: 'nama',
                    name: 'nama',
                    width: '15%',
                    className: 'font-medium'
                },
                {
                    data: 'nama_pimpinan',
                    name: 'nama_pimpinan',
                    width: '15%'
                },
                {
                    data: 'no_telp',
                    name: 'no_telp',
                    width: '10%'
                },
                {
                    data: 'desa.nama',
                    name: 'desa.nama',
                    width: '15%'
                },
                {
                    data: 'alamat',
                    name: 'alamat',
                    wdith: '30%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '30%',
                    className: 'text-center',
                    render: function (data, type, row) {
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
                // Style search input
                $('.dataTables_filter input').addClass('bg-gray-700 text-white border border-gray-600 rounded px-3 py-1 focus:outline-none focus:border-blue-500 w-64');
                $('.dataTables_filter label').addClass('flex items-center');

                // Add sort icons to column headers
                $('#dataTable thead th').each(function () {
                    if (!$(this).hasClass('no-sort')) {
                        $(this).append('<span class="ml-1 inline-block sort-icon"><i class="fas fa-sort text-gray-400 text-xs"></i></span>');
                    }
                });

                // Style table header
                $('#dataTable thead th').addClass('bg-gray-800 text-left px-4 py-3 font-semibold text-white');

                // Style table rows
                $('#dataTable tbody tr').addClass('border-b border-gray-700 hover:bg-gray-800 transition');
                $('#dataTable tbody td').addClass('px-4 py-3');

                // Style pagination
                $('.dataTables_paginate').addClass('flex items-center space-x-1');
                $('.paginate_button').addClass('px-3 py-1 bg-gray-700 text-white rounded hover:bg-gray-600 focus:outline-none transition');
                $('.paginate_button.current').removeClass('bg-gray-700').addClass('bg-blue-600 hover:bg-blue-700');
                $('.paginate_button.disabled').removeClass('hover:bg-gray-600').addClass('opacity-50 cursor-not-allowed');
            },
            drawCallback: function () {
                $('#dataTable tbody tr').each(function () {
                    $(this).find('td').each(function (index) {
                        var columnTitle = $('#dataTable thead th').eq(index).text().trim(); // Ambil teks TH
                        $(this).attr('data-label', columnTitle); // Set data-label
                    });
                });

                // Re-apply styles after draw
                $('#dataTable tbody tr').addClass('border-b border-gray-700 hover:bg-gray-800 transition');
                $('#dataTable tbody td').addClass('px-4 py-3');

                // Update sort icons
                $('#dataTable thead .sorting').find('.sort-icon i').removeClass().addClass('fas fa-sort text-gray-400 text-xs');
                $('#dataTable thead .sorting_asc').find('.sort-icon i').removeClass().addClass('fas fa-sort-up text-blue-400 text-xs');
                $('#dataTable thead .sorting_desc').find('.sort-icon i').removeClass().addClass('fas fa-sort-down text-blue-400 text-xs');

                // Re-apply pagination styles
                $('.paginate_button').addClass('px-3 py-1 bg-gray-700 text-white rounded hover:bg-gray-600 focus:outline-none transition');
                $('.paginate_button.current').removeClass('bg-gray-700').addClass('bg-blue-600 hover:bg-blue-700');
                $('.paginate_button.disabled').removeClass('hover:bg-gray-600').addClass('opacity-50 cursor-not-allowed');
            },
            order: [[0, 'asc']], // Default sorting
            responsive: true,
            autoWidth: false
        });

        // Open Add Modal
        $('#openAddModal').click(function () {
            resetForm();
            $('#modal-title').text('Tambah Ranting');
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
            $('#ranting-form')[0].reset();
            $('#ranting-id').val('');
        }

        // Submit Form
        $('#ranting-form').submit(function (e) {
            e.preventDefault();
            var id = $('#ranting-id').val();
            var url = id ? "{{ route('ranting.update', ':id') }}".replace(':id', id) : "{{ route('ranting.store') }}";
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
                        text: response.success
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

            $.get("{{ route('ranting.edit', ':id') }}".replace(':id', id), function (response) {
                $('#view-nama').text(response.nama);
                $('#view-nomor_sk').text(response.nomor_sk);
                $('#view-nama_pimpinan').text(response.nama_pimpinan);
                $('#view-no_telp').text(response.no_telp);
                $('#view-cabang').text(response.cabang.nama);
                $('#view-desa').text(response.desa.nama);
                $('#view-alamat').text(response.alamat);
                $('#view-modal').removeClass('hidden');
            }).fail(function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal memuat data ranting!'
                });
            });
        });

        // Edit
        $(document).on('click', '.edit-btn', function () {
            var id = $(this).data('id');

            $.get("{{ route('ranting.edit', ':id') }}".replace(':id', id), function (response) {
                $('#ranting-id').val(response.id);
                $('#nama').val(response.nama);
                $('#nomor_sk').val(response.nomor_sk);
                $('#nama_pimpinan').val(response.nama_pimpinan);
                $('#no_telp').val(response.no_telp);
                $('#cabang').val(response.cabang.id);
                $('#desa').val(response.desa.id);
                $('#alamat').val(response.alamat);
                $('#modal-title').val('Edit Ranting');
                $('#submit-btn').val('Update');
                $('#modal-form').removeClass('hidden');
            }).fail(function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal memuat data ranting!'
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
                        url: "{{ route('ranting.destroy', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        data: { "_token": "{{ csrf_token() }}" },
                        success: function (response) {
                            Swal.fire('Terhapus!', response.success, 'success');
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
@endsection