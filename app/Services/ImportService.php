<?php

namespace App\Services;

use App\Jobs\ImportSiswaJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImportService
{
    public function executeImport(UploadedFile $file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('temp-imports', $filename, 'local');

        ImportSiswaJob::dispatch(Storage::disk('local')->path($path));

        return true;
    }
}
