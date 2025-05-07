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
                    <a class="nav-link fs-14 active " data-bs-toggle="tab" href="#facilities" role="tab">
                        <i class="fa fa-hospital d-inline-block d-m"></i> <span class="d-md-inline-block">منشآتي الطبية</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-14"  href="{{route('doctor.dashboard', ['licences' => 1])}}" role="tab">
                        <i class="ri-list-unordered d-inline-block d-m"></i> <span class=" d-md-inline-block">أذونات المزاولة</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-14  "  href="{{route('doctor.dashboard', ['requests' => 1])}}" role="tab">
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
              <form action="{{ route('doctor.medical-facilities.store') }}"
                    method="POST"
                    enctype="multipart/form-data">
                @csrf
  
                <input type="hidden" name="type" value="{{ request('type') }}">
  
                <div class="mb-4 text-primary">
                  @if(!request('type'))
                    <h4>حدد نوع المنشأة</h4>
                  @else
                    <h4>تسجيل منشأة جديدة</h4>
                  @endif
                </div>
  
                @if(!request('type'))
                  <div class="row">
                    <div class="col-md-6">
                      <a href="?type=1">
                        <div class="card border border-primary p-3">
                          <h3 class="text-center">عيادة فردية <i class="fa fa-user"></i></h3>
                        </div>
                      </a>
                    </div>
                    <div class="col-md-6">
                      <a href="?type=2">
                        <div class="card border border-primary p-3">
                          <h3 class="text-center">خدمات طبية <i class="fa fa-users"></i></h3>
                        </div>
                      </a>
                    </div>
                  </div>
                @else
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <label class="form-label" for="name">اسم المنشأة</label>
                      <input type="text" id="name" name="name" class="form-control" required>
                    </div>
  
                    @if(get_area_name()=='admin')
                      <div class="col-md-12 mb-3">
                        <label class="form-label" for="branch">الفرع</label>
                        <select name="branch_id" id="branch" class="form-select select2">
                          <option value="">حدد الفرع</option>
                          @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    @else
                      <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                    @endif
  
                    <div class="col-md-6 mb-3">
                      <label class="form-label" for="address">الموقع</label>
                      <input type="text" id="address" name="address" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="form-label" for="phone_number">رقم الهاتف</label>
                      <input type="text" id="phone_number" name="phone_number" class="form-control" maxlength="10" required>
                    </div>
                  </div>
                @endif
  
                @if(request('type'))
                  <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center">
                      <h4 class="mb-0">📑 المستندات المطلوبة</h4>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        @foreach($file_types as $ft)
                            <div class="mb-3">
                                <label>{{ $ft->name }} @if($ft->is_required)*@endif</label>
                                <input type="file"
                                    name="documents[{{ $ft->id }}]"
                                    class="form-control"
                                    {{ $ft->is_required ? 'required' : '' }}
                                    accept=".pdf,image/*">
                            </div>
                            @endforeach
                      </div>
                    </div>
                  </div>
                @endif
  
                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary">تسجيل المنشأة</button>
                  <a href="{{ route('doctor.dashboard', ['requests' => 1]) }}" class="btn btn-secondary ms-2">إلغاء</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
  
    </div>
  </div>
  
  @endsection
  
  @section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
  $(function(){
    // اسم الملف وحالة الرفع
    $(document).on('change', 'input[type="file"]', function(){
      var fileId   = this.id.split('_')[1];
      var fileName = $(this).val().split('\\').pop();
      $('#file_' + fileId).siblings('.form-control').val(fileName);
      $('#status_' + fileId)
        .text('✅ تم الرفع: ' + fileName)
        .removeClass('text-muted')
        .addClass('text-success');
    });
  });
  </script>
  @endsection