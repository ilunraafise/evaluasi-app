<x-app-layout>
    
    {{-- Background Halaman dan Container Utama --}}
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header Form --}}
            <div class="bg-white p-6 shadow-xl rounded-xl border-t-4 border-indigo-600 mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                    <i class="fas fa-clipboard-list mr-2 text-indigo-600"></i> {{ $form->title }}
                </h1>
                @if($form->description)
                    <p class="text-gray-600 border-l-4 border-gray-300 pl-3 italic">{{ $form->description }}</p>
                @else
                    <p class="text-gray-500 italic">Silakan isi form evaluasi ini dengan data yang sebenar-benarnya.</p>
                @endif
            </div>

            {{-- Form Utama --}}
            <form action="{{ route('form.submit', $form) }}" method="POST" class="space-y-8">
                @csrf

                {{-- Identitas Responden (Card 1) --}}
                <div class="bg-white p-6 rounded-xl shadow-lg space-y-4 border border-gray-200">
                    <h2 class="font-bold text-xl text-indigo-700 border-b pb-2 flex items-center">
                        <i class="fas fa-user-circle mr-2"></i> 1. Data Diri
                    </h2>
                    
                    {{-- Input Nama (Optional) --}}
                    <div>
                        <label for="respondent_name" class="block text-sm font-medium text-gray-700">Nama (opsional)</label>
                        <input 
                            type="text" 
                            name="respondent_name" 
                            id="respondent_name"
                            placeholder="Contoh: Budi Santoso" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                        >
                    </div>

                    {{-- Input Asal Sekolah --}}
                    <div>
                        <label for="origin_school" class="block text-sm font-medium text-gray-700">Asal Sekolah <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            name="origin_school" 
                            id="origin_school"
                            placeholder="Contoh: SMA 1 Jakarta" 
                            required 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                        >
                    </div>

                    {{-- Select Jenjang --}}
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700">Jenjang <span class="text-red-500">*</span></label>
                        <select 
                            name="level" 
                            id="level"
                            required 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                        >
                            <option value="">-- Pilih Jenjang --</option>
                            <option value="PAUD">PAUD</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="SMK">SMK</option>
                            <option value="SLB">SLB</option>
                            <option value="PNF">PNF</option>
                        </select>
                    </div>

                    {{-- Select Wilayah --}}
                    <div>
                        <label for="region" class="block text-sm font-medium text-gray-700">Wilayah <span class="text-red-500">*</span></label>
                        <select 
                            name="region" 
                            id="region"
                            required 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                        >
                            <option value="">-- Pilih Wilayah --</option>
                            <option value="Jakarta Barat">Jakarta Barat</option>
                            <option value="Jakarta Pusat">Jakarta Pusat</option>
                            <option value="Jakarta Utara">Jakarta Utara</option>
                            <option value="Jakarta Timur">Jakarta Timur</option>
                            <option value="Jakarta Selatan">Jakarta Selatan</option>
                        </select>
                    </div>

                    {{-- Select Batch --}}
                    <div>
                        <label for="batch" class="block text-sm font-medium text-gray-700">Angkatan (Batch) <span class="text-red-500">*</span></label>
                        <select 
                            name="batch" 
                            id="batch"
                            required 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                        >
                            <option value="">-- Pilih Angkatan --</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>

                </div>
                
                <h2 class="font-bold text-xl text-indigo-700 border-b pb-2 flex items-center">
                    <i class="fas fa-comments mr-2"></i> 2. Pertanyaan
                </h2>

                {{-- Pertanyaan --}}
                @foreach($questions as $q)
                    <div class="bg-white p-6 rounded-xl shadow-lg space-y-3 border border-gray-200">
                        <p class="font-semibold text-lg text-gray-800">{{ $loop->iteration }}. {{ $q->question }} <span class="text-red-500">*</span></p>
                        
                        @php
                            $options = is_array($q->options) ? $q->options : json_decode($q->options) ?? [];
                        @endphp

                        @if($q->type === 'radio' && count($options))
                            {{-- TAMPILAN HORIZONTAL DITERAPKAN DI SINI --}}
                            <div class="flex flex-wrap gap-4 pt-1">
                                @foreach($options as $opt)
                                    <label class="flex items-center p-3 border rounded-lg hover:bg-indigo-50 transition duration-150 cursor-pointer">
                                        <input 
                                            type="radio" 
                                            name="answers[{{ $q->id }}]" 
                                            value="{{ $opt }}" 
                                            required 
                                            class="form-radio h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                        >
                                        <span class="ml-3 text-base text-gray-700">{{ $opt }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            {{-- Input Teks/Textarea tetap vertikal untuk kenyamanan membaca --}}
                            <textarea 
                                name="answers[{{ $q->id }}]" 
                                required 
                                rows="3"
                                placeholder="Tulis jawaban Anda di sini..."
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                            ></textarea>
                        @endif
                    </div>
                @endforeach

                {{-- Tombol Submit --}}
                <div class="pt-4">
                    <button 
                        type="submit" 
                        class="w-full bg-indigo-600 text-white font-bold text-lg px-6 py-3 rounded-xl shadow-lg hover:bg-indigo-700 transition duration-300 transform hover:scale-[1.01] flex items-center justify-center"
                    >
                        <i class="fas fa-check-circle mr-3"></i> Kirim Evaluasi
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    {{-- Tambahkan FontAwesome untuk Ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</x-app-layout>