<x-app-layout>
    
    {{-- Background Halaman dan Container Utama --}}
    <div class="py-8 sm:py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- 🏆 Header Hasil Evaluasi --}}
            <div class="bg-white p-6 sm:p-8 shadow-2xl rounded-xl border-l-8 border-indigo-600 transition duration-300">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 flex items-center">
                    <i class="fas fa-chart-bar mr-3 text-indigo-600 text-3xl"></i> 
                    Hasil Evaluasi: <span class="ml-2 text-indigo-700 truncate">{{ $form->title }}</span>
                </h1>
                <p class="text-gray-500 mt-2 text-base sm:text-lg">Ringkasan dan detail jawaban dari <span class="font-bold text-indigo-600">{{ $answers->pluck('respondent')->unique('id')->count() }}</span> responden.</p>
            </div>
            
            <hr class="border-gray-200">

            @if($answers->isEmpty())
                <div class="p-8 bg-white rounded-xl shadow-lg text-center border-t-4 border-yellow-500">
                    <p class="text-gray-500 italic text-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-circle mr-3 text-yellow-500"></i> Belum ada peserta yang mengisi form ini.
                    </p>
                </div>
            @else
                @php
                    // Logika PHP/Blade tetap sama
                    $questions = $form->questions()->get();
                    $respondents = $answers->pluck('respondent')->unique('id');
                @endphp

                <h2 class="text-2xl font-bold text-gray-800 pt-4">Data Responden Detail</h2>

                
                {{-- Tampilan Desktop (Tabel) --}}
                <div class="hidden lg:block bg-white shadow-xl rounded-xl overflow-hidden p-6 border border-gray-200">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 border-b pb-4">
                        <p class="text-sm text-gray-500 mb-2 sm:mb-0">Tabel berisi {{ $respondents->count() }} data responden.</p>
                        
                        {{-- 🔽 Tombol Export Excel (Dipindahkan ke sini) --}}
                        <a href="{{ route('export.hasil', $form->id) }}"
                        class="w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg shadow-md shadow-green-400/50 hover:from-green-600 hover:to-emerald-700 transition duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-file-excel text-white text-lg"></i>
                            Download Excel
                        </a>
                    </div>

                    {{-- Kontainer Pencarian DataTables --}}
                    <div class="mb-4">
                        <label for="search_input" class="sr-only">Cari Data</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="search_input" class="block w-full p-3 pl-10 text-sm border border-gray-300 rounded-xl bg-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" placeholder="Cari Nama, Sekolah, Wilayah, atau Jawaban...">
                        </div>
                    </div>

                    <div class="overflow-x-auto max-h-[70vh] relative"> 
                        {{-- ID 'evaluationTable' DIPERLUKAN OLEH JAVASCRIPT DATATABLES --}}
                        <table id="evaluationTable" class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                            
                            {{-- Header Tabel (Sticky) --}}
                            <thead class="bg-indigo-600 sticky top-0 z-20 shadow-md">
                                <tr>
                                    {{-- Kolom No. Urut (Perlu class 'no-sort' agar DataTables tidak mengurutkan kolom ini) --}}
                                    <th class="px-6 py-3 text-center text-xs font-extrabold uppercase tracking-wider text-white border-r border-indigo-700 no-sort">No</th>
                                    
                                    <th class="px-6 py-3 text-left text-xs font-extrabold uppercase tracking-wider text-white border-r border-indigo-700 min-w-[180px]">Nama/Sekolah</th>
                                    <th class="px-6 py-3 text-left text-xs font-extrabold uppercase tracking-wider text-white border-r border-indigo-700 min-w-[120px]">Jenjang/Wilayah</th>
                                    <th class="px-6 py-3 text-center text-xs font-extrabold uppercase tracking-wider text-white min-w-[80px]">Batch</th>
                                    
                                    {{-- Kolom Pertanyaan --}}
                                    @foreach($questions as $q)
                                        <th class="px-6 py-3 text-left text-xs font-extrabold uppercase tracking-wider text-gray-800 border-l border-white/50 min-w-[250px] bg-yellow-400 transition duration-150">
                                            {{ $q->question }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            {{-- Isi Tabel. DataTables akan mengurus nomor urut --}}
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($respondents as $index => $res)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-indigo-50' }} hover:bg-indigo-100 transition duration-150">
                                        {{-- Kolom Nomor Urut Kosong, akan diisi oleh DataTables --}}
                                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200"></td> 
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                            <div class="font-bold text-gray-900">{{ $res->name ?? 'Anonymous' }}</div>
                                            <div class="text-xs text-gray-500 truncate">{{ $res->origin_school }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                            <div class="font-medium text-gray-800">{{ $res->level }}</div>
                                            <div class="text-xs text-gray-500">{{ $res->region }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm font-medium text-indigo-600">{{ $res->batch }}</td>
                                        
                                        {{-- Kolom Jawaban --}}
                                        @foreach($questions as $q)
                                            @php
                                                $ans = $answers->where('question_id', $q->id)
                                                                ->where('respondent_id', $res->id)
                                                                ->first();
                                                
                                                $displayAnswer = 'N/A';
                                                if($ans) {
                                                    if($q->type === 'checkbox') {
                                                        $items = json_decode($ans->answer, true) ?? [];
                                                        $displayAnswer = implode(', ', $items);
                                                    } else {
                                                        $displayAnswer = $ans->answer;
                                                    }
                                                }
                                            @endphp
                                            <td class="px-6 py-4 text-sm text-gray-800 border-l border-gray-100 max-w-[250px] overflow-hidden whitespace-normal align-top" title="{{ $displayAnswer }}">
                                                {{-- Menggunakan line-clamp untuk membatasi tinggi baris, tapi tooltip tetap menunjukkan jawaban lengkap --}}
                                                <span class="line-clamp-3 text-xs sm:text-sm">{{ $displayAnswer }}</span>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                {{-- Tampilan Mobile (Card) - Tetap Rapi --}}
                <div class="lg:hidden space-y-6 pt-4">
                    {{-- 🔽 Tombol Export Excel (Muncul di Mobile juga) --}}
                    <div class="flex justify-center mb-4">
                        <a href="{{ route('export.hasil', $form->id) }}"
                        class="w-full px-5 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg shadow-md shadow-green-400/50 hover:from-green-600 hover:to-emerald-700 transition duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-file-excel text-white text-lg"></i>
                            Download Excel
                        </a>
                    </div>

                    @foreach($respondents as $index => $res)
                        {{-- Warna border yang bergantian --}}
                        @php
                            $borderColor = [
                                'border-indigo-500',
                                'border-green-500',
                                'border-purple-500',
                                'border-yellow-500'
                            ];
                            $currentBorder = $borderColor[$index % count($borderColor)];
                        @endphp

                        <div class="bg-white p-5 rounded-xl shadow-lg border-l-8 {{ $currentBorder }} transition duration-300 hover:shadow-xl">
                            <div class="flex justify-between items-start mb-4 border-b pb-3 border-gray-100">
                                <div>
                                    <p class="text-xs font-semibold uppercase text-gray-500">Responden #{{ $index + 1 }}</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $res->name ?? 'Anonymous' }}</p>
                                </div>
                                <span class="bg-indigo-100 text-indigo-700 text-xs font-extrabold px-3 py-1 rounded-full shadow-md">{{ $res->batch }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-y-3 text-sm mb-4">
                                <div class="text-gray-600 flex items-center"><i class="fas fa-school mr-2 text-indigo-500"></i> Sekolah:</div>
                                <div class="font-semibold text-right text-gray-800 truncate">{{ $res->origin_school }}</div>
                                
                                <div class="text-gray-600 flex items-center"><i class="fas fa-graduation-cap mr-2 text-indigo-500"></i> Jenjang:</div>
                                <div class="font-semibold text-right text-gray-800">{{ $res->level }}</div>
                                
                                <div class="text-gray-600 flex items-center"><i class="fas fa-map-marker-alt mr-2 text-indigo-500"></i> Wilayah:</div>
                                <div class="font-semibold text-right text-gray-800">{{ $res->region }}</div>
                            </div>
                            
                            <hr class="my-4 border-gray-200">

                            {{-- Jawaban per Pertanyaan --}}
                            <div class="space-y-4">
                                <p class="text-md font-bold text-indigo-600 border-b pb-2">Jawaban Survei:</p>
                                @foreach($questions as $q)
                                    @php
                                        $ans = $answers->where('question_id', $q->id)
                                                        ->where('respondent_id', $res->id)
                                                        ->first();
                                        
                                        $displayAnswer = 'N/A';
                                        if($ans) {
                                            if($q->type === 'checkbox') {
                                                $items = json_decode($ans->answer, true) ?? [];
                                                $displayAnswer = implode(', ', $items);
                                            } else {
                                                $displayAnswer = $ans->answer;
                                            }
                                        }
                                    @endphp
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 shadow-inner">
                                        <p class="text-sm font-semibold text-gray-700 mb-1 flex items-start">
                                            <span class="text-indigo-500 mr-2 min-w-[20px]">{{ $loop->iteration }}.</span> 
                                            <span class="flex-1">{{ $q->question }}</span>
                                        </p>
                                        <div class="text-gray-800 text-sm italic pl-5 break-words whitespace-pre-wrap">{{ $displayAnswer }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                
            @endif
        </div>
    </div>
    
    {{-- Tambahkan CSS dan JS DataTables (sudah ada) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    
    {{-- Pastikan urutan jQuery sebelum DataTables --}}
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            var table = $('#evaluationTable').DataTable({
                // Konfigurasi umum
                paging: true,
                searching: true, // Fitur pencarian default diaktifkan
                ordering: true,
                info: true,
                responsive: false,
                scrollX: true, // Penting untuk tabel lebar
                order: [[ 1, 'asc' ]], // Urutkan berdasarkan kolom Nama/Sekolah secara default
                
                // Menghilangkan elemen DataTables default (search box dan length menu)
                dom: 'rtip', 
                
                // Konfigurasi Nomor Urut (Nomor kolam 0)
                columnDefs: [
                    {
                        // Kolom pertama (index 0) tidak bisa di-sort
                        targets: 'no-sort',
                        orderable: false,
                    },
                    { 
                        // Kolom pertama (index 0) akan diberi nomor urut
                        "searchable": false, 
                        "orderable": false, 
                        "targets": 0 
                    }
                ],
                
                // Konfigurasi Bahasa (Opsional, agar lebih ramah Bahasa Indonesia)
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                }
            });

            // Logika untuk mengisi Nomor Urut (No) yang BENAR dan BERURUT
            // Fungsi ini dipanggil setiap kali tabel digambar ulang (setelah filter/sort/paging)
            table.on( 'order.dt search.dt draw.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();


            // Menghubungkan input custom dengan fitur pencarian DataTables
            $('#search_input').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
</x-app-layout>