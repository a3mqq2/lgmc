@extends('layouts.doctor')

@section('styles')
<style>
/* ===== CSS Variables ===== */
:root {
    --primary-color: #b91c1c;
    --primary-light: #dc2626;
    --primary-dark: #991b1b;
    --blood-red: #7f1d1d;
    --blood-red-light: #a91b1b;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #dc2626;
    --info-color: #b91c1c;
    --secondary-color: #6b7280;
    --light-bg: #fef2f2;
    --card-shadow: 0 10px 25px -5px rgba(185, 28, 28, 0.15), 0 10px 10px -5px rgba(185, 28, 28, 0.08);
    --card-shadow-hover: 0 20px 25px -5px rgba(185, 28, 28, 0.2), 0 10px 10px -5px rgba(185, 28, 28, 0.1);
    --border-radius: 16px;
    --border-radius-sm: 8px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== Helpers ===== */
.text-end { text-align: right !important; }

/* ===== Tabs ===== */
.nav-tabs {
    border-bottom: none;
    margin-bottom: 1.5rem;
    gap: 0.75rem;
    flex-wrap: nowrap;
    overflow-x: auto;
}

.nav-tabs .nav-link {
    border: none;
    border-radius: 50px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    background: #fef2f2;
    color: var(--primary-dark);
    transition: var(--transition);
    white-space: nowrap;
}

.nav-tabs .nav-link.active,
.nav-tabs .nav-link:hover {
    background: linear-gradient(135deg, var(--blood-red) 0%, var(--primary-color) 50%, var(--primary-light) 100%);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(185, 28, 28, 0.3);
}

/* ===== Page Header ===== */
.page-header {
    background: linear-gradient(135deg, var(--blood-red) 0%, var(--primary-color) 50%, var(--primary-light) 100%);
    margin: -1rem -1rem 2rem -1rem;
    padding: 2rem 1rem;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
    color: white;
    box-shadow: var(--card-shadow);
}

.page-header h3 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(127, 29, 29, 0.3);
}

.page-header i {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem;
    border-radius: var(--border-radius-sm);
    margin-left: 0.5rem;
}

/* ===== Enhanced Cards ===== */
.enhanced-card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    overflow: hidden;
    background: white;
}

.enhanced-card:hover {
    box-shadow: var(--card-shadow-hover);
    transform: translateY(-2px);
}

.enhanced-card .card-header {
    background: linear-gradient(135deg, var(--blood-red) 0%, var(--primary-color) 50%, var(--primary-light) 100%);
    border: none;
    padding: 1.25rem 1.5rem;
    font-weight: 600;
    color: white;
}

.enhanced-card .card-body {
    padding: 2rem 1.5rem;
}

