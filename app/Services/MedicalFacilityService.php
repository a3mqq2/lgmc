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

    /**
     * Create a new medical facility.
     *
     * @param  array  $data
     * @return \App\Models\MedicalFacility
     */
    public function create(array $data): MedicalFacility
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

        if(request('manager_id'))
        {
            $checkIfManagerHaveFacilityBefore = MedicalFacility::where('manager_id', request('manager_id'))->first();
            if($checkIfManagerHaveFacilityBefore) {
                return redirect()->back()->withErrors(['هذا المدير لديه منشأة طبية مسجلة بالفعل']);
            }


            // manager id should have licence active
            $manager = Doctor::find(request('manager_id'));
            if(!$manager->licenses()->where('status', 'active')->first()) {
                return redirect()->back()->withErrors(['هذا المدير ليس لديه ترخيص نشط']);
            }
        }

        $medicalFacility = MedicalFacility::create($data);
        if(isset($data['manager_id'])) {
            // get first licence add medical facility id to it
            $manager = Doctor::find(request('manager_id'));
            $licence = $manager->licenses()->where('status', 'active')->first();
            $licence->update([
                'medical_facility_id' => $medicalFacility->id
            ]);
        }
        $file_types = FileType::where('type', 'medical_facility')
         ->get();

     foreach ($file_types as $file_type) {
         if (isset($data['documents'][$file_type->id])) {
             $file = $data['documents'][$file_type->id];

             $path = $file->store('medical-facilites','public');
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
        ]);

        return $medicalFacility;
    }

    /**
     * Update an existing medical facility.
     *
     * @param  \App\Models\MedicalFacility  $medicalFacility
     * @param  array  $data
     * @return \App\Models\MedicalFacility
     */

     public function update(MedicalFacility $medicalFacility, array $data): MedicalFacility
{

    \DB::beginTransaction();

    try {
        $data['user_id'] = Auth::id();

        if($data['manager_id'] == null)
        {
            unset($data['manager_id']);
        }
        // معالجة تاريخ بدء النشاط
        if (isset($data['date'])) {
            $data['activity_start_date'] = $data['date'];
            unset($data['date']);
        }

        // التحقق من أن المدير ليس لديه منشأة طبية أخرى
        if (isset($data['manager_id']) && $medicalFacility->manager_id != $data['manager_id']) {
            $existingFacility = MedicalFacility::where('manager_id', $data['manager_id'])->where('id', '!=', $medicalFacility->id)->first();
            if ($existingFacility) {
                throw new \Exception('هذا المدير لديه منشأة طبية مسجلة بالفعل');
            }
        }

        // تحديث بيانات المنشأة
        $medicalFacility->update($data);

        // تحديث الملفات (المستندات)
        if (isset($data['documents'])) {
            $fileTypes = FileType::where('type', 'medical_facility')->get();

            foreach ($fileTypes as $fileType) {
                if (isset($data['documents'][$fileType->id])) {
                    $file = $data['documents'][$fileType->id];

                    // حفظ الملف في التخزين العام
                    $path = $file->store('medical-facilites', 'public');

                    // تحديث المستند إذا كان موجودًا، أو إنشاؤه إذا لم يكن موجودًا
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

        // تسجيل العملية في السجلات
        Log::create([
            'user_id' => Auth::id(),
            'details' => 'تم تحديث بيانات المنشأة الطبية: ' . $medicalFacility->name,
        ]);

        \DB::commit();

        return $medicalFacility;
    } catch (\Exception $e) {
        \DB::rollBack();
        throw new \Exception('حدث خطأ أثناء تحديث المنشأة الطبية: ' . $e->getMessage());
    }
}


    /**
     * Delete a medical facility.
     *
     * @param  \App\Models\MedicalFacility  $medicalFacility
     * @return void
     */
    public function delete(MedicalFacility $medicalFacility): void
    {
        $name = $medicalFacility->name;

        $medicalFacility->delete();

        // Create a log entry
        Log::create([
            'user_id' => Auth::id(),
            'details' => 'تم حذف منشأة طبية: ' . $name,
        ]);
    }

    /**
     * Fetch and filter medical facilities.
     *
     * @param  array  $filters
     * @param  int    $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = MedicalFacility::query();

        // Apply filters
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
        $invoice = Invoice::create([
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
