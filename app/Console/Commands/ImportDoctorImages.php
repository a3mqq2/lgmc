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

    public function handle()
    {
        $branchId = 1;
        
        // Get all doctors of branch_id = 1, ordered by index
        $doctors = Doctor::where('branch_id', $branchId)
            ->orderBy('index')
            ->get();

        $this->info("Found {$doctors->count()} doctors.");

        foreach ($doctors as $doctor) {
            $fileName = "{$doctor->index}.jpg"; // بناء اسم الصورة بناءً على index
            $filePath = "profilepic/{$fileName}";

            // Check if file exists
            if (Storage::disk('public')->exists($filePath)) {

                DoctorFile::create([
                    'doctor_id'    => $doctor->id,
                    'file_name'    => $fileName,
                    'file_path'    => $filePath,
                    'file_type_id' => 1,
                ]);

                $this->info("Image imported for Doctor ID: {$doctor->id} - {$fileName}");
            } else {
                $this->warn("Image not found for Doctor ID: {$doctor->id} - Expected: {$fileName}");
            }
        }

        $this->info('All doctor images processed successfully.');
    }
}
