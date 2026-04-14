<x-dashboard.main title="Import Data Siswa">
    <div class="flex flex-col gap-5">
        <div class="flex flex-col border-back rounded-xl w-full">
            <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                    Import Data Siswa
                </h1>
                <p class="text-sm opacity-60">
                    Silahkan unggah file Excel yang berisi data siswa sesuai dengan format yang telah ditentukan.
                </p>
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
    </div>
</x-dashboard.main>