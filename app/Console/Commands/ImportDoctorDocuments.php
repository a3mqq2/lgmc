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

    public function handle()
    {
        $this->info('Starting import of doctor documents...');

        $oldDocuments = DB::connection('lgmc_r')->table('documents')->get();

        $imported = 0;
        $skipped  = 0;
        $errors   = 0;

        foreach ($oldDocuments as $oldDoc) {
            try {
                /** @var Doctor|null $doctor */
                $doctor = Doctor::where('index', $oldDoc->member_id)->first();

                if (!$doctor) {
                    $this->warn("Doctor not found for member_id: {$oldDoc->member_id}");
                    $skipped++;
                    continue;
                }

                /** @var FileType|null $fileType */
                $fileType = FileType::where('slug', $oldDoc->slug)->first();

                if (!$fileType) {
                    $fileType = FileType::where('slug','other')->first();
                }

                $exists = DoctorFile::where('doctor_id', $doctor->id)
                    ->where('file_type_id', $fileType->id)
                    ->exists();


                // محاولة تحديد مسار الملف القديم
                $filePath = $oldDoc->file_path
                    ?? $oldDoc->path
                    ?? $this->guessStoragePath($doctor->doctor_number, $oldDoc->slug);

                // إذا لم يكن الملف موجودًا على التخزين، ضع مسارًا فارغًا على الأقل لتجنب خطأ NOT NULL
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

                $this->info("✓ Imported document: {$oldDoc->ar_name} for doctor {$doctor->id}");
                $imported++;
            } catch (\Exception $e) {
                $this->error("✗ Failed to import document ID {$oldDoc->id}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("\n=== Import Summary ===");
        $this->info("Imported:  {$imported}");
        $this->info("Skipped:   {$skipped}");
        $this->info("Errors:    {$errors}");
        $this->info("Total processed: " . ($imported + $skipped + $errors));
    }

    /**
     * Guess a public storage path for a document if no explicit path exists.
     */
    private function guessStoragePath(string $doctorNumber, string $slug): string
    {
        $extensions = ['jpg', 'jpeg', 'png', 'pdf'];
        foreach ($extensions as $ext) {
            $path = "documents/{$doctorNumber}/{$slug}.{$ext}";
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }

        // default placeholder (will be stored as empty if the file truly doesn't exist)
        return "documents/{$doctorNumber}/{$slug}";
    }
}
