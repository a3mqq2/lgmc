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
                // ============ 1) Doctor ============
                $doctor = Doctor::where('index', $oldDoc->member_id)->first();
                if (!$doctor) { $skipped++; continue; }

                // ============ 2) File-type (slug) ============
                $slug       = $oldDoc->slug ?? '';
                $baseSlug   = str_contains($slug, '_') ? explode('_', $slug)[0] : $slug;

                // fix common misspellings
                if ($baseSlug === 'employement_letter') { $baseSlug = 'employment_letter'; }

                // “other” covers شهادة ميلاد إلكترونية وأي ملفات لا نعرف سلاقها
                $fileType = FileType::where('slug', $baseSlug)->first()
                           ?: FileType::where('slug', 'other')->first();

                if (!$fileType) {           // لا يوجد حتى “other”
                    $errors++; continue;
                }

                // ============ 3) Allow multiple “other” ============
                $query = DoctorFile::where('doctor_id', $doctor->id)
                                   ->where('file_type_id', $fileType->id);

                // للسلاق “other” اسم الملف يميّز السجل؛ أمّا لباقي الأنواع نمنع التكرار
                if ($fileType->slug !== 'other') {
                    if ($query->exists()) { $skipped++; continue; }
                } else {
                    $query->where('file_name', $oldDoc->ar_name ?? $fileType->name);
                    if ($query->exists()) { $skipped++; continue; }
                }

                // ============ 4) Resolve path ============
                $filePath = $oldDoc->file_path
                         ?? $oldDoc->path
                         ?? $this->guessPath($doctor->doctor_number, $fileType->slug);

                if (!Storage::disk('public')->exists($filePath)) { $filePath = ''; }

                // ============ 5) Insert ============
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

    private function guessPath(string $doctorNumber, string $slug): string
    {
        foreach (['jpg','jpeg','png','pdf'] as $ext) {
            $p = "documents/{$doctorNumber}/{$slug}.{$ext}";
            if (Storage::disk('public')->exists($p)) return $p;
        }
        return "documents/{$doctorNumber}/{$slug}";
    }
}
