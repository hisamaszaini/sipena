<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Admin Panel') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTable.css') }}">
    <!-- Scripts -->
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
    <style>
        /* Transisi untuk animasi dropdown */
        [x-cloak] {
            display: none !important;
        }

        .dropdown-content {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease-in-out;
        }

        .dropdown-icon {
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-gradasi text-white">
    <!-- Hamburger button -->
    <div id="hamburgerWrapper" class="hamburger-wrapper sidebar-collapsed">
        <button id="toggleSidebar" class="text-white w-8 h-8 flex items-center justify-center">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>

    <!-- Profile Menu Icon -->
    <div id="profileMenuContainer" class="profile-menu-container sidebar-collapsed">
        <button id="profileMenuButton"
            class="w-10 h-10 rounded-full bg-biru flex items-center justify-center hover:bg-blue-700 transition-colors">
            <i class="fas fa-user"></i>
        </button>
        <div id="profileMenu" class="profile-menu">
            <div class="py-2 px-4 border-b border-gray-700">
                <p class="font-medium">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
            </div>
            <ul>
                <li>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-700 transition-colors">
                        <i class="fas fa-user-circle mr-2"></i> My Profile
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                            class="block px-4 py-2 text-red-400 hover:bg-gray-700 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                </li>
                </form>
            </ul>
        </div>
    </div>

    <div class="flex flex-col min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar bg-gray-800 collapsed">
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center">
                    <a href="/dashboard#"><img src="{{ asset('images/logo.png') }}" alt="Logo"></a>
                </div>
            </div>

            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-biru rounded-md flex items-center justify-center text-xs">CH</div>
                    <div class="ml-3">
                        <div class="flex items-center">
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                        <div class="text-xs text-gray-400">{{ Auth::user()->role }}</div>
                    </div>
                </div>
            </div>
            <nav class="mt-4 flex-1 overflow-y-auto">
                <div class="px-4 py-2 text-xs font-medium text-white uppercase">Pages</div>
                <div class="mt-2">
                    <a href="dashboard"
                        class="flex items-center px-4 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                        <i class="fas fa-th-large mr-3 text-blue-500 hover:text-blue-300 w-[20px]"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                </div>
            </nav>
            <div class="px-4 py-2 text-xs font-medium text-white uppercase mt-1">Mastering Data</div>
            <div class="mt-2">
                <div x-data="{ open: false }">
                    <div @click="open = !open; $nextTick(() => { $refs.submenu.style.maxHeight = open ? $refs.submenu.scrollHeight + 'px' : '0px' })" class="flex items-center px-4 py-2 hover:bg-gray-700 transition duration-200 ease-in-out cursor-pointer">
                        <i class="fas fa-user-shield mr-3 text-purple-600 hover:text-red-300 w-[20px]"></i>
                        <span class="font-medium">Kelola Pengguna</span>
                        <i class="fas fa-chevron-down ml-auto dropdown-icon" :class="{'transform rotate-180': open}"></i>
                    </div>
                    <div x-ref="submenu" class="dropdown-content bg-gray-800" style="max-height: 0px;">
                        <a href="{{ route('admin.index') }}" class="flex items-center pl-10 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                            <i class="fas fa-user-tie mr-3 text-blue-400 hover:text-blue-300 w-[20px]"></i>
                            <span class="font-medium">Data Admin</span>
                        </a>
                        <a href="{{ route('operator.index') }}" class="flex items-center pl-10 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                            <i class="fas fa-user-cog mr-3 text-teal-500 hover:text-blue-300 w-[20px]"></i>
                            <span class="font-medium">Data Operator</span>
                        </a>
                    </div>
                </div>
                <div x-data="{ open: false }">
                    <div @click="open = !open; $nextTick(() => { $refs.submenu.style.maxHeight = open ? $refs.submenu.scrollHeight + 'px' : '0px' })"
                        class="flex items-center px-4 py-2 hover:bg-gray-700 transition duration-200 ease-in-out cursor-pointer">
                        <i class="fas fa-map mr-3 text-green-600 hover:text-red-300 w-[20px]"></i>
                        <span class="font-medium">Data Wilayah</span>
                        <i class="fas fa-chevron-down ml-auto dropdown-icon"
                            :class="{'transform rotate-180': open}"></i>
                    </div>
                    <div x-ref="submenu" class="dropdown-content bg-gray-800">
                        <a href="{{ route('kecamatan.index') }}"
                            class="flex items-center pl-10 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                            <i class="fas fa-landmark mr-3 text-green-500 hover:text-green-300 w-[20px]"></i>
                            <span class="font-medium">Data Kecamatan</span>
                        </a>
                        <a href="{{ route('desa.index') }}"
                            class="flex items-center pl-10 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                            <i class="fas fa-home mr-3 text-green-500 hover:text-green-300 w-[20px]"></i>
                            <span class="font-medium">Data Desa</span>
                        </a>
                    </div>
                </div>
                <a href="{{ route('daerah.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                    <i class="fas fa-map-marked-alt mr-3 text-purple-500 hover:text-purple-300 w-[20px]"></i>
                    <span class="font-medium">Data Daerah</span>
                </a>
                <a href="{{ route('cabang.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                    <i class="fas fa-code-branch mr-3 text-green-500 hover:text-green-300 w-[20px]"></i>
                    <span class="font-medium">Data Cabang</span>
                </a>
                <a href="{{ route('ranting.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                    <i class="fas fa-tree mr-3 text-yellow-500 hover:text-yellow-300 w-[20px]"></i>
                    <span class="font-medium">Data Ranting</span>
                </a>
                <div x-data="{ open: false }">
                    <div @click="open = !open; $nextTick(() => { $refs.submenu.style.maxHeight = open ? $refs.submenu.scrollHeight + 'px' : '0px' })"
                        class="flex items-center px-4 py-2 hover:bg-gray-700 transition duration-200 ease-in-out cursor-pointer">
                        <i class="fas fa-users mr-3 text-red-500 hover:text-red-300 w-[20px]"></i>
                        <span class="font-medium">Data Anggota</span>
                        <i class="fas fa-chevron-down ml-auto dropdown-icon" :class="{'transform rotate-180': open}"></i>
                    </div>
                    <div x-ref="submenu" class="dropdown-content bg-gray-800">
                        <a href="{{ route('anggotadaerah.index') }}" class="flex items-center pl-10 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                            <i class="fas fa-user-friends mr-3 text-purple-500 hover:text-purple-300 w-[20px]"></i>
                            <span class="font-medium">Anggota Daerah</span>
                        </a>
                        <a href="{{ route('anggotacabang.index') }}" class="flex items-center pl-10 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                            <i class="fas fa-user-check mr-3 text-blue-400 hover:text-blue-200 w-[20px]"></i>
                            <span class="font-medium">Anggota Cabang</span>
                        </a>
                        <a href="{{ route('anggotaranting.index') }}" class="flex items-center pl-10 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                            <i class="fas fa-user-graduate mr-3 text-green-400 hover:text-green-200 w-[20px]"></i>
                            <span class="font-medium">Anggota Ranting</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="px-4 py-2 text-xs font-medium text-white uppercase mt-1">Akun</div>
            <div class="mt-2">
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                    <i class="fas fa-cog mr-3 text-blue-500 hover:text-blue-300 w-[20px]"></i>
                    <span class="font-medium">Pengaturan Akun</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center px-4 py-2 hover:bg-gray-700 transition duration-200 ease-in-out">
                        <i class="fas fa-sign-out-alt mr-3 text-red-500 hover:text-red-300 w-[20px]"></i>
                        <span class="font-medium">Logout</span>
                    </a>
                </form>
            </div>            
        </aside>
        <main id="mainContent" class="main-content sidebar-collapsed flex-1 min-h-screen pt-16">
            @yield('content')
        </main>
        <footer id="footer" class="bg-gray-800 p-4 text-center text-sm text-gray-400">
            <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
                <div class="text-slate-300">
                    On Development!
                </div>
            </div>
        </footer>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/navigation.js') }}"></script>
    @yield('scripts')
</body>

</html>