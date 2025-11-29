{{-- resources/views/admin/forms/show.blade.php --}}
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
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-blue-600 hover:text-white">
                    <i class="fas fa-file-alt mr-3 text-blue-600"></i>
                    <span x-show="sidebarOpen">Kelola Form Evaluasi</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto p-6">
            <div class="max-w-4xl mx-auto space-y-6">

                {{-- Header --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-indigo-600">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-2">
                        📊 Detail Form Evaluasi — {{ $form->title }}
                    </h2>
                </div>

                {{-- Informasi Form --}}
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h1 class="text-xl md:text-2xl font-semibold text-gray-900">{{ $form->title }}</h1>
                    <p class="text-gray-600 mt-1">{{ $form->description ?? 'Tidak ada deskripsi' }}</p>
                </div>

                {{-- Import Excel --}}
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold mb-4">Import Pertanyaan dari Excel</h3>
                    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                        <form action="{{ route('forms.questions.import', $form) }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                            @csrf
                            <input type="file" name="excel_file" accept=".xlsx,.xls" required
                                class="border rounded p-2 w-full md:w-auto">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 w-full md:w-auto">
                                📥 Import Pertanyaan
                            </button>
                        </form>

                        <a href="{{ asset('templates/questions_template.xlsx') }}" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow w-full md:w-auto text-center"
                        download>
                            📄 Download Template Excel
                        </a>
                    </div>
                    <p class="text-gray-500 text-sm mt-2">
                        Format Excel: kolom pertama = Pertanyaan, kolom berikutnya = Opsi Jawaban (opsional).
                    </p>
                </div>

                {{-- Daftar Pertanyaan --}}
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Daftar Pertanyaan</h3>
                        <a href="{{ route('questions.create', $form) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow mt-2 md:mt-0">
                            + Tambah Pertanyaan
                        </a>
                    </div>

                    @if($form->questions->count() > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach ($form->questions as $i => $question)
                                <li class="py-3 flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div class="mb-2 md:mb-0">
                                        <p class="font-medium text-gray-800">{{ $i+1 }} — {{ $question->question }}</p>
                                    </div>
                                    <form action="{{ route('forms.questions.destroy', [$form, $question]) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 text-sm font-semibold">
                                            Hapus
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 text-sm italic">Belum ada pertanyaan ditambahkan.</p>
                    @endif
                </div>

                {{-- Tombol Lihat Link Form --}}
                <div class="text-center">
                    <a href="{{ url('/form/'.$form->id.'/take') }}"
                    class="text-blue-600 font-semibold hover:underline">
                    🔗 Copy Link Form untuk dibagikan ke peserta
                    </a>
                </div>

            </div>
        </div>

    </div>

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-app-layout>
