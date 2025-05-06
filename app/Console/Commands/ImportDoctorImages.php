<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Doctor;
use App\Models\DoctorFile;
use Illuminate\Support\Facades\Storage;

class ImportDoctorImages extends Command
{
    protected $signature = 'import:doctor-images';
    protected $description = 'Import doctors images from storage to doctor_files table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $branchId = 1;

        $doctors = Doctor::where('branch_id', $branchId)
            ->whereNotNull('doctor_number')
            ->orderBy('doctor_number')
            ->get();

        $this->info("Found {$doctors->count()} doctors.");

        foreach ($doctors as $doctor) {
            $fileName = "{$doctor->index}.jpg";
            $filePath = "profilepic/{$fileName}";

            if (Storage::disk('public')->exists($filePath)) {
                DoctorFile::create([
                    'doctor_id'    => $doctor->id,
                    'file_name'    => $fileName,
                    'file_path'    => $filePath,
                    'file_type_id' => 1,
                ]);

                $this->info("✅ Image imported for Doctor ID: {$doctor->id} - {$fileName}");
            } else {
                $this->warn("⛔️ Image not found for Doctor ID: {$doctor->id} - Expected: {$fileName}");
            }
        }

        $this->info('✅ All doctor images processed successfully.');
    }
}
