{{-- resources/views/admin/forms/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Daftar Form Evaluasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @vite('resources/css/app.css') {{-- jika pakai Vite/Tailwind --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50">

<div class="flex h-screen" x-data="{ sidebarOpen: true }">
    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'w-64' : 'w-16'" class="bg-white border-r transition-all duration-300 flex flex-col">
        <div class="flex items-center justify-between h-16 px-4 border-b">
            <span x-show="sidebarOpen" class="font-bold text-lg">Admin Panel</span>
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none">
                <svg x-show="!sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Menu Sidebar -->
        <nav class="flex-1 px-2 py-4 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-blue-100 hover:text-blue-700">
                <i class="fas fa-chart-line mr-3 text-blue-500"></i>
                <span x-show="sidebarOpen">Statistik & Visualisasi Data</span>
            </a>

            <a href="{{ route('forms.index') }}"
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-blue-600 hover:text-white">
                <i class="fas fa-file-alt mr-3 text-blue-600"></i>
                <span x-show="sidebarOpen">Kelola Form Evaluasi</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto p-6 max-w-6xl mx-auto">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 bg-white p-6 rounded-xl shadow-lg border-b-4 border-indigo-600">
            <h1 class="text-3xl font-extrabold text-gray-900 flex items-center">
                📊 Daftar Form Evaluasi
            </h1>

            <a href="{{ route('forms.create') }}"
               class="mt-4 md:mt-0 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg shadow-md transition duration-150 ease-in-out">
                + Buat Form Baru
            </a>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-xl shadow-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/2">
                            Judul Form
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Dibuat
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Pertanyaan
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($forms as $form)
                    <tr class="hover:bg-indigo-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-left">
                            <div class="text-sm font-medium text-gray-900">{{ $form->title }}</div>
                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ $form->description ?? 'Tidak ada deskripsi' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-gray-700">{{ $form->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $form->questions->count() }} Butir
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" type="button"
                                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Aksi
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.584l3.71-3.353a.75.75 0 111.04 1.08l-4.25 3.843a.75.75 0 01-1.04 0l-4.25-3.843a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition
                                     class="origin-top-right absolute right-0 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <a href="{{ route('forms.show', $form) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Lihat</a>
                                        <a href="{{ route('forms.results', $form) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Hasil</a>
                                        <form action="{{ route('forms.destroy', $form) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus form ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center bg-gray-50">
                            <p class="text-lg text-gray-500">
                                Belum ada form evaluasi yang tersedia.
                            </p>
                            <p class="mt-2 text-sm text-gray-400">
                                Klik tombol <strong>Buat Form Baru</strong> di atas untuk memulai.
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