/* ===== Status Badges Enhanced ===== */
.badge-under_approve { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color:#92400e;font-weight:600;padding:0.5rem 1rem;border-radius:50px;font-size:0.875rem;box-shadow:0 4px 6px -1px rgba(251,191,36,0.3); }
.badge-under_complete{background:linear-gradient(135deg,#fb923c 0%,#f97316 100%);color:white;font-weight:600;padding:0.5rem 1rem;border-radius:50px;font-size:0.875rem;box-shadow:0 4px 6px -1px rgba(249,115,22,0.3);}
.badge-under_edit{background:linear-gradient(135deg,#34d399 0%,#10b981 100%);color:white;font-weight:600;padding:0.5rem 1rem;border-radius:50px;font-size:0.875rem;box-shadow:0 4px 6px -1px rgba(16,185,129,0.3);}
.badge-active{background:linear-gradient(135deg,#4ade80 0%,#22c55e 100%);color:white;font-weight:600;padding:0.5rem 1rem;border-radius:50px;font-size:0.875rem;box-shadow:0 4px 6px -1px rgba(34,197,94,0.3);}
.badge-expired{background:linear-gradient(135deg,#f87171 0%,#ef4444 100%);color:white;font-weight:600;padding:0.5rem 1rem;border-radius:50px;font-size:0.875rem;box-shadow:0 4px 6px -1px rgba(239,68,68,0.3);}
.badge-banned{background:linear-gradient(135deg,#9ca3af 0%,#6b7280 100%);color:white;font-weight:600;padding:0.5rem 1rem;border-radius:50px;font-size:0.875rem;box-shadow:0 4px 6px -1px rgba(107,114,128,0.3);}

/* ===== Empty State Enhanced ===== */
.empty-state {text-align:center;padding:3rem 2rem;background:linear-gradient(135deg,#fef2f2 0%,#fee2e2 100%);border-radius:var(--border-radius);}
.empty-img{max-width:280px;width:100%;opacity:0.9;filter:drop-shadow(0 10px 20px rgba(0,0,0,0.1));margin-bottom:2rem;}
.empty-state h4{color:var(--secondary-color);font-weight:600;margin-bottom:1rem;}
.empty-state p{color:var(--secondary-color);font-size:1.125rem;margin-bottom:2rem;}

/* ===== Enhanced Buttons ===== */
.btn-enhanced{padding:0.875rem 2rem;border-radius:50px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;transition:var(--transition);border:none;font-size:1rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);}
.btn-enhanced:hover{transform:translateY(-2px);box-shadow:0 10px 20px -5px rgba(0,0,0,0.2);}
.btn-enhanced.btn-primary{background:linear-gradient(135deg,var(--blood-red) 0%,var(--primary-color) 50%,var(--primary-light) 100%);color:white;box-shadow:0 4px 15px rgba(185,28,28,0.3);}
.btn-enhanced.btn-success{background:linear-gradient(135deg,var(--success-color) 0%,#059669 100%);color:white;}
.btn-enhanced.btn-info{background:linear-gradient(135deg,var(--info-color) 0%,#0891b2 100%);color:white;}
.btn-enhanced.btn-secondary{background:linear-gradient(135deg,var(--secondary-color) 0%,#4b5563 100%);color:white;}

/* ===== Status Cards ===== */
.status-card{background:white;border-radius:var(--border-radius);padding:2.5rem;text-align:center;box-shadow:var(--card-shadow);transition:var(--transition);border:2px solid transparent;}
.status-card:hover{transform:translateY(-3px);box-shadow:var(--card-shadow-hover);}
.status-card.success-card{border:2px solid var(--success-color);background:linear-gradient(135deg,rgba(16,185,129,0.05) 0%,rgba(5,150,105,0.05) 100%);}
.status-card.warning-card{border:2px solid var(--warning-color);background:linear-gradient(135deg,rgba(245,158,11,0.05) 0%,rgba(217,119,6,0.05) 100%);}
.status-card.info-card{border:2px solid var(--primary-color);background:linear-gradient(135deg,rgba(185,28,28,0.05) 0%,rgba(220,38,38,0.05) 100%);}
.status-card img{filter:drop-shadow(0 8px 16px rgba(0,0,0,0.15));margin-bottom:1.5rem;}
.status-card h4{font-weight:700;line-height:1.4;margin-bottom:1.5rem;}

/* ===== Info List Enhanced ===== */
.info-list{background:white;border-radius:var(--border-radius);overflow:hidden;box-shadow:var(--card-shadow);}
.info-item{padding:1.25rem 1.5rem;border:none;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;transition:var(--transition);}
.info-item:last-child{border-bottom:none;}
.info-item:hover{background:linear-gradient(135deg,#fef2f2 0%,rgba(185,28,28,0.02) 100%);}
.info-item .info-label{display:flex;align-items:center;gap:0.75rem;color:var(--secondary-color);font-weight:500;}
.info-item .info-label i{width:32px;height:32px;display:flex;align-items:center;justify-content:center;border-radius:var(--border-radius-sm);font-size:0.875rem;}
.info-item .info-label i.fa-clinic-medical{background:linear-gradient(135deg,var(--blood-red),var(--primary-color));color:white;}
.info-item .info-label i.fa-tags{background:linear-gradient(135deg,var(--primary-light),var(--primary-color));color:white;}
.info-item .info-label i.fa-map-marker-alt{background:linear-gradient(135deg,var(--danger-color),var(--primary-color));color:white;}
.info-item .info-label i.fa-phone-alt{background:linear-gradient(135deg,var(--success-color),#059669);color:white;}
.info-item .info-label i.fa-calendar-alt{background:linear-gradient(135deg,var(--warning-color),#d97706);color:white;}
.info-item .info-value{font-weight:600;color:#1f2937;}

/* ===== Animations ===== */
@keyframes fadeInUp{from{opacity:0;transform:translateY(30px);}to{opacity:1;transform:translateY(0);}}
.fade-in-up{animation:fadeInUp 0.6s ease-out;}
@keyframes pulse{0%,100%{opacity:1;}50%{opacity:0.8;}}
.pulse-animation{animation:pulse 2s infinite;}

/* ===== Responsive Design ===== */
@media (max-width:768px){
.page-header{margin:-0.5rem -0.5rem 1.5rem -0.5rem;padding:1.5rem 1rem;}
.page-header h3{font-size:1.5rem;}
.status-card{padding:2rem 1.5rem;}
.enhanced-card .card-body{padding:1.5rem 1rem;}
.info-item{padding:1rem;flex-direction:column;align-items:flex-start;gap:0.5rem;}
.info-item .info-value{text-align:right;width:100%;}
}
</style>
@endsection


@section('content')
<div class="container-fluid px-0">

{{-- ===============================================================
   إذا لم توجد منشأة
================================================================ --}}
@unless(auth('doctor')->user()->medicalFacility)
    <div class="enhanced-card fade-in-up">
        <div class="card-body empty-state">
            <h4 class="text-end mb-3">
                <i class="fas fa-exclamation-circle text-warning ms-1"></i>
                عذرًا، لا توجد لديك منشأة طبية مسجَّلة.
            </h4>

            <img src="{{ asset('assets/images/No data-pana.png') }}" class="empty-img" alt="no-data">

            <p class="mb-4">يمكنك التقديم لإضافة منشأة طبية جديدة والبدء في تقديم الخدمات الطبية.</p>

            <a href="{{ route('doctor.my-facility.create') }}" class="btn-enhanced btn-primary btn-lg pulse-animation">
                <i class="fas fa-plus-circle"></i> اضغط هنا للتقديم
            </a>
        </div>
    </div>
@endunless



{{-- ===============================================================
   إذا وُجدت منشأة
================================================================ --}}
@isset(auth('doctor')->user()->medicalFacility)
@php
    $facility   = auth('doctor')->user()->medicalFacility;
    $statusVal  = $facility->membership_status->value;
    $isPending  = in_array($statusVal, ['under_edit']);
@endphp

@if ($facility->membership_status->value != 'active')

<div class="card">
   <div class="card-body">


{{-- ------------------------ كروت الحالة ------------------------ --}}
<div class="row g-4 mb-3">
   @if($statusVal=='under_complete')
       <div class="col-12">
           <div class="status-card success-card fade-in-up">
               <img src="{{asset('assets/images/celebrate.png')}}" width="90">
               <h4 class="text-success">🎉 تم حفظ بيانات منشأتك بنجاح</h4>
               <p class="text-muted">يرجى رفع الملفات المطلوبة ليتم إرسال الطلب.</p>
               <a href="{{route('doctor.my-facility.upload-documents')}}" class="btn btn-success">رفع المستندات ( اضغط هنا ) </a>
           </div>
       </div>
   @elseif($statusVal=='under_approve')
       <div class="col-12">
           <div class="status-card warning-card fade-in-up">
               <img src="{{asset('assets/images/review.png')}}" width="90">
               <h4 class="text-warning">⏳ طلبك قيد المراجعة</h4>
               <p class="text-muted mb-0">سيتم إشعارك فور الانتهاء.</p>
           </div>
       </div>
   @elseif($statusVal=='under_edit')
       <div class="col-12">
           <div class="status-card success-card fade-in-up">
               <img src="{{asset('assets/images/under_edit.png')}}" width="90">
               <h4 class="text-success">✏️ طلبك يحتاج تعديل</h4>
               <p class="text-muted mb-0">سبب التعديل: {{ $facility->edit_reason ?? 'غير محدد' }}</p>
           </div>
       </div>
       @elseif($statusVal=='under_payment')
       <div class="col-12">
           <div class="status-card success-card fade-in-up">
               <img src="{{asset('assets/images/under_payment.png')}}" width="100">
               <h4 class="text-success m-0"> طلبك قيد السداد  </h4>
               <p class="text-muted h4 mb-0">توجه الى الفرع الخاص بك لسداد قيمة  : {{auth('doctor')->user()->invoices()->where('status','unpaid')->sum('amount')}}  دينار ليبي فقط  </p>
           </div>
       </div>
   @endif
</div>

   </div>
</div>

@endif

{{-- ------------------------ بطاقة التابات ------------------------ --}}
@if($isPending)        {{-- نموذج موحّد للتعديل متاح فقط إذا الحالة تسمح --}}
<form action="{{ route('doctor.my-facility.update',$facility) }}"
      method="POST" enctype="multipart/form-data">
    @csrf  @method('PUT')
@endif

    {{-- ===== شريط التابات ===== --}}
    <ul class="nav nav-tabs" id="facilityTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab"
                    data-bs-target="#tab-info" type="button">المعلومات الاساسية</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab"
                    data-bs-target="#tab-files" type="button">المستندات</button>
        </li>
    </ul>


    <div class="tab-content" id="facilityTabsContent">

        {{-- ================= تبويب المعلومات ================= --}}
        <div class="tab-pane fade show active" id="tab-info">
            <div class="enhanced-card fade-in-up my-3">
                <div class="card-header d-flex justify-content-between">
                    <span><i class="fas fa-info-circle me-2"></i> بيانات المنشأة الطبية</span>
                    <span class="badge {{ $facility->membership_status->badgeClass() }}">
                        {{ $facility->membership_status->label() }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="info-list">

                        {{-- اسم المنشأة --}}
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-clinic-medical"></i> اسم المنشأة</span>
                            @if($isPending)
                                <input name="name" class="form-control"
                                       value="{{ old('name',$facility->name) }}">
                            @else
                                <span class="info-value">{{ $facility->name }}</span>
                            @endif
                        </div>

                    
                        @if (!$isPending)
                                {{-- نوع المنشأة --}}
                        <div class="info-item">
                           <span class="info-label"><i class="fas fa-tags"></i> نوع المنشأة</span>
                           @if($isPending)
                               <select name="type" class="form-select">
                                   <option value="private_clinic"  @selected($facility->type=='private_clinic')>
                                       عيادة خاصة
                                   </option>
                                   <option value="medical_services" @selected($facility->type!='private_clinic')>
                                       خدمات طبية
                                   </option>
                               </select>
                           @else
                               <span class="info-value">
                                   {{ $facility->type=='private_clinic'?'عيادة خاصة':'خدمات طبية' }}
                               </span>
                           @endif
                       </div>
                        @endif

                        {{-- العنوان --}}
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-map-marker-alt"></i> العنوان</span>
                            @if($isPending)
                                <input name="address" class="form-control"
                                       value="{{ old('address',$facility->address) }}">
                            @else
                                <span class="info-value">{{ $facility->address }}</span>
                            @endif
                        </div>

                        {{-- الهاتف --}}
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-phone-alt"></i> رقم الهاتف</span>
                            @if($isPending)
                                <input name="phone_number" class="form-control"
                                       value="{{ old('phone_number',$facility->phone_number) }}">
                            @else
                                <span class="info-value">{{ $facility->phone_number }}</span>
                            @endif
                        </div>

                        {{-- تاريخ الانتهاء --}}
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-calendar-alt"></i> انتهاء الاشتراك</span>
                            <span class="info-value">
                                {{ optional($facility->membership_expiration_date)->format('Y-m-d') ?? 'غير محدد' }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>



        {{-- ================= تبويب المستندات ================= --}}
        <div class="tab-pane fade" id="tab-files">
            <div class="enhanced-card fade-in-up my-3">
                <div class="card-header">
                    <i class="fas fa-file-alt me-2"></i> المستندات
                </div>

                <div class="card-body">
                    <div class="list-group">
                        @forelse($facility->files as $doc)
                            <div class="list-group-item d-flex flex-column flex-md-row
                                        justify-content-between align-items-start align-items-md-center">
                                <div class="flex-grow-1 mb-2 mb-md-0">
                                    <strong>{{ $doc->fileType->name }}</strong><br>
                                    <a href="{{ Storage::url($doc->file_path) }}"
                                       target="_blank" class="small">
                                        عرض الملف <i class="fa fa-eye"></i>
                                    </a>
                                    <div class="small text-muted mt-1">
                                        آخر تعديل: {{ $doc->updated_at->format('Y-m-d H:i') }}
                                    </div>
                                </div>

                                @if($isPending)
                                    <div class="d-flex align-items-center">
                                        <label class="btn btn-outline-secondary btn-sm mb-0 me-2">
                                            <i class="fa fa-upload"></i> تغيير
                                            <input type="file"
                                                   name="files[{{ $doc->id }}]"
                                                   hidden
                                                   onchange="this.parentElement.nextElementSibling.textContent = this.files[0] ? this.files[0].name : ''">
                                        </label>
                                        <span class="small text-truncate" style="max-width:150px"></span>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted">
                                لا توجد مستندات مرفوعة حتى الآن.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div> {{-- /tab-content --}}

    @if($isPending)
        <div class="text-center my-4">
            <button class="btn-enhanced btn-success px-5">
                <i class="fas fa-save"></i> حفظ جميع التعديلات
            </button>
        </div>
    @endif

@if($isPending)
</form>
@endif
@endisset
</div>
@endsection