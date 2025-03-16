<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Licence;
use App\Models\Pricing;
use App\Enums\DoctorType;
use App\Models\Specialty;
use App\Models\Institution;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DoctorsSheetImport implements ToModel, WithHeadingRow
{
    protected $doctorRankMap = [
        'طبيب' => 1,
        'اخصائي' => 3,
        'استشاري' => 6,
    ];

    public function model(array $row)
    {
        if (empty($row['alasm'])) {
            return null;
        }

        $specialty = null;
        if (!empty($row['altkhss']) && $row['altkhss'] != "ممارس عام") {
            $specialty = Specialty::firstOrCreate(['name' => $row['altkhss']]);
        }

        $institution = null;
        if (!empty($row['gh_alaaml'])) {
            $institution = Institution::firstOrCreate(['name' => $row['gh_alaaml'], 'branch_id' => 5]);
        }


        // skip the row if doctor already exists
        if (Doctor::where('name', $row['alasm'])->exists()) {
            return null;
        }


        $doctor = new Doctor([
            'doctor_number' => $row['aadoy'],
            'name' => $row['alasm'],
            'phone' => 0 . $row['rkm_alhatf'],
            'address' => $row['alakam'],
            'doctor_rank_id' => $this->doctorRankMap[$row['alsf']] ?? null,
            'specialty_1_id' => $specialty?->id,
            'institution_id' => $institution?->id,
            'certificate_of_excellence_date' => !empty($row['almohl']) && is_numeric(preg_replace('/\D/', '', $row['almohl'])) 
                ? preg_replace('/\D/', '', $row['almohl']) . '-01-01' 
                : null,
            'date_of_birth' => !empty($row['almylad']) && is_numeric(preg_replace('/\D/', '', $row['almylad'])) 
                ? preg_replace('/\D/', '', $row['almylad']) . '-01-01' 
                : null,
            'registered_at' => $this->parseDate($row['alantsab']),
            'branch_id' => 5,
            'code' => $row['aadoy'],
            'type' => "libyan",
            'country_id' => 1,
        ]);

        $doctor->save();

        if (!empty($row["tgdyd_2026"])) {
            $expiryDate = $this->parseDate($row["tgdyd_2026"]);

            if ($expiryDate->format('m-d') === '12-31') {
                $issueDate = $expiryDate->copy()->startOfYear();
            } else {
                $issueDate = $expiryDate->copy()->addDay()->subYear();
            }

            Licence::create([
                'licensable_id' => $doctor->id,
                'licensable_type' => Doctor::class,
                'issued_date' => $issueDate->format('Y-m-d'),
                'expiry_date' => $expiryDate->format('Y-m-d'),
                'status' => $expiryDate->isPast() ? 'expired' : 'active',
                'doctor_id' => $doctor->id,
                'branch_id' => 5,
                'created_by' => auth()->id(),
                'doctor_type' => "libyan",
            ]);


            $doctor->update([
                "membership_status" => $expiryDate->isPast() ? 'inactive' : 'active',
                "membership_expiration_date" => $expiryDate->format('Y-m-d'),
            ]);

            // create invoice for membership

           if($expiryDate->isPast()){
                if($doctor->type ==  DoctorType::Libyan)
                {
                    if($doctor->doctor_rank_id == 1)
                    {
                        $price = Pricing::find(1);
                    } else if($doctor->doctor_rank_id == 2) 
                    {
                        $price = Pricing::find(2);
                    } else if($doctor->doctor_rank_id == 3)
                    {
                        $price = Pricing::find(3);
                    } else if($doctor->doctor_rank_id == 4)
                    {
                        $price = Pricing::find(4);
                    } else if($doctor->doctor_rank_id == 5)
                    {
                        $price = Pricing::find(5);  
                    }else if($doctor->doctor_rank_id == 6)
                    {
                        $price = Pricing::find(6);  
                    }
        
                } else if($doctor->type == DoctorType::Foreign)
                {
                    if($doctor->doctor_rank_id == 1)
                    {
                        $price = Pricing::find(13);
                    } else if($doctor->doctor_rank_id == 2) 
                    {
                        $price = Pricing::find(14);
                    } else if($doctor->doctor_rank_id == 3)
                    {
                        $price = Pricing::find(15);
                    } else if($doctor->doctor_rank_id == 4)
                    {
                        $price = Pricing::find(16);
                    } else if($doctor->doctor_rank_id == 5)
                    {
                        $price = Pricing::find(17);  
                    }else if($doctor->doctor_rank_id == 6)
                    {
                        $price = Pricing::find(18);  
                    }
                } else if($doctor->type == DoctorType::Visitor) {
                    if($doctor->doctor_rank_id == 3 || $doctor->doctor_rank_id == 4)
                    {
                        $price = Pricing::find(25);
                    }
        
        
                    if($doctor->doctor_rank_id == 5)
                    {
                        $price = Pricing::find(26);
                    }
        
        
                    if($doctor->doctor_rank_id == 6)
                    {
                        $price = Pricing::find(27);
                    }
        
        
                    if(!$price)
                    {
                        return redirect()->back()->withInput()->withErrors(['لا يمكن اضافة طبيب زائر بدون تحديد الرتبة الصحيحة']);
                    }
        
                    
                } else if($doctor->type == DoctorType::Palestinian) {
        
                    if($doctor->doctor_rank_id == 1)
                    {
                        $price = Pricing::find(53);
                    } else if($doctor->doctor_rank_id == 2) 
                    {
                        $price = Pricing::find(54);
                    } else if($doctor->doctor_rank_id == 3)
                    {
                        $price = Pricing::find(55);
                    } else if($doctor->doctor_rank_id == 4)
                    {
                        $price = Pricing::find(56);
                    } else if($doctor->doctor_rank_id == 5)
                    {
                        $price = Pricing::find(57);  
                    }else if($doctor->doctor_rank_id == 6)
                    {
                        $price = Pricing::find(58);  
                    }
        
            }
                
                $data = [
                    'invoice_number' => "RGS-" . Invoice::count() + 1,
                    'invoiceable_id' => $doctor->id,
                    'invoiceable_type' => 'App\Models\Doctor',
                    'description' => "رسوم العضوية الخاصة بالطبيب",
                    'user_id' => auth()->id(),
                    'amount' => $price->amount,
                    'pricing_id' => $price->id,
                    'status' => 'unpaid',
                    'branch_id' => auth()->user()->branch_id,
                ];
        
        
                $invoice = Invoice::create($data);
           }

        }

        return $doctor;
    }

    private function parseDate($date)
    {
        try {
            if (empty($date) || (!is_string($date) && !is_numeric($date))) {
                return null;
            }

            $cleanedDate = trim(preg_replace('/[^0-9\-\/\.]/', '', $date));

            if (strlen($cleanedDate) < 4) {
                return null;
            }

            return is_numeric($cleanedDate)
                ? Carbon::instance(Date::excelToDateTimeObject($cleanedDate))
                : Carbon::parse(str_replace(['/', '.'], '-', $cleanedDate));

        } catch (\Exception $e) {
            Log::warning('Date parsing failed', [
                'original_date' => $date,
                'cleaned_date' => $cleanedDate ?? null,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
