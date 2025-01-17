@extends('layouts.doctor')
@section('content')
    

<div class="row">
   <div class="col-lg-12">
       <div>
         <div class="d-flex">
            <!-- Nav tabs -->
            <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link fs-14" href="{{route('doctor.dashboard', ['overview' => 1])}}" role="tab">
                        <i class="ri-airplay-fill d-inline-block d-m"></i> <span class=" d-md-inline-block">بياناتي الآساسية</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-14"  href="{{route('doctor.dashboard', ['licences' => 1])}}" role="tab">
                        <i class="ri-list-unordered d-inline-block d-m"></i> <span class=" d-md-inline-block">أذونات المزاولة</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-14"  href="{{route('doctor.dashboard', ['tickets' => 1])}}"  role="tab">
                        <i class="ri-price-tag-line d-inline-block d-m"></i> <span class=" d-md-inline-block">تذاكر الدعم</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-14 active "  href="{{route('doctor.dashboard', ['requests' => 1])}}" role="tab">
                        <i class="ri-folder-4-line d-inline-block d-m"></i> <span class=" d-md-inline-block">الطلبات</span>
                    </a>
                </li>
                <li class="nav-item">
                 <a class="nav-link fs-14" data-bs-toggle="tab" href="{{route('doctor.dashboard', ['invoices' => 1])}}"  role="tab">
                        <i class="fa fa-file d-inline-block d-m"></i> <span class=" d-md-inline-block">الفواتير</span>
                    </a>
                </li>
            </ul>
        </div>
           <div class="tab-content pt-4 text-muted">
           <div class="tab-pane active" id="requests" role="tabpanel">
                  <div class="card">
                        <div class="card-body">
                           <div class="col-mb-12 mb-2">
                              <h4 class="main-content-label mb-3">  اضافة طلب جديد     </h4>
                           </div>
                           <form action="{{route('doctor.doctor-requests.store')}}" method="POST">
                              @csrf
                              <div class="col-md-12 mb-3">
                                 <label for="pricing_id" class="form-label">اختيار نوع الطلب <span class="text-danger">*</span></label>
                                 <select name="pricing_id" id="pricing_id" class="form-control" required>
                                     <option value="">اختر نوع الطلب</option>
                                     @foreach(App\Models\Pricing::where('type','service')->where('doctor_type', auth('doctor')->user()->type)->get() as $pricing)
                                         <option value="{{ $pricing->id }}" {{ old('pricing_id') == $pricing->id ? 'selected' : '' }}>
                                             {{ $pricing->name }} - {{ number_format($pricing->amount, 2) }} د.ل
                                         </option>
                                     @endforeach
                                 </select>
                             </div>
                             <div class="mb-3">
                              <label for="notes" class="form-label">الملاحظات</label>
                              <textarea name="notes" id="notes" class="form-control mb-3" rows="3" placeholder="أضف ملاحظات إضافية">{{ old('notes') }}</textarea>

                              <button type="submit" class="btn btn-success ml-3">إضافة الطلب</button>
                              <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary ms-2">إلغاء</a>
                           </form>
                       </div>
                       <div class="d-flex justify-content-end">

                    </div>
                        </div>
                  </div>
           </div>
        </div>
        
       </div>
   </div>
</div>


@endsection