<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Doctor;
use App\Models\DoctorFile;

class ImportOtherDocuments extends Command
{
    protected $signature = 'import:other_documents';
    protected $description = 'Import files starting with other_ for each doctor';

    public function handle()
    {
        $branchId = 1;
        $fileTypeId = 55;

        $doctors = Doctor::where('branch_id', $branchId)
                         ->whereNotNull('doctor_number')
                         ->orderBy('doctor_number')
                         ->get();

        foreach ($doctors as $doctor) {
            $dir = "documents/{$doctor->doctor_number}";
            if (! Storage::disk('public')->exists($dir)) {
                continue;
            }

            $files = Storage::disk('public')->files($dir);

            $matched = array_filter($files, function ($path) {
                return Str::startsWith(basename($path), 'other_');
            });

            foreach ($matched as $filePath) {
                DoctorFile::create(
                    [
                        'doctor_id' => $doctor->id,
                        'file_path' => $filePath,
                        'file_name'    => basename($filePath),
                        'file_type_id' => $fileTypeId,
                        'order_number' => 99,
                    ]
                );
            }
        }
    }
}
