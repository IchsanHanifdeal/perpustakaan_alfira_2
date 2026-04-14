<x-dashboard.main title="Import Data">
    <script src="https://openlibrary.org/api/books?bibkeys=ISBN:0451526538&callback=mycallback"></script>
        {{-- Import Data Siswa --}}
        <div class="flex flex-col border-back rounded-xl w-full">
            <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                    Import Data Siswa
                </h1>
            </div>
            <div class="flex flex-col rounded-b-xl gap-6 p-5 sm:p-7 bg-neutral">
                <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-col gap-4">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text text-white">File Excel (.xlsx, .xls)</span>
                            </label>
                            <input type="file" name="file" accept=".xlsx, .xls"
                                class="file-input file-input-bordered w-full bg-gray-300 text-black @error('file') border-red-500 @enderror" />
                            @error('file')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 items-center justify-end mt-4">
                            <button type="submit" class="btn btn-primary w-full sm:w-auto px-10">
                                Import Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-col border-back rounded-xl w-full">
            <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                    Import Data Buku dari API
                </h1>
            </div>
            <div class="flex flex-col rounded-b-xl gap-6 p-5 sm:p-7 bg-neutral">
                <form action="{{ route('import_buku.store') }}" method="POST">
                    @csrf
                    <div class="flex flex-col gap-4">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text text-white">Jumlah Buku yang Ingin Diimport</span>
                            </label>
                            <input type="number" name="jumlah" value="5" min="1" max="100"
                                class="input input-bordered w-full bg-gray-300 text-black @error('jumlah') border-red-500 @enderror" />
                            <p class="text-xs text-white opacity-60 mt-2">Sistem akan mengambil data buku populer secara otomatis dari Open Library API.</p>
                            @error('jumlah')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 items-center justify-end mt-4">
                            <button type="submit" class="btn btn-secondary w-full sm:w-auto px-10">
                                Mulai Import Buku
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</x-dashboard.main>