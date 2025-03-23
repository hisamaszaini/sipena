@extends('layouts.operator')

@section('content')
<div class="p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">{{ ucwords(Auth::user()->role) }} / {{ $title }}</h1>
    </div>
    <div class="bg-gray-800 p-4 rounded-lg mb-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-white">{{ $title }}</h2>
        </div>
        <form action="{{ route('cabang.update', $cabang->id) }}" method="POST" id="cabang-form" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama" class="block text-white mb-2">Nama Cabang</label>
                    <input type="text" id="nama" name="nama" value="{{ $cabang->nama }}" required
                        class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="nomor_sk" class="block text-white mb-2">Nomor SK</label>
                    <input type="text" id="nomor_sk" name="nomor_sk" value="{{ $cabang->nomor_sk }}" required
                        class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="nama_pimpinan" class="block text-white mb-2">Nama Pimpinan</label>
                    <input type="text" id="nama_pimpinan" name="nama_pimpinan" value="{{ $cabang->nama_pimpinan }}"
                        required
                        class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="no_telp" class="block text-white mb-2">Nomor Telepon</label>
                    <input type="text" id="no_telp" name="no_telp" value="{{ $cabang->no_telp }}" required
                        minlength="10" maxlength="15"
                        class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="kecamatan" class="block text-white mb-2">Kecamatan</label>
                    <select id="kecamatan" name="kecamatan_id" required disabled
                        class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                        <option value="{{ $cabang->kecamatan_id }}" selected>{{ $cabang->kecamatan->nama }}</option>
                    </select>
                    <input type="hidden" name="kecamatan_id" value="{{ $cabang->kecamatan_id }}">
                </div>
                <div>
                    <label for="daerah" class="block text-white mb-2">Daerah</label>
                    <select id="daerah" name="daerah_id" required disabled
                        class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                        <option value="{{ $cabang->daerah_id }}" selected>{{ $cabang->daerah->nama }}</option>
                    </select>
                    <input type="hidden" name="daerah_id" value="{{ $cabang->daerah_id }}">
                </div>                
            </div>
            <div class="mb-4">
                <label for="alamat" class="block text-white mb-2">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3" required
                    class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">{{ $cabang->alamat }}</textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Submit Form
    $('#cabang-form').submit(function (e) {
    e.preventDefault();
    var id = "{{ $cabang->id }}";
    $.ajax({
        url: "{{ route('cabang.update', '') }}/" + id,
            type: 'PUT',
            data: $(this).serialize(),
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.success
                });
            },
            error: function (xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '';
                if (errors) {
                    $.each(errors, function (key, value) {
                        errorMessage += value[0] + '<br>';
                    });
                } else {
                    errorMessage = 'Terjadi kesalahan saat memperbarui data!';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: errorMessage
                });
            }
        });
    });
</script>
@endsection