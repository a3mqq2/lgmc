<?php

namespace App\Console\Commands;

use App\Models\Doctor;
use App\Models\DoctorFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportApplicationForm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:application-form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $branchId = 1;
        
        $doctors = Doctor::where('branch_id', $branchId)
            ->whereNotNull('doctor_number')
            ->orderBy('doctor_number')
            ->get();

        $this->info("Found {$doctors->count()} doctors.");

        foreach ($doctors as $doctor) {
            $fileName = "application_form.jpg";
            $filePath = "documents/{$doctor->doctor_number}/{$fileName}";

            if (Storage::disk('public')->exists($filePath)) {

                DoctorFile::create([
                    'doctor_id'    => $doctor->id,
                    'file_name'    => $fileName,
                    'file_path'    => $filePath,
                    'file_type_id' => 56,
                    'order_number' => 2
                ]);

                $this->info("Image imported for Doctor ID: {$doctor->id} - {$fileName}");
            } else {
                $this->warn("Image not found for Doctor ID: {$doctor->id} - Expected: {$fileName}");
            }
        }

        $this->info('All doctor images processed successfully.');
    }
}
