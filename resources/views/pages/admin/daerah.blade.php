@extends('layouts.admin')

@section('content')
<div class="p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">{{ ucwords(Auth::user()->role) }} / {{ $title }}</h1>
    </div>
    <div class="bg-gray-800 p-4 rounded-lg mb-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-white">{{ $title }}</h2>
        </div>
        <form action="{{ route('daerah.update') }}" method="POST" id="daerah-form" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama" class="block text-white mb-2">Nama Daerah</label>
                    <input type="text" id="nama" name="nama" value="{{ $daerah->nama }}" required
                        class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="nomor_sk" class="block text-white mb-2">Nomor SK</label>
                    <input type="text" id="nomor_sk" name="nomor_sk" value="{{ $daerah->nomor_sk }}" required
                        class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="nama_pimpinan" class="block text-white mb-2">Nama Pimpinan</label>
                    <input type="text" id="nama_pimpinan" name="nama_pimpinan" value="{{ $daerah->nama_pimpinan }}"
                        required
                        class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="no_telp" class="block text-white mb-2">Nomor Telepon</label>
                    <input type="text" id="no_telp" name="no_telp" value="{{ $daerah->no_telp }}" required
                        minlength="10" maxlength="15"
                        class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="provinsi" class="block text-white mb-2">Provinsi</label>
                    <select id="provinsi" name="provinsi_id" required
                        class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                        <option value=\"\">Pilih Provinsi</option>
                        @foreach($provinsi as $prov)
                        <option value="{{ $prov->id }}" {{ $daerah->provinsi_id == $prov->id ? 'selected' : '' }}>
                            {{ $prov->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="kabupaten" class="block text-white mb-2">Kabupaten</label>
                    <select id="kabupaten" name="kabupaten_id" required
                        class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">
                        <option value=\"\">Pilih Kabupaten</option>
                        @foreach($kabupaten as $kab)
                        <option value="{{ $kab->id }}" {{ $daerah->kabupaten_id == $kab->id ? 'selected' : '' }}>
                            {{ $kab->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label for="alamat" class="block text-white mb-2">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3" required
                    class="w-full px-3 py-2 bg-gray-700 text-white rounded-md border border-gray-600 focus:border-blue-500 focus:outline-none">{{ $daerah->alamat }}</textarea>
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
    document.getElementById('provinsi').addEventListener('change', function () {
        let provinsiId = this.value;
        fetch(`/api/kabupaten?provinsi_id=${provinsiId}`)
            .then(response => response.json())
            .then(data => {
                let kabupatenSelect = document.getElementById('kabupaten');
                kabupatenSelect.innerHTML = '<option value=\"\">Pilih Kabupaten</option>';
                data.forEach(kab => {
                    kabupatenSelect.innerHTML += `<option value=\"${kab.id}\">${kab.nama}</option>`;
                });
            });
    });

    // Submit Form
    $('#daerah-form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('daerah.update') }}",
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