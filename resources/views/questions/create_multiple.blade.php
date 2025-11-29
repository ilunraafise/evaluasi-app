<x-app-layout>
    <div class="flex h-screen bg-gray-50" x-data="{ sidebarOpen: true }">

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
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-blue-100 hover:text-blue-700">
                    <i class="fas fa-chart-line mr-3 text-blue-500 w-5 text-center"></i>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>

                <a href="{{ route('forms.index') }}"
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-blue-600 hover:text-white">
                    <i class="fas fa-file-alt mr-3 text-blue-600 w-5 text-center"></i>
                    <span x-show="sidebarOpen">Kelola Form Evaluasi</span>
                </a>

            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto p-6">
            <div class="max-w-6xl mx-auto space-y-6">

                {{-- Header --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 bg-white p-6 rounded-xl shadow-lg border-b-4 border-indigo-600">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                        Tambah Banyak Pertanyaan — {{ $form->title }}
                    </h1>
                    <a href="{{ route('forms.index') }}"
                       class="mt-4 md:mt-0 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow text-sm">
                        ← Kembali ke Daftar Form
                    </a>
                </div>

                {{-- Form Card --}}
                <div class="bg-white p-6 rounded-xl shadow-md space-y-4">

                    <form action="{{ route('forms.questions.store', $form) }}" method="POST">
                        @csrf

                        <div id="questions-wrapper" class="space-y-4">
                            <div class="question-item border p-4 rounded space-y-2" data-index="0">
                                <label class="font-semibold">Pertanyaan:</label>
                                <input type="text" name="questions[0][question]" class="border rounded w-full p-2" required>
                                <input type="hidden" name="questions[0][type]" value="radio">

                                <label class="font-semibold">Opsi Jawaban:</label>
                                <div class="options-list space-y-2"></div>

                                <div class="flex flex-wrap gap-2 mt-2">
                                    <button type="button" onclick="addOption(this)" class="bg-gray-700 text-white px-2 py-1 rounded hover:bg-gray-800">
                                        + Tambah Opsi
                                    </button>
                                    <button type="button" onclick="addDefaultSSOptions(this)" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                        SS / S / TS / STS
                                    </button>
                                    <button type="button" onclick="removeQuestion(this)" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                        Hapus Pertanyaan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-col md:flex-row gap-4">
                            <button type="button" onclick="addQuestion()" class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 w-full md:w-auto">
                                + Tambah Pertanyaan Baru
                            </button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full md:w-auto">
                                Simpan Semua Pertanyaan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>

    {{-- Scripts --}}
    <script>
        let questionIndex = 1;

        function addOption(btn, value = '') {
            const optionsList = btn.closest('.question-item').querySelector('.options-list');
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-center';

            const idx = btn.closest('.question-item').dataset.index;

            div.innerHTML = `
                <input type="text" name="questions[${idx}][options][]" placeholder="Isi opsi jawaban"
                       class="border rounded w-full p-2" value="${value}" required>
                <button type="button" onclick="this.parentElement.remove()"
                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">-</button>
            `;
            optionsList.appendChild(div);
        }

        function addDefaultSSOptions(btn) {
            const defaultOptions = ['SS', 'S', 'TS', 'STS'];
            const defaultTexts = ['Sangat Setuju', 'Setuju', 'Tidak Setuju', 'Sangat Tidak Setuju'];

            defaultOptions.forEach((opt, idx) => {
                addOption(btn, `${opt} = ${defaultTexts[idx]}`);
            });
        }

        function addQuestion() {
            const wrapper = document.getElementById('questions-wrapper');
            const div = document.createElement('div');
            div.className = 'question-item border p-4 rounded space-y-2';
            div.dataset.index = questionIndex;

            div.innerHTML = `
                <label class="font-semibold">Pertanyaan:</label>
                <input type="text" name="questions[${questionIndex}][question]" class="border rounded w-full p-2" required>
                <input type="hidden" name="questions[${questionIndex}][type]" value="radio">

                <label class="font-semibold">Opsi Jawaban:</label>
                <div class="options-list space-y-2"></div>

                <div class="flex flex-wrap gap-2 mt-2">
                    <button type="button" onclick="addOption(this)" class="bg-gray-700 text-white px-2 py-1 rounded hover:bg-gray-800">
                        + Tambah Opsi
                    </button>
                    <button type="button" onclick="addDefaultSSOptions(this)" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                        SS / S / TS / STS
                    </button>
                    <button type="button" onclick="removeQuestion(this)" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                        Hapus
                    </button>
                </div>
            `;

            wrapper.appendChild(div);
            questionIndex++;
        }

        function removeQuestion(btn) {
            btn.closest('.question-item').remove();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.question-item').forEach(q => addOption(q.querySelector('button')));
        });
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</x-app-layout>
