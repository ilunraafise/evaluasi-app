<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Tambah Banyak Pertanyaan — {{ $form->title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
            <form action="{{ route('forms.questions.store', $form) }}" method="POST">
                @csrf

                <div id="questions-wrapper" class="space-y-4">
                    {{-- Pertanyaan pertama default --}}
                    <div class="question-item border p-4 rounded space-y-2">
                        <label class="font-semibold">Pertanyaan:</label>
                        <input type="text" name="questions[0][question]" class="border rounded w-full p-2" required>

                        <input type="hidden" name="questions[0][type]" value="radio">

                        <label class="font-semibold">Opsi Jawaban:</label>
                        <div class="options-list space-y-2">
                            <!-- Opsi akan ditambah disini -->
                        </div>

                        <div class="flex gap-2 mt-2">
                            <button type="button" onclick="addOption(this)" class="bg-gray-700 text-white px-2 py-1 rounded">
                                + Tambah Opsi
                            </button>
                            <button type="button" onclick="addDefaultSSOptions(this)" class="bg-blue-600 text-white px-2 py-1 rounded">
                                SS / S / TS / STS
                            </button>
                            <button type="button" onclick="removeQuestion(this)" class="bg-red-500 text-white px-2 py-1 rounded">
                                Hapus Pertanyaan
                            </button>
                        </div>
                    </div>
                </div>


                <button type="button" onclick="addQuestion()" class="bg-green-600 text-white px-3 py-1 rounded mt-4">
                    + Tambah Pertanyaan Baru
                </button>

                <button class="bg-blue-600 text-white mt-5 px-4 py-2 rounded">Simpan Semua Pertanyaan</button>
            </form>
        </div>
    </div>

    <script>
        let questionIndex = 1;

        function addOption(btn, value = '') {
            const optionsList = btn.closest('.question-item').querySelector('.options-list');
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-center';

            div.innerHTML = `
                <input type="text" name="questions[${btn.closest('.question-item').dataset.index}][options][]" 
                       placeholder="Isi opsi jawaban" class="border rounded w-full p-2" value="${value}" required>
                <button type="button" onclick="this.parentElement.remove()" 
                        class="px-3 py-1 bg-red-500 text-white rounded">-</button>
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

                <div class="flex gap-2 mt-2">
                    <button type="button" onclick="addOption(this)" class="bg-gray-700 text-white px-2 py-1 rounded">
                        + Tambah Opsi
                    </button>
                    <button type="button" onclick="addDefaultSSOptions(this)" class="bg-blue-600 text-white px-2 py-1 rounded">
                        SS / S / TS / STS
                    </button>
                    <button type="button" onclick="removeQuestion(this)" class="bg-red-500 text-white px-2 py-1 rounded">
                        Hapus Pertanyaan
                    </button>
                </div>
            `;

            wrapper.appendChild(div);
            questionIndex++;
        }

        function removeQuestion(btn) {
            btn.closest('.question-item').remove();
        }

        // Tambah 1 opsi kosong default saat halaman load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.question-item').forEach(q => addOption(q.querySelector('button')));
        });
    </script>
</x-app-layout>
