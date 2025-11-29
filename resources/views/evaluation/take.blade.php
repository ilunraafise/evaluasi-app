<x-app-layout>
    {{-- Tambahkan FontAwesome untuk Ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    {{-- Pastikan header Laravel Breeze/Jetstream tidak muncul (diasumsikan sudah ditangani oleh kustomisasi x-app-layout) --}}
    
    {{-- Background Halaman dan Container Utama --}}
    <div class="py-6 sm:py-10 bg-gray-50 min-h-screen">
        
        {{-- Container Form: Lebar optimal untuk keterbacaan di semua device --}}
        <div class="max-w-screen-sm lg:max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">

            {{-- 📝 Header Form --}}
            <div class="bg-white p-5 sm:p-7 shadow-2xl rounded-xl border-l-8 border-indigo-600 mb-6 sm:mb-8 transition duration-300">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2 flex items-center">
                    <i class="fas fa-clipboard-check mr-2 sm:mr-3 text-indigo-600 text-2xl sm:text-3xl flex-shrink-0"></i> 
                    <span class="truncate">{{ $form->title }}</span>
                </h1>
                @if($form->description)
                    <p class="text-gray-600 text-xs sm:text-sm border-l-4 border-indigo-300 pl-3 italic mt-3">{{ $form->description }}</p>
                @else
                    <p class="text-gray-500 italic text-xs sm:text-sm mt-2">Silakan isi form evaluasi ini dengan data yang sebenar-benarnya.</p>
                @endif
            </div>

            {{-- Form Utama --}}
            <form action="{{ route('form.submit', $form) }}" method="POST" class="space-y-6 sm:space-y-8">
                @csrf

                {{-- 1. Identitas Responden (Card 1) --}}
                <div class="bg-white p-5 sm:p-7 rounded-xl shadow-lg space-y-4 border border-gray-100 hover:shadow-xl transition duration-300">
                    <h2 class="font-extrabold text-lg sm:text-xl text-indigo-700 border-b-2 border-indigo-100 pb-3 flex items-center">
                        <i class="fas fa-user-circle mr-3 text-indigo-500 flex-shrink-0"></i> 1. Data Diri Responden
                    </h2>
                    
                    {{-- Layout Input: Semua input sekarang menggunakan grid 2 kolom di tablet/desktop --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        {{-- Input Nama (Optional) - Penuh di semua ukuran --}}
                        <div class="sm:col-span-2">
                            <label for="respondent_name" class="block text-sm font-semibold text-gray-700">Nama (opsional)</label>
                            <input 
                                type="text" 
                                name="respondent_name" 
                                id="respondent_name"
                                placeholder="Contoh: Budi Santoso" 
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 text-sm"
                            >
                        </div>

                        {{-- Input Asal Sekolah - Penuh di semua ukuran --}}
                        <div class="sm:col-span-2">
                            <label for="origin_school" class="block text-sm font-semibold text-gray-700">Asal Satuan Pendidikan <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                name="origin_school" 
                                id="origin_school"
                                placeholder="Contoh: SMA 1 Jakarta" 
                                required 
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 text-sm"
                            >
                        </div>

                        {{-- Select Jenjang (Setengah kolom di tablet/desktop) --}}
                        <div>
                            <label for="level" class="block text-sm font-semibold text-gray-700">Jenjang <span class="text-red-500">*</span></label>
                            <select 
                                name="level" 
                                id="level"
                                required 
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 text-sm"
                            >
                                <option value="" disabled selected>-- Pilih Jenjang --</option>
                                <option value="PAUD">PAUD</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="SMK">SMK</option>
                                <option value="SLB">SLB</option>
                                <option value="PNF">PNF</option>
                            </select>
                        </div>

                        {{-- Select Wilayah (Setengah kolom di tablet/desktop) --}}
                        <div>
                            <label for="region" class="block text-sm font-semibold text-gray-700">Wilayah <span class="text-red-500">*</span></label>
                            <select 
                                name="region" 
                                id="region"
                                required 
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 text-sm"
                            >
                                <option value="" disabled selected>-- Pilih Wilayah --</option>
                                <option value="Jakarta Barat">Jakarta Barat</option>
                                <option value="Jakarta Pusat">Jakarta Pusat</option>
                                <option value="Jakarta Utara">Jakarta Utara</option>
                                <option value="Jakarta Timur">Jakarta Timur</option>
                                <option value="Jakarta Selatan">Jakarta Selatan</option>
                            </select>
                        </div>

                        {{-- Select Batch - Penuh di semua ukuran --}}
                        <div class="sm:col-span-2">
                            <label for="batch" class="block text-sm font-semibold text-gray-700">Angkatan <span class="text-red-500">*</span></label>
                            <select 
                                name="batch" 
                                id="batch"
                                required 
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 text-sm"
                            >
                                <option value="" disabled selected>-- Pilih Angkatan --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                {{-- 2. Header Pertanyaan --}}
                <h2 class="font-extrabold text-lg sm:text-xl text-indigo-700 border-b-2 border-indigo-600 pb-3 flex items-center mt-6 pt-4">
                    <i class="fas fa-comments mr-3 text-indigo-500 flex-shrink-0"></i> 2. Pertanyaan Evaluasi
                </h2>

                {{-- Pertanyaan --}}
                @foreach($questions as $q)
                    <div class="bg-white p-5 sm:p-7 rounded-xl shadow-lg space-y-4 border border-gray-100 hover:shadow-xl transition duration-300">
                        
                        {{-- Judul Pertanyaan --}}
                        <p class="font-extrabold text-base sm:text-lg text-gray-800 flex items-start">
                            <span class="text-indigo-600 mr-2 flex-shrink-0">{{ $loop->iteration }}.</span> 
                            <span class="flex-1">{{ $q->question }} <span class="text-red-500">*</span></span>
                        </p>
                        
                        @php
                            // Decoding options for safety
                            $options = is_array($q->options) ? $q->options : json_decode($q->options) ?? [];
                        @endphp

                        @if($q->type === 'radio' && count($options))
                            {{-- Tampilan Opsi Radio (Horizontal/Flex Wrap Responsive) --}}
                            <div class="flex flex-wrap gap-2 sm:gap-3 pt-1">
                                @foreach($options as $opt)
                                    <label class="flex items-center px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-full hover:bg-indigo-50 transition duration-150 cursor-pointer text-sm font-medium text-gray-700">
                                        <input 
                                            type="radio" 
                                            name="answers[{{ $q->id }}]" 
                                            value="{{ $opt }}" 
                                            required 
                                            class="form-radio h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 mr-2 flex-shrink-0"
                                        >
                                        <span class="text-xs sm:text-sm">{{ $opt }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            {{-- Input Teks/Textarea --}}
                            <textarea 
                                name="answers[{{ $q->id }}]" 
                                required 
                                rows="3"
                                placeholder="Tulis jawaban Anda di sini..."
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 text-sm"
                            ></textarea>
                        @endif
                    </div>
                @endforeach

                {{-- Tombol Submit --}}
                <div class="pt-6">
                    <button 
                        type="submit" 
                        class="w-full bg-indigo-600 text-white font-bold text-base sm:text-lg px-6 py-3 rounded-xl shadow-xl hover:bg-indigo-700 transition duration-300 transform hover:scale-[1.01] flex items-center justify-center ring-4 ring-indigo-300/50"
                    >
                        <i class="fas fa-paper-plane mr-3 text-white"></i> Kirim Evaluasi
                    </button>
                </div>
            </form>
            
            <div class="text-center text-gray-500 text-xs mt-6 pb-4">
                <p>&copy; {{ date('Y') }} Form Evaluasi. Semua hak dilindungi.</p>
            </div>
            
        </div>
    </div>
</x-app-layout>