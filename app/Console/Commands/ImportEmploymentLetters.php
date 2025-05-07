<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Doctor;
use App\Models\DoctorFile;

class ImportEmploymentLetters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:employment_letters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import employment letter files for each doctor, matching any file that starts with "employment_letter"';

    public function handle()
    {
        $branchId = 1;

        $doctors = Doctor::where('branch_id', $branchId)
                         ->whereNotNull('doctor_number')
                         ->orderBy('doctor_number')
                         ->get();

        $this->info("Found {$doctors->count()} doctors.");

        foreach ($doctors as $doctor) {
            $dir = "documents/{$doctor->doctor_number}";

            if (! Storage::disk('public')->exists($dir)) {
                $this->warn("Directory not found for doctor_number: {$doctor->doctor_number}");
                continue;
            }

            // 1) list all files in that directory
            $files = Storage::disk('public')->files($dir);

            // 2) filter by prefix
            $matched = array_filter($files, function ($path) {
                return Str::startsWith(basename($path), 'employment_letter');
            });

            if (empty($matched)) {
                $this->warn("No employment_letter file found for Doctor ID: {$doctor->id}");
                continue;
            }

            // 3) import each match
            foreach ($matched as $filePath) {
                $fileName = basename($filePath);

                DoctorFile::updateOrCreate(
                    [
                        'doctor_id' => $doctor->id,
                        'file_path' => $filePath,
                    ],
                    [
                        'file_name'    => $fileName,
                        'file_type_id' => 31, // <— change to your employment_letter file_type_id
                    ]
                );

                $this->info("Imported {$fileName} for Doctor ID: {$doctor->id}");
            }
        }

        $this->info('All employment letters processed.');
    }
}
