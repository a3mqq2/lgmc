<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\DoctorFile;
use App\Models\FileType;
use App\Models\Doctor;

class ImportDoctorDocuments extends Command
{
    protected $signature = 'import:doctor-documents';
    protected $description = 'Import doctor documents from lgmc_r.documents into LGMC.doctor_files';

    public function handle()
    {
        $this->info('Starting import of doctor documents...');
        
        // جلب جميع المستندات من قاعدة البيانات القديمة
        $oldDocuments = DB::connection('lgmc_r')->table('documents')->get();
        
        $imported = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($oldDocuments as $oldDoc) {
            try {
                // البحث عن الطبيب في قاعدة البيانات الجديدة
                $doctor = Doctor::where('old_member_id', $oldDoc->member_id)->first();
                
                if (!$doctor) {
                    $this->warn("Doctor not found for member_id: {$oldDoc->member_id}");
                    $skipped++;
                    continue;
                }

                // البحث عن نوع الملف بناءً على الـ slug
                $fileType = FileType::where('slug', $oldDoc->slug)->first();
                
                if (!$fileType) {
                    $this->warn("File type not found for slug: {$oldDoc->slug}");
                    $skipped++;
                    continue;
                }

                // التحقق من عدم وجود المستند مسبقاً
                $existingFile = DoctorFile::where('doctor_id', $doctor->id)
                    ->where('file_type_id', $fileType->id)
                    ->first();

                if ($existingFile) {
                    $this->line("Document already exists for doctor {$doctor->id}, file type {$fileType->name}");
                    $skipped++;
                    continue;
                }

                // إنشاء المستند الجديد
                DoctorFile::create([
                    'doctor_id' => $doctor->id,
                    'file_type_id' => $fileType->id,
                    'file_name' => $oldDoc->ar_name ?? $fileType->name,
                    'file_path' => null, // يمكن تحديثه لاحقاً إذا كان لديك مسارات الملفات
                    'uploaded_at' => $oldDoc->created_at,
                    'order_number' => $fileType->order_number,
                    'renew_number' => 0,
                    'created_at' => $oldDoc->created_at,
                    'updated_at' => $oldDoc->updated_at,
                ]);

                $this->info("✓ Imported document: {$oldDoc->ar_name} for doctor {$doctor->id}");
                $imported++;

            } catch (\Exception $e) {
                $this->error("✗ Failed to import document ID {$oldDoc->id}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("\n=== Import Summary ===");
        $this->info("Imported: {$imported}");
        $this->info("Skipped: {$skipped}");
        $this->info("Errors: {$errors}");
        $this->info("Total processed: " . ($imported + $skipped + $errors));
    }
}