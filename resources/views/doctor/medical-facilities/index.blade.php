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
                    <a class="nav-link fs-14 active "  href="{{route('doctor.dashboard', ['requests' => 1])}}" role="tab">
                        <i class="ri-folder-4-line d-inline-block d-m"></i> <span class=" d-md-inline-block">اوراق الخارج</span>
                    </a>
                </li>
                <li class="nav-item">
                 <a class="nav-link fs-14"  href="{{route('doctor.dashboard', ['invoices' => 1])}}"  role="tab">
                        <i class="fa fa-file d-inline-block d-m"></i> <span class=" d-md-inline-block">الفواتير</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link fs-14"    href="{{route('doctor.dashboard', ['change-password' => 1])}}"   role="tab">
                           <i class="ri-lock-password-line d-inline-block d-m"></i> <span class=" d-md-inline-block">تغيير كلمة المرور</span>
                       </a>
                   </li>

                <li class="nav-item">
                    <a class="nav-link fs-14" href="{{route('doctor.logout')}}"  role="tab">
                           <i class="ri-logout-box-line d-inline-block d-m"></i> <span class=" d-md-inline-block">تسجيل خروج</span>
                       </a>
                   </li>


            </ul>
        </div>
           <div class="tab-content pt-4 text-muted">
           <div class="tab-pane active" id="facilities" role="tabpanel">


               <div class="card">
                  <div class="card-body">
                     <div class="row">
                        @foreach ($facilities as $facility)
                           <a href="">
                              <div class="card">
                                 <div class="card-body">
                                    <div class="d-flex align-items-center">
                                       <div class="avatar-sm flex-shrink-0">
                                          <span class="avatar-title bg-soft-light text-white rounded-2 fs-2">
                                             <i class="fa fa-hospital"></i>
                                          </span>
                                       </div>
                                       <div class="flex-grow-1 ms-3">
                                          <h4 class="fs-4 mb-3 text-dark"><span class="counter-value" data-target="{{ $facility->name }}">{{ $facility->name }}</span></h4>
                                       </div>
                                    </div>
                                 </div>
                           </div>
                           </a>
                        @endforeach
                     </div>
                  </div>
               </div>
           </div>
        </div>
        
       </div>
   </div>
</div>


@endsection