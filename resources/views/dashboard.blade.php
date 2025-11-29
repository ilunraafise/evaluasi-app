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
                <a href="#statistik"
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
        <div class="flex-1 overflow-auto p-8">
            {{-- Statistik Utama --}}
            <div id="statistik" class="mb-8">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Statistik Utama</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Total Form --}}
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                        <div class="flex items-center justify-between">
                            <i class="fas fa-file-alt text-3xl text-blue-500"></i>
                            <h3 class="text-4xl font-extrabold text-gray-800">{{ \App\Models\Form::count() }}</h3>
                        </div>
                        <p class="text-lg text-gray-600 mt-2">Total Form</p>
                    </div>

                    {{-- Total Pertanyaan --}}
                    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                        <div class="flex items-center justify-between">
                            <i class="fas fa-question-circle text-3xl text-green-500"></i>
                            <h3 class="text-4xl font-extrabold text-gray-800">{{ \App\Models\Question::count() }}</h3>
                        </div>
                        <p class="text-lg text-gray-600 mt-2">Total Pertanyaan</p>
                    </div>

                    {{-- Responden Submit --}}
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                        @php
                            $latestForm = \App\Models\Form::latest()->first();
                            $respondentCount = $latestForm ? $latestForm->answers()->distinct('respondent_id')->count('respondent_id') : 0;
                        @endphp
                        <div class="flex items-center justify-between">
                            <i class="fas fa-users text-3xl text-yellow-500"></i>
                            <h3 class="text-4xl font-extrabold text-gray-800">{{ $respondentCount }}</h3>
                        </div>
                        <p class="text-lg text-gray-600 mt-2">Responden Submit</p>
                    </div>
                </div>
            </div>

            {{-- Visualisasi Data --}}
            <div class="mb-12">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Visualisasi Data</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Pie Chart Jawaban --}}
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                            <i class="fas fa-chart-pie mr-2 text-red-500"></i> Distribusi Jawaban
                        </h3>

                        @php
                            $answers = $latestForm ? \App\Models\Answer::where('form_id', $latestForm->id)->get() : collect([]);
                            $answerCounts = [];

                            foreach($answers as $ans) {
                                $a = is_array(json_decode($ans->answer, true)) 
                                        ? json_decode($ans->answer, true) 
                                        : [$ans->answer];

                                foreach($a as $item) {
                                    if(!isset($answerCounts[$item])) $answerCounts[$item] = 0;
                                    $answerCounts[$item]++;
                                }
                            }
                        @endphp

                        @if(count($answerCounts))
                            <div class="w-full h-64 md:h-64 flex items-center justify-center">
                                <canvas id="answersPieChart" class="max-h-full"></canvas>
                            </div>
                        @else
                            <p class="text-gray-500 italic text-sm p-4 bg-gray-50 rounded-md text-center">
                                Belum ada jawaban yang bisa ditampilkan untuk form terbaru.
                            </p>
                        @endif
                    </div>

                    {{-- Bar Chart Responden --}}
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                            <i class="fas fa-chart-bar mr-2 text-indigo-500"></i> Jumlah Responden
                        </h3>
                        <div class="w-full h-64 md:h-64 flex items-center justify-center">
                             <canvas id="respondentBarChart" class="max-h-full"></canvas>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if(count($answerCounts))
            new Chart(document.getElementById('answersPieChart'), {
                type: 'pie',
                data: {
                    labels: @json(array_keys($answerCounts)),
                    datasets: [{
                        data: @json(array_values($answerCounts)),
                        backgroundColor: [
                            '#3B82F6', '#EF4444', '#FACC15', '#10B981',
                            '#8B5CF6', '#F472B6', '#F97316'
                        ],
                        hoverOffset: 4,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed;
                                }
                            }
                        }
                    }
                }
            });
        @endif

        new Chart(document.getElementById('respondentBarChart'), {
            type: 'bar',
            data: {
                labels: ['Total Responden'],
                datasets: [{
                    label: 'Jumlah Submit',
                    data: [{{ $respondentCount }}],
                    backgroundColor: '#4C51BF',
                    borderColor: '#4338CA',
                    borderWidth: 1,
                    borderRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Total Responden',
                        padding: { top: 10, bottom: 30 }
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        precision: 0,
                        title: { display: true, text: 'Jumlah' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</x-app-layout>
