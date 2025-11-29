{{-- resources/views/admin/forms/create.blade.php --}}
<x-app-layout>
    <div class="flex h-screen bg-gray-50" x-data="{ sidebarOpen: true }">

        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'w-64' : 'w-16'" class="bg-white border-r transition-all duration-300 flex flex-col">
            <!-- Logo / Header Sidebar -->
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
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700">
                    <i class="fas fa-file-alt mr-3 text-white"></i>
                    <span x-show="sidebarOpen">Kelola Form Evaluasi</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto p-6 max-w-3xl mx-auto">

            {{-- Header --}}
            <div class="mb-6 bg-white p-6 rounded-xl shadow-lg border-b-4 border-indigo-600">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    📝 Buat Form Evaluasi Baru
                </h1>
            </div>

            {{-- Form --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <form action="{{ route('forms.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Judul Form</label>
                        <input type="text" name="title"
                               class="w-full border border-gray-300 p-2 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                               required>
                    </div>

                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Deskripsi (opsional)</label>
                        <textarea name="description"
                                  class="w-full border border-gray-300 p-2 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                  rows="4"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg shadow-md transition duration-150 ease-in-out">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-app-layout>
