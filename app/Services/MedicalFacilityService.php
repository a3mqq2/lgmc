<?php

namespace App\Services;

use App\Models\Log;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Models\FileType;
use App\Models\MedicalFacility;
use Illuminate\Support\Facades\Auth;

class MedicalFacilityService
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function getVault()
    {
        return Auth::user()->branch->vault;
    }

    public function create(array $data)
    {
        $data['user_id'] = Auth::id();

        if (isset($data['date'])) {
            $data['activity_start_date'] = $data['date'];
            unset($data['date']);
        }

        if (isset($data['documents'])) {
            unset($data['documents']);
        }

        $data['membership_status'] = "inactive";

        if (request('manager_id')) {
            $checkIfManagerHaveFacilityBefore = MedicalFacility::where('manager_id', request('manager_id'))->first();
            if ($checkIfManagerHaveFacilityBefore) {
                // trhow error
                throw new \Exception('هذا المدير لديه منشأة طبية مسجلة بالفعل');
                // return redirect()->back()->withErrors(['هذا المدير لديه منشأة طبية مسجلة بالفعل']);
            }

            $manager = Doctor::find(request('manager_id'));
            if (!$manager->licenses()->where('status', 'active')->first()) {
            // trhow error
                 throw new \Exception('هذا المدير ليس لديه ترخيص نشط');
                // return redirect()->back()->withErrors(['هذا المدير ليس لديه ترخيص نشط']);
            }
        }

        $medicalFacility = MedicalFacility::create($data);

        if (isset($data['manager_id'])) {
            $manager = Doctor::find(request('manager_id'));
            $licence = $manager->licenses()->where('status', 'active')->first();
            $licence->update(['medical_facility_id' => $medicalFacility->id]);
        }

        $file_types = FileType::where('type', 'medical_facility')->get();
        foreach ($file_types as $file_type) {
            if (isset($data['documents'][$file_type->id])) {
                $file = $data['documents'][$file_type->id];
                $path = $file->store('medical-facilites', 'public');

                $medicalFacility->files()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_type_id' => $file_type->id,
                    'file_path' => $path,
                ]);
            }
        }

        Log::create([
            'user_id' => Auth::id(),
            'details' => 'تم إنشاء منشأة طبية جديدة: ' . $medicalFacility->name,
            'loggable_id' => $medicalFacility->id,
            'loggable_type' => MedicalFacility::class,
            'action' => 'create_medical_facility',
        ]);

        return $medicalFacility;
    }

    public function update(MedicalFacility $medicalFacility, array $data): MedicalFacility
    {
        \DB::beginTransaction();

        try {
            $data['user_id'] = Auth::id();

            if ($data['manager_id'] === null) {
                unset($data['manager_id']);
            }

            if (isset($data['date'])) {
                $data['activity_start_date'] = $data['date'];
                unset($data['date']);
            }

            if (isset($data['manager_id']) && $medicalFacility->manager_id != $data['manager_id']) {
                $existingFacility = MedicalFacility::where('manager_id', $data['manager_id'])
                    ->where('id', '!=', $medicalFacility->id)
                    ->first();
                if ($existingFacility) {
                    throw new \Exception('هذا المدير لديه منشأة طبية مسجلة بالفعل');
                }
            }

            $medicalFacility->update($data);

            if (isset($data['documents'])) {
                $fileTypes = FileType::where('type', 'medical_facility')->get();

                foreach ($fileTypes as $fileType) {
                    if (isset($data['documents'][$fileType->id])) {
                        $file = $data['documents'][$fileType->id];
                        $path = $file->store('medical-facilites', 'public');

                        $medicalFacility->files()->updateOrCreate(
                            ['file_type_id' => $fileType->id],
                            [
                                'file_name' => $file->getClientOriginalName(),
                                'file_type_id' => $fileType->id,
                                'file_path' => $path,
                            ]
                        );
                    }
                }
            }

            Log::create([
                'user_id' => Auth::id(),
                'details' => 'تم تحديث بيانات المنشأة الطبية: ' . $medicalFacility->name,
                'loggable_id' => $medicalFacility->id,
                'loggable_type' => MedicalFacility::class,
                'action' => 'update_medical_facility',
            ]);

            \DB::commit();

            return $medicalFacility;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception('حدث خطأ أثناء تحديث المنشأة الطبية: ' . $e->getMessage());
        }
    }

    public function delete(MedicalFacility $medicalFacility): void
    {
        $name = $medicalFacility->name;

        Log::create([
            'user_id' => Auth::id(),
            'details' => 'تم حذف منشأة طبية: ' . $name,
            'loggable_id' => $medicalFacility->id,
            'loggable_type' => MedicalFacility::class,
            'action' => 'delete_medical_facility',
        ]);

        $medicalFacility->delete();

        redirect()->route('admin.medical-facilities.index')->with('success', 'تم حذف المنشأة الطبية بنجاح');
    }

    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = MedicalFacility::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['ownership'])) {
            $query->where('ownership', $filters['ownership']);
        }

        if (!empty($filters['medical_facility_type_id'])) {
            $query->where('medical_facility_type_id', $filters['medical_facility_type_id']);
        }

        return $query->paginate($perPage);
    }

    protected function createInvoice($medicalFacility)
    {
        $price = Pricing::find(76);
        $getLastInvoiceNumber = Invoice::latest()->first() ? Invoice::latest()->first()->id + 1 : 1;

        Invoice::create([
            'invoice_number' => $getLastInvoiceNumber,
            'invoiceable_id' => $medicalFacility->id,
            'invoiceable_type' => MedicalFacility::class,
            'description' => 'تكلفة إنشاء منشأة طبية',
            'user_id' => Auth::id(),
            'amount' => $price->amount,
            'status' => 'unpaid',
            'pricing_id' => $price->id,
            'branch_id' => auth()->user()->branch_id,
        ]);
    }
}
