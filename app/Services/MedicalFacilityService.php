<?php

namespace App\Services;

use App\Models\Log;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Models\FileType;
use App\Models\MedicalFacility;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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


        $doctor = Doctor::find($data['manager_id']);
        if ($doctor->medicalFacility) {
            throw new \Exception('هذا الطبيب لديه منشأة طبية مسجلة بالفعل');
        }


        // check if the doctor is not libyan
        if($doctor->type->value != "libyan")
        {
            throw new \Exception('الطبيب ليس ليبي الجنسية، لا يمكنه تسجيل منشأة طبية');
        }

        // check if medical facility type private_clinic and doctor rank is not ( 3,4,5,6)
        if($data['type'] == 'private_clinic' && !in_array($doctor->rank, [3, 4, 5, 6]))
        {
            throw new \Exception('الطبيب لا يملك الصفة المطلوبة لتسجيل منشأة طبية خاصة');
        }

    
        $data['branch_id'] = $doctor->branch_id;
    
        $lastIssuedDate = $data['last_issued_date'] ?? null;
        unset($data['last_issued_date']);
    
        $medicalFacility = MedicalFacility::create($data);
    
        if ($lastIssuedDate) {
            $medicalFacility->membership_expiration_date = Carbon::parse($lastIssuedDate)->addYear();
            $medicalFacility->membership_status = $medicalFacility->membership_expiration_date < now() ? 'expired' : 'active';
            $medicalFacility->index = MedicalFacility::max('index') + 1;
            $medicalFacility->code = $medicalFacility->branch->code . '-' . 'MF-' . str_pad($medicalFacility->index, 3, '0', STR_PAD_LEFT);
            $medicalFacility->save();

            // create license
            $license = $medicalFacility->licenses()->create([
                'status' => $medicalFacility->membership_status,
                'issued_date' => $lastIssuedDate,
                'expiry_date' => $medicalFacility->membership_expiration_date,
                'created_by' => Auth::id(),
            ]);
        }
        
        return $medicalFacility;
    }
    

    public function update(MedicalFacility $medicalFacility, array $data)
    {
        $doctor = Doctor::find($data['manager_id']);
        if ($doctor->medicalFacility && $doctor->medicalFacility->id !== $medicalFacility->id) {
            throw new \Exception('هذا الطبيب لديه منشأة طبية مسجلة بالفعل');
        }
    
        $data['branch_id'] = $doctor->branch_id;
    
        $lastIssuedDate = $data['last_issued_date'] ?? null;
        unset($data['last_issued_date']);
    
        $medicalFacility->update($data);
    
        if ($lastIssuedDate) {
            $medicalFacility->membership_expiration_date = Carbon::parse($lastIssuedDate)->addYear();
            $medicalFacility->membership_status = $medicalFacility->membership_expiration_date < now() ? 'expired' : 'active';
    
            if ($medicalFacility->branch_id !== $medicalFacility->getOriginal('branch_id')) {
                $medicalFacility->code = $medicalFacility->branch->code . '-MF-' . str_pad($medicalFacility->index, 3, '0', STR_PAD_LEFT);
            }
    
            $medicalFacility->save();
        } else {
            $medicalFacility->membership_status = 'under_payment';
            $medicalFacility->save();
        }
    
        return $medicalFacility;
    }
    

    public function delete(MedicalFacility $medicalFacility)
    {
        DB::transaction(function() use ($medicalFacility) {
            // Delete all associated licenses first using the correct method name
            $medicalFacility->licenses()->delete();
            $medicalFacility->licenses_manager()->delete();
            // Log the deletion
            $name = $medicalFacility->name;
            Log::create([
                'user_id'       => Auth::id(),
                'details'       => 'تم حذف منشأة طبية: ' . $name,
                'loggable_id'   => $medicalFacility->id,
                'loggable_type' => MedicalFacility::class,
                'action'        => 'delete_medical_facility',
            ]);
    
            // Now delete the medical facility
            $medicalFacility->delete();
        });
    }
    
    // في الـ Controller أو Service الذي يستدعي getAll():
    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = MedicalFacility::query();

        if (!empty($filters['q'])) {
            $query->where('name', 'like', '%' . $filters['q'] . '%');
        }


        if(!empty($filters['code']))
        {
            $code = explode('-', $filters['code']);
            if(count($code) == 3)
            {
                $query->where('code', 'like', '%' . $filters['code'] . '%');
            } else {
                $query->where('index', $filters['code']);
            }
        }

        if (!empty($filters['phone'])) {
            $query->where('phone_number', 'like', '%' . $filters['phone'] . '%');
        }

        if (!empty($filters['ownership'])) {
            $query->where('type', $filters['ownership']);
        }


        if (!empty($filters['membership_status'])) {
            if($filters['membership_status'] == "expiring_soon")
            {
                $query->whereBetween('membership_expiration_date', [now(), now()->addDays(30)]);
            } else {
                $query->where('membership_status', $filters['membership_status']);
            }
        }

        if (!empty($filters['manager_id'])) {
            $query->where('manager_id', $filters['manager_id']);
        }

        if(get_area_name() == "user")
        {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        return $query->orderByDesc('id')->paginate($perPage);
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
            'user_id' => auth()->user()->id,
        ]);
    }
}
