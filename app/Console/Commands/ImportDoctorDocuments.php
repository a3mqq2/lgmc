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
                if (!$doctor) { $skipped++; continue; }

                $slug       = $oldDoc->slug ?? '';
                $targetSlug = $this->normalizeSlug($slug);

                $fileType = FileType::where('slug', $targetSlug)->first();
                if (!$fileType) {
                    $fileType = FileType::where('slug', 'other')->first();
                }
                if (!$fileType) { $skipped++; continue; }

                $exists = DoctorFile::where('doctor_id', $doctor->id)
                    ->where('file_type_id', $fileType->id)
                    ->exists();

                if ($exists) { $skipped++; continue; }

                $filePath = $oldDoc->file_path
                    ?? $oldDoc->path
                    ?? $this->guessPath($doctor->doctor_number, $targetSlug);

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
                $errors++;
            }
        }

        $this->info("Imported: {$imported} | Skipped: {$skipped} | Errors: {$errors}");
    }

    private function normalizeSlug(string $raw): string
    {
        $base = str_contains($raw, '_') ? explode('_', $raw)[0] : $raw;

        return match ($base) {
            'employment_letter', 'employement_letter' => 'employment_letter',
            'other'                                      => 'other',
            default                                      => $base,
        };
    }

    private function guessPath(string $doctorNumber, string $slug): string
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
