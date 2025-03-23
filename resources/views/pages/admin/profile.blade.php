@extends('layouts.admin', ['title' => 'Edit Profile'])

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">{{ ucwords(Auth::user()->role) }} / {{ $title }}</h1>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Form Update Profil --}}
        <div class="bg-gray-800 shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-semibold text-white mb-4">Informasi Profil</h2>
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-white mb-2">Nama</label>
                    <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required
                        class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="username" class="block text-white mb-2">Username</label>
                    <input type="text" id="username" name="username" value="{{ Auth::user()->username }}" required
                        class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="email" class="block text-white mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required
                        class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 focus:outline-none">Simpan</button>
                </div>
            </form>
        </div>

        {{-- Form Update Password --}}
        <div class="bg-gray-800 shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-semibold text-white mb-4">Perbarui Password</h2>
            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-white mb-2">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" required
                        class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="new_password" class="block text-white mb-2">Password Baru</label>
                    <input type="password" id="new_password" name="password" required
                        class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-white mb-2">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg border border-gray-600 focus:border-blue-500 focus:outline-none">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 focus:outline-none">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function handleFormSubmit(form, successMessage) {
        event.preventDefault();
        let formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: successMessage,
                    confirmButtonText: 'OK'
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Terjadi kesalahan!',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan!',
                confirmButtonText: 'OK'
            });
        });
    }

    document.querySelector('form[action="{{ route('profile.update') }}"]').addEventListener('submit', function (e) {
        handleFormSubmit(this, 'Profil berhasil diperbarui.');
    });

    document.querySelector('form[action="{{ route('password.update') }}"]').addEventListener('submit', function (e) {
        handleFormSubmit(this, 'Password berhasil diperbarui.');
    });
</script>
@endsection
