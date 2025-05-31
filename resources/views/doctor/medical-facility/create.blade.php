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
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
  <div class="card shadow-sm">
    <div class="card-body">

      <h3 class="fw-bold text-primary text-end mb-4">
         طلب تقديم منشآه طبية جديدة
      </h3>

      @if (!request('type'))
         <p class="font-weight-bold h4">اولاََ : حدد نوع المنشآة الطبية التي ترغب في تقديمها</p>
         <div class="row">
            <div class="col-md-6">
              <a href="?type=private-clinic">
               <div class="card border mt-3">
                  <div class="card-body m-auto">
                     <img src="{{asset('assets/images/user-doctor.png')}}" width="100" alt="">
                     <p class="text-center h4 mt-2">عيادة فردية</p>
                  </div>
               </div>
              </a>
            </div>
            <div class="col-md-6">
               <a href="?type=medical-services">
                  <div class="card border mt-3">
                     <div class="card-body m-auto">
                        <i class="fa fa-building text-primary" style="font-size: 100px !important;"></i>
                        <p class="text-center h4 mt-2"> خدمات طبية </p>
                     </div>
                  </div>
               </a>
            </div>
         </div>
         @else 
         @if (request('type') == "private-clinic")
            <p class="font-weight-bold h4">ثانياََ : قم بتعبئة البيانات التالية للعيادة الفردية</p>
            <form action="{{route('doctor.my-facility.store')}}" method="POST">
               @csrf
               <input type="hidden" name="type" value="private_clinic">
               @include('doctor.medical-facility.private_clinic_form')
               
               <button class="btn-primary btn mt-2">حفظ</button>
            </form>
             
         @endif
      @endif

    </div>
  </div>
</div>
@endsection
