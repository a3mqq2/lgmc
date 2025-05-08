@extends('layouts.doctor')
@section('content')



<div class="tab-pane {{request('licences') ? "active" : ""}} " id="licences" role="tabpanel">
   <div class="card">
       <div class="card-body">
           <h4 class="font-weight-bold text-primary">
               <i class="fas fa-id-card text-primary"></i> اذونات مزاولة 
           </h4>

           <div class="row">


               {{-- create button --}}
               <div class="col-md-12">
                     <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('doctor.licences.create') }}" class="btn btn-success text-light">
                              <i class="fas fa-plus"></i> اضافة اذن مزاولة جديد
                        </a>
                     </div>
               </div>

                   @if (auth('doctor')->user()->licenses->last() && auth('doctor')->user()->licenses->last()->expiry_date < now())
                   <div class="col-md-12">
                       <div class="alert alert-danger">
                           <i class="fas fa-exclamation-triangle"></i> يبدو ان اخاذونات مزاولة ة لديك قد انتهت، يرجى تجديد اذونات مزاولة ة للمتابعة.
                       </div>
                   @endif
                   @if (!auth('doctor')->user()->licenses->last() )
                   <div class="col-md-12">
                       <div class="alert alert-danger">
                           <i class="fas fa-exclamation-triangle"></i> يبدو ان ليس لديك اذونات مزاولة    يرجى اضافة اذونات  للمتابعة.
                       </div>
                   </div>
                   @endif



           </div>

           <div class="list-group p-3">
               @foreach (auth('doctor')->user()->licenses as $licence)
               <div class="list-group-item border-3">
                   <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                       <h2 class="text-primary" style="font-weight: bold!important;">رقم الإذن : {{ "#".$licence->id }}</h2>
                   </div>
                   <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                       <span><i class="fas fa-user text-info"></i> ااذونات مزاولة  له:</span>
                       <span>{{ $licence->licensable->name }}</span>
                   </div>
                   <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                       <span><i class="fas fa-calendar-alt text-info"></i> تاريخ الإصدار:</span>
                       <span>{{ $licence->issued_date }}</span>
                   </div>
                       <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                           <span><i class="fas fa-id-card text-info"></i>  حالة اذونات مزاولة ة  :</span>
                           <span>{!! $licence->status_badge !!}</span>
                           </div>
                       <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                           <span><i class="fas fa-laptop text-info"></i> مكان العمل :</span>
                           <span>{{ $licence->medicalFacility->name }}</span>
                       </div>
                   <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                       <span><i class="fas fa-calendar-check text-info"></i> تاريخ الانتهاء:</span>
                       <span>{{ $licence->expiry_date }}</span>
                   </div>
                   
                   @if ($licence->status == 'active')
                   <div class="d-flex justify-content-between align-items-center border-bottom pb-3 pt-3">
                      <span><i class="fas fa-download text-info"></i> تحميل اذونات مزاولة ة :</span>
                          <a href="{{route('doctor.licences.print', $licence)}}" class="btn btn-primary text-light">تحميل</a>
                  </div>
                   @endif
                   
               </div>
               @endforeach
           </div>
       </div>
   </div>
   </div>


@endsection

@section('scripts')
<script>
    function validatePasswords() {
        let newPassword = document.getElementById('password').value;
        let confirmPassword = document.getElementById('password_confirmation').value;
        if (newPassword !== confirmPassword) {
            alert('كلمة المرور الجديدة وتأكيد كلمة المرور غير متطابقين!');
            return false;
        }
        return true;
    }

    function togglePassword(fieldId) {
        let field = document.getElementById(fieldId);
        let eyeIcon = document.getElementById('eye_' + fieldId);
        if (field.type === "password") {
            field.type = "text";
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            field.type = "password";
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection
