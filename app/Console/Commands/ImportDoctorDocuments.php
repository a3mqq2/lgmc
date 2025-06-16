<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\DoctorFile;
use App\Models\FileType;
use App\Models\Doctor;

class ImportDoctorDocuments extends Command
{
    protected $signature   = 'import:doctor-documents';
    protected $description = 'Import doctor documents from lgmc_r.documents into LGMC.doctor_files';

    public function handle(): void
    {
        $this->info('Starting import of doctor documents...');

        $oldDocuments = DB::connection('lgmc_r')->table('documents')->get();

        $imported = 0;
        $skipped  = 0;
        $errors   = 0;

        foreach ($oldDocuments as $oldDoc) {
            try {
                $doctor = Doctor::where('index', $oldDoc->member_id)->first();
                if (!$doctor) {
                    $this->warn("Doctor not found for member_id: {$oldDoc->member_id}");
                    $skipped++;
                    continue;
                }

                $fileType = FileType::where('slug', $oldDoc->slug)->first();

                if (!$fileType && str_contains($oldDoc->slug, '_')) {
                    $baseSlug = explode('_', $oldDoc->slug)[0];
                    $fileType = FileType::where('slug', $baseSlug)->first();
                }

                if (!$fileType) {
                    $fileType = FileType::where('slug', 'other')->first();
                }

                if (!$fileType) {
                    $this->error("Fallback 'other' file type not found in FileType table.");
                    $errors++;
                    continue;
                }

                $exists = DoctorFile::where('doctor_id', $doctor->id)
                    ->where('file_type_id', $fileType->id)
                    ->exists();

                if ($exists) {
                    $this->line("Document already exists for doctor {$doctor->id}, file type {$fileType->name}");
                    $skipped++;
                    continue;
                }

                $filePath = $oldDoc->file_path
                    ?? $oldDoc->path
                    ?? $this->guessStoragePath($doctor->doctor_number, $fileType->slug);

                if (!Storage::disk('public')->exists($filePath)) {
                    $filePath = '';
                }

                DoctorFile::create([
                    'doctor_id'     => $doctor->id,
                    'file_type_id'  => $fileType->id,
                    'file_name'     => $oldDoc->ar_name ?: $fileType->name,
                    'file_path'     => $filePath,
                    'uploaded_at'   => $oldDoc->created_at,
                    'order_number'  => $fileType->order_number,
                    'renew_number'  => 0,
                    'created_at'    => $oldDoc->created_at,
                    'updated_at'    => $oldDoc->updated_at,
                ]);

                $imported++;
            } catch (\Throwable $e) {
                $this->error("Failed to import document ID {$oldDoc->id}: {$e->getMessage()}");
                $errors++;
            }
        }

        $this->info("\n=== Import Summary ===");
        $this->info("Imported:  {$imported}");
        $this->info("Skipped:   {$skipped}");
        $this->info("Errors:    {$errors}");
        $this->info("Total processed: " . ($imported + $skipped + $errors));
    }

    private function guessStoragePath(string $doctorNumber, string $slug): string
    {
        foreach (['jpg', 'jpeg', 'png', 'pdf'] as $ext) {
            $path = "documents/{$doctorNumber}/{$slug}.{$ext}";
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }
        return "documents/{$doctorNumber}/{$slug}";
    }
}
