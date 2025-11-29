<x-app-layout>
    
    {{-- Background Halaman dan Container Utama --}}
    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Header Hasil Evaluasi --}}
            <div class="bg-white p-6 shadow-xl rounded-xl border-t-4 border-indigo-600">
                <h1 class="text-3xl font-extrabold text-gray-900 flex items-center">
                    <i class="fas fa-chart-bar mr-3 text-indigo-600"></i> Hasil Evaluasi: <span class="ml-2 text-indigo-700">{{ $form->title }}</span>
                </h1>
                <p class="text-gray-500 mt-2 text-lg">Ringkasan dan detail jawaban dari {{ $answers->pluck('respondent')->unique('id')->count() }} responden.</p>
            </div>
            
            <hr class="border-gray-200">

            @if($answers->isEmpty())
                <div class="p-6 bg-white rounded-lg shadow-md text-center">
                    <p class="text-gray-500 italic text-xl">Belum ada peserta yang mengisi form ini. 😔</p>
                </div>
            @else
                @php
                    // Logika PHP/Blade tetap sama
                    $questions = $form->questions()->get();
                    $respondents = $answers->pluck('respondent')->unique('id');
                @endphp

                <h2 class="text-2xl font-bold text-gray-800 mb-4">Detail Responden</h2>
                
                {{-- Tampilan Desktop (Tabel) --}}
                <div class="hidden lg:block bg-white shadow-xl rounded-xl overflow-hidden p-6">
                    
                    {{-- Kontainer Pencarian DataTables --}}
                    <div class="mb-4">
                        <label for="search_input" class="sr-only">Cari Data</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="search_input" class="block w-full p-3 pl-10 text-sm border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Cari Nama, Sekolah, Wilayah, atau Jawaban...">
                        </div>
                    </div>

                    <div class="overflow-x-auto max-h-[70vh] relative"> 
                        {{-- ID 'evaluationTable' DIPERLUKAN OLEH JAVASCRIPT DATATABLES --}}
                        <table id="evaluationTable" class="min-w-full divide-y divide-gray-200">
                            
                            {{-- Header Tabel (Sticky) --}}
                            <thead class="bg-indigo-50 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    {{-- Kolom No. Urut (Perlu class 'no-sort' agar DataTables tidak mengurutkan kolom ini) --}}
                                    <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider text-indigo-700 border-r no-sort">No</th>
                                    
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-indigo-700 border-r min-w-[150px]">Nama/Sekolah</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-indigo-700 border-r min-w-[100px]">Jenjang/Wilayah</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider text-indigo-700 min-w-[80px]">Batch</th>
                                    
                                    {{-- Kolom Pertanyaan --}}
                                    @foreach($questions as $q)
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700 border-l min-w-[200px] bg-yellow-100 hover:bg-yellow-200 transition duration-150">
                                            {{ $q->question }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            {{-- Isi Tabel. DataTables akan mengurus nomor urut --}}
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($respondents as $index => $res)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-100 transition duration-150">
                                        {{-- Kolom Nomor Urut Kosong, akan diisi oleh DataTables --}}
                                        <td class="px-6 py-3 text-center whitespace-nowrap text-sm font-medium text-gray-900 border-r"></td> 
                                        
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700 border-r">
                                            <div class="font-semibold">{{ $res->name ?? 'Anonymous' }}</div>
                                            <div class="text-xs text-gray-500">{{ $res->origin_school }}</div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700 border-r">
                                            <div class="font-medium">{{ $res->level }}</div>
                                            <div class="text-xs text-gray-500">{{ $res->region }}</div>
                                        </td>
                                        <td class="px-6 py-3 text-center whitespace-nowrap text-sm text-gray-700">{{ $res->batch }}</td>
                                        
                                        {{-- Kolom Jawaban --}}
                                        @foreach($questions as $q)
                                            @php
                                                $ans = $answers->where('question_id', $q->id)
                                                               ->where('respondent_id', $res->id)
                                                               ->first();
                                                
                                                $displayAnswer = '-';
                                                if($ans) {
                                                    if($q->type === 'checkbox') {
                                                        $items = json_decode($ans->answer, true) ?? [];
                                                        $displayAnswer = implode(', ', $items);
                                                    } else {
                                                        $displayAnswer = $ans->answer;
                                                    }
                                                }
                                            @endphp
                                            <td class="px-6 py-3 text-sm text-gray-800 border-l max-w-[250px] overflow-hidden whitespace-normal" title="{{ $displayAnswer }}">
                                                {{ $displayAnswer }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                {{-- Tampilan Mobile (Card) - Tetap Dibuat Rapi --}}
                <div class="lg:hidden space-y-6">
                    {{-- Dalam tampilan Mobile (Card), fitur pencarian/filter tidak diimplementasikan secara dinamis di sini --}}
                    @foreach($respondents as $index => $res)
                        <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 {{ $index % 2 == 0 ? 'border-indigo-500' : 'border-green-500' }}">
                            <div class="flex justify-between items-start mb-3 border-b pb-2">
                                <div>
                                    <p class="text-xs font-semibold uppercase text-gray-500">Responden #{{ $index + 1 }}</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $res->name ?? 'Anonymous' }}</p>
                                </div>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $res->batch }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-y-2 text-sm mb-4">
                                <div class="text-gray-500 flex items-center"><i class="fas fa-school mr-2"></i> Sekolah:</div>
                                <div class="font-medium text-right">{{ $res->origin_school }}</div>
                                
                                <div class="text-gray-500 flex items-center"><i class="fas fa-graduation-cap mr-2"></i> Jenjang:</div>
                                <div class="font-medium text-right">{{ $res->level }}</div>
                                
                                <div class="text-gray-500 flex items-center"><i class="fas fa-map-marker-alt mr-2"></i> Wilayah:</div>
                                <div class="font-medium text-right">{{ $res->region }}</div>
                            </div>
                            
                            <hr class="my-3">

                            {{-- Jawaban per Pertanyaan --}}
                            <div class="space-y-4">
                                <p class="text-md font-bold text-indigo-600">Jawaban Survei:</p>
                                @foreach($questions as $q)
                                    @php
                                        $ans = $answers->where('question_id', $q->id)
                                                       ->where('respondent_id', $res->id)
                                                       ->first();
                                        
                                        $displayAnswer = '-';
                                        if($ans) {
                                            if($q->type === 'checkbox') {
                                                $items = json_decode($ans->answer, true) ?? [];
                                                $displayAnswer = implode(', ', $items);
                                            } else {
                                                $displayAnswer = $ans->answer;
                                            }
                                        }
                                    @endphp
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700 mb-1">{{ $loop->iteration }}. {{ $q->question }}</p>
                                        <div class="p-2 bg-gray-50 border rounded-lg text-gray-800 text-sm italic">{{ $displayAnswer }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                
            @endif
        </div>
    </div>
    
    {{-- Tambahkan CSS dan JS DataTables --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
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