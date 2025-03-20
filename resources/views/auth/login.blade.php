<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    input:focus, button:hover {
      transition: all 0.3s ease;
    }
  </style>
</head>
<body class="bg-gray-900 flex justify-center items-center h-screen">
  <div class="max-w-4xl w-full bg-gray-800 shadow-lg rounded-lg overflow-hidden flex">
    <div class="w-1/2 bg-cover bg-center hidden md:block" style="background-image: url('https://source.unsplash.com/random/600x800');"></div>
    
    <div class="w-full md:w-1/2 p-8">
      <h2 class="text-2xl font-semibold text-white text-center">Login</h2>
      
      <form method="POST" action="{{ route('login') }}" class="mt-6">
        @csrf

        <!-- Input Login: Email atau Username -->
        <div>
          <label for="login" class="text-white block text-sm font-medium">
            {{ __('Email or Username') }}
          </label>
          <input 
            type="text" 
            id="login" 
            name="login" 
            value="{{ old('login') }}" 
            required autofocus 
            class="mt-1 block w-full p-3 border border-gray-700 rounded-lg bg-gray-700 text-white focus:outline-none focus:border-blue-500 @error('login') border-red-500 @enderror"
          >
          @error('login')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
          @enderror
        </div>

        <!-- Input Password -->
        <div class="mt-4">
          <label for="password" class="text-white block text-sm font-medium">
            {{ __('Password') }}
          </label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            required 
            class="mt-1 block w-full p-3 border border-gray-700 rounded-lg bg-gray-700 text-white focus:outline-none focus:border-blue-500 @error('password') border-red-500 @enderror"
          >
          @error('password')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
          @enderror
        </div>

        <!-- Remember Me -->
        <div class="mt-4 flex items-center">
          <input 
            type="checkbox" 
            id="remember" 
            name="remember" 
            class="h-4 w-4 text-blue-500 focus:ring-blue-400 border-gray-300 rounded" 
            {{ old('remember') ? 'checked' : '' }}>
          <label for="remember" class="ml-2 block text-sm text-white">
            {{ __('Remember Me') }}
          </label>
        </div>

        <!-- Tombol Login -->
        <button 
          type="submit" 
          class="mt-6 w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg focus:outline-none focus:shadow-outline"
        >
          {{ __('Login') }}
        </button>

        <!-- Link Lupa Password -->
        @if (Route::has('password.request'))
          <div class="mt-4 text-center">
            <a class="text-sm text-blue-400 hover:text-blue-600" href="{{ route('password.request') }}">
              {{ __('Forgot Your Password?') }}
            </a>
          </div>
        @endif

      </form>
    </div>
  </div>
</body>
</html>
