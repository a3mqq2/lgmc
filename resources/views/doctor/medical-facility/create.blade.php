@extends('layouts.doctor')

@push('styles')
<style>
/* ===== Toggle Switch ===== */
.switch             { position:relative; display:inline-block; width:52px; height:26px; }
.switch input        { opacity:0; width:0; height:0; }
.slider              { position:absolute; inset:0; cursor:pointer; background:#ced4da; transition:.4s; border-radius:26px; }
.slider::before      { content:""; position:absolute; height:20px; width:20px; left:3px; top:3px; background:#fff; transition:.4s; border-radius:50%; }
.switch input:checked + .slider          { background:#28a745; }
.switch input:checked + .slider::before  { transform:translateX(26px); }
.switch input:focus   + .slider          { box-shadow:0 0 1px #28a745; }

/* ===== Empty-state image ===== */
.empty-img   { max-width:220px; width:100%; opacity:.75; }

/* ===== Card Hover Effect ===== */
.facility-card {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}
.facility-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    border-color: #0d6efd;
}
.facility-card.disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.facility-card.disabled:hover {
    transform: none;
    box-shadow: none;
    border-color: #e9ecef;
}
</style>
@endpush

@php
    // التحقق من رتبة الطبيب
    $allowedRanks = [3, 4, 5, 6];
    $canCreatePrivateClinic = in_array(auth()->user()->doctor_rank_id, $allowedRanks);
    
    // إذا لم يكن مسموحاً له بإنشاء عيادة، توجيهه مباشرة للخدمات الطبية
    if (!$canCreatePrivateClinic && !request('type')) {
        request()->merge(['type' => 'medical-services']);
    }
@endphp

@section('content')
<div class="container-fluid px-0">
  <div class="card shadow-sm">
    <div class="card-body">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">
          <i class="ri-hospital-line me-2"></i>
          طلب تقديم منشأة طبية جديدة
        </h3>
      </div>

      @if (!request('type') || (!$canCreatePrivateClinic && request('type') == 'private-clinic'))
         @if($canCreatePrivateClinic)
            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                <i class="ri-information-line fs-4 me-2"></i>
                <div>
                    <p class="mb-0 fw-bold">اولاً: حدد نوع المنشأة الطبية التي ترغب في تقديمها</p>
                </div>
            </div>
         @else
            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                <i class="ri-alert-line fs-4 me-2"></i>
                <div>
                    <p class="mb-0">رتبتك الحالية تسمح لك بإنشاء <strong>خدمات طبية فقط</strong>.</p>
                    <small>لإنشاء عيادة فردية، يجب أن تكون رتبتك: استشاري، استشاري أول، نائب، أو نائب أول</small>
                </div>
            </div>
         @endif
         
         <div class="row">
            @if($canCreatePrivateClinic)
            <div class="col-md-6 mb-4">
              <a href="?type=private-clinic" class="text-decoration-none">
               <div class="card border facility-card h-100">
                  <div class="card-body text-center py-5">
                     <div class="mb-4">
                        <i class="ri-user-heart-line text-primary" style="font-size: 80px;"></i>
                     </div>
                     <h4 class="mb-2">عيادة فردية</h4>
                  </div>
               </div>
              </a>
            </div>
            @endif
            
            <div class="col-md-{{$canCreatePrivateClinic ? '6' : '12'}} mb-4">
               <a href="?type=medical-services" class="text-decoration-none">
                  <div class="card border facility-card h-100">
                     <div class="card-body text-center py-5">
                        <div class="mb-4">
                           <i class="ri-hospital-fill text-success" style="font-size: 80px;"></i>
                        </div>
                        <h4 class="mb-2">خدمات طبية</h4>
                     </div>
                  </div>
               </a>
            </div>
         </div>
         
      @else 
         @if (request('type') == "private-clinic" && $canCreatePrivateClinic)
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('doctor.my-facility.create') }}" class="btn btn-light me-3">
                    <i class="ri-arrow-left-line"></i> رجوع
                </a>
                <h4 class="mb-0">
                    <i class="ri-user-heart-line text-primary me-2"></i>
                    ثانياً: قم بتعبئة البيانات التالية للعيادة الفردية
                </h4>
            </div>
            
            <form action="{{route('doctor.my-facility.store')}}" method="POST" class="needs-validation" novalidate>
               @csrf
               <input type="hidden" name="type" value="private_clinic">
               
               <div class="card border-0 shadow-sm">
                  <div class="card-body">
                     @include('doctor.medical-facility.private_clinic_form')
                  </div>
               </div>
               
               <div class="text-end mt-4">
                  <button type="button" class="btn btn-light me-2" onclick="window.location.href='{{ route('doctor.my-facility.create') }}'">
                     <i class="ri-close-line me-1"></i> إلغاء
                  </button>
                  <button type="submit" class="btn btn-primary">
                     <i class="ri-save-line me-1"></i> حفظ البيانات
                  </button>
               </div>
            </form>
         @endif

         @if (request('type') == "medical-services" || (!$canCreatePrivateClinic && request('type') == "private-clinic"))
            <div class="d-flex align-items-center mb-4">
                @if($canCreatePrivateClinic)
                <a href="{{ route('doctor.my-facility.create') }}" class="btn btn-light me-3">
                    <i class="ri-arrow-left-line"></i> رجوع
                </a>
                @endif
                <h4 class="mb-0">
                    <i class="ri-hospital-fill text-success me-2"></i>
                    {{$canCreatePrivateClinic ? 'ثانياً:' : ''}} قم بتعبئة البيانات التالية للخدمات الطبية
                </h4>
            </div>
            
            <form action="{{route('doctor.my-facility.store')}}" method="POST" class="needs-validation" novalidate>
               @csrf
               <input type="hidden" name="type" value="medical_services">
               
               <div class="card border-0 shadow-sm">
                  <div class="card-body">
                     @include('doctor.medical-facility.medical_services_form')
                  </div>
               </div>
               
               <div class="text-end mt-4">
                  @if($canCreatePrivateClinic)
                  <button type="button" class="btn btn-light me-2" onclick="window.location.href='{{ route('doctor.my-facility.create') }}'">
                     <i class="ri-close-line me-1"></i> إلغاء
                  </button>
                  @endif
                  <button type="submit" class="btn btn-primary">
                     <i class="ri-save-line me-1"></i> حفظ البيانات
                  </button>
               </div>
            </form>
         @endif
      @endif

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
@endpush