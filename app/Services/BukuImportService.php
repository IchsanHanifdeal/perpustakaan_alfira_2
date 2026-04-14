<?php

namespace App\Services;

use App\Jobs\ProcessBukuImportJob;
use Illuminate\Support\Facades\Log;

class BukuImportService
{
    public function fetchAndQueue(int $count)
    {
        ProcessBukuImportJob::dispatch($count);

        return $count;
    }
}
