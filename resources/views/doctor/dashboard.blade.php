@extends('layouts.doctor')
@section('content')


@if (auth('doctor')->user()->membership_status == \App\Enums\MembershipStatus::Active)
    
<div class="row">
    <div class="col-lg-12">
      
            <div class="tab-content pt-4 text-muted">
                     <div class="tab-pane {{(!request('licences') && !request('tickets') && !request('requests') && !request('invoices') && !request('change-password') ) ? "active" : ""}} " id="overview" role="tabpanel">
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="card">
                                     <div class="card-body">
                                        <h4 class="font-weight-bold text-primary" style="font-weight: bold!important;">
                                            <i class="fas fa-user text-primary"></i> البيانات الشخصية
                                        </h4>
                                        <div class="list-group">
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-id-badge text-info"></i> نوع الطبيب</span>
                                                <span class="badge bg-primary">
                                                    {{ auth('doctor')->user()->type->label() }}
                                                </span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-barcode text-info"></i> كود الطبيب</span>
                                                <span>{{ auth('doctor')->user()->code }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-user text-info"></i> الاسم</span>
                                                <span>{{ auth('doctor')->user()->name }}</span>
                                            </div>
                                           
                                            @if (isset(auth('doctor')->user()->name_en))
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                               <span><i class="fas fa-user-circle text-info"></i> الاسم بالإنجليزية</span>
                                               <span>{{ auth('doctor')->user()->name_en }}</span>
                                           </div>
                                            @endif

                                            @if (auth('doctor')->user()->type == "libyan")
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-id-card text-info"></i> الرقم الوطني</span>
                                                <span>{{ auth('doctor')->user()->national_number }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-female text-info"></i> اسم الأم</span>
                                                <span>{{ auth('doctor')->user()->mother_name }}</span>
                                            </div>
                                            @endif


                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-flag text-info"></i> الدولة</span>
                                                <span>{{ auth('doctor')->user()->country->name ?? '-' }}</span>
                                            </div>


                                            @if (auth('doctor')->user()->type == "libyan")
                                               <div class="list-group-item d-flex justify-content-between align-items-center">
                                                   <span><i class="fas fa-birthday-cake text-info"></i> تاريخ الميلاد</span>
                                                   <span>{{ auth('doctor')->user()->date_of_birth ? auth('doctor')->user()->date_of_birth->format('Y-m-d') : '-' }}</span>
                                               </div>
                                               <div class="list-group-item d-flex justify-content-between align-items-center">
                                                   <span><i class="fas fa-heart text-info"></i> الحالة الاجتماعية</span>
                                                   <span>{{ auth('doctor')->user()->marital_status->label() }}</span>
                                               </div>
                                            @endif


                                     
                                         
                                        </div>
                                        <h4 class="font-weight-bold text-primary mt-4" style="font-weight: bold!important;">
                                            <i class="fas fa-phone text-primary"></i>  بيانات التواصل  
                                        </h4>
                                        <div class="list-group">
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-phone text-info"></i> رقم الهاتف </span>
                                                <span>{{ auth('doctor')->user()->phone }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fab fa-whatsapp-square text-info"></i> رقم  الواتساب  </span>
                                                <span>{{ auth('doctor')->user()->phone_2 }}</span>
                                            </div>
                                            {{-- email --}}
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fa fa-envelope text-info"></i>  البريد الالكتروني     </span>
                                                <span>{{ auth('doctor')->user()->email }}</span>
                                            </div>
                                        </div>
                                         <h4 class="font-weight-bold text-primary mt-4 ">
                                             <i class="fas fa-info-circle text-primary"></i> بيانات إضافية
                                         </h4>
                                         <div class="list-group">
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-venus-mars text-info"></i> الجنس</span>
                                                 <span>{{ auth('doctor')->user()->gender->label() }}</span>
                                             </div>

                                             @if (auth('doctor')->user()->passport_number)
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-passport text-info"></i> رقم الجواز</span>
                                                    <span>{{ auth('doctor')->user()->passport_number }}</span>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-calendar-alt text-info"></i> تاريخ انتهاء الجواز</span>
                                                    <span>{{ auth('doctor')->user()->passport_expiration ? auth('doctor')->user()->passport_expiration->format('Y-m-d') : '-' }}</span>
                                                </div>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-map-marker-alt text-info"></i> الإقامة</span>
                                                    <span>{{ auth('doctor')->user()->address }}</span>
                                                </div>
                                             @endif

                             
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-check-circle text-info"></i> الاشتراك السنوي</span>
                                                 <span class="badge {{auth('doctor')->user()->membership_status->badgeClass()}}">
                                                     {{ auth('doctor')->user()->membership_status->label() }}
                                                 </span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-calendar-check text-info"></i> تاريخ انتهاء العضوية</span>
                                                 <span>{{ auth('doctor')->user()->membership_expiration_date ? auth('doctor')->user()->membership_expiration_date->format('Y-m-d') : '-' }}</span>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>


 
         </div>
         </div>
        
    </div>
 </div>
 @elseif(auth('doctor')->user()->membership_status == \App\Enums\MembershipStatus::Pending)

 <div class="card">
    <div class="card-body">
        <div class="image d-flex justify-content-center">
            <img src="{{asset('/assets/images/pending.jpg')}}" width="400" alt="">
        </div>
        <h1 class="text-center">
            <strong>لم يتم تفعيل حسابك بعد</strong>
        </h1>

        <p class="text-center h3">نحن الان في صدد مراجعة معلوماتك  ٫ سنعلمك قريباََ بالتفاصيل عبر بريدك الالكتروني والنظام  </p>

        <div class="text-center">
            <a href="/logout" class="btn btn-primary text-light">تسجيل خروج</a>
        </div>
    </div>
 </div>

 @elseif( auth('doctor')->user()->membership_status == \App\Enums\MembershipStatus::InitApprove )
 <div class="card">
    <div class="card-body">
        <div class="image d-flex justify-content-center">
            <img src="{{asset('/assets/images/init-approve.jpg')}}" width="400" alt="">
        </div>
        <h1 class="text-center">
            <strong class="text-success">تم قبولك بشكل مبدئي</strong>
        </h1>

        <p class="text-center h3"> يجب عليك زيارة الفرع المسجل به في يوم {{ auth('doctor')->user()->visiting_date }} وذلك لإستكمال الإجراءات </p>

        <div class="text-center">
            <a href="/logout" class="btn btn-primary text-light">تسجيل خروج</a>
        </div>
    </div>
 </div>

 @elseif( auth('doctor')->user()->membership_status == \App\Enums\MembershipStatus::InActive )
 <div class="card">
    <div class="card-body">
        <div class="image d-flex justify-content-center">
            <img src="{{asset('/assets/images/inactive.jpg')}}" width="400" alt="">
        </div>
        <h1 class="text-center">
            <strong class="text-danger">عضويتك غير مفعله </strong>
        </h1>

        <p class="text-center h3"> يجب عليك زيارة الفرع الخاص بك لمزيد من التفاصيل </p>

        <div class="text-center">
            <a href="/logout" class="btn btn-primary text-light">تسجيل خروج</a>
        </div>
    </div>
 </div>

 @elseif( auth('doctor')->user()->membership_status == \App\Enums\MembershipStatus::Rejected )
 <div class="card">
    <div class="card-body">
        <div class="image d-flex justify-content-center">
            <img src="{{asset('/assets/images/rejection.jpg')}}" width="400" alt="">
        </div>
        <h1 class="text-center">
            <strong class="text-danger">تم الرفض</strong>
        </h1>

        <p class="text-center h3"> تم الرفض طلبك للانضمام لنقابة الاطباء وذلك لسبب : <strong class="text-danger">{{auth('doctor')->user()->notes}}</strong> يرجى مراجعة الفرع لتفاصيل اكثر </p>

        <div class="text-center">
            <a href="/logout" class="btn btn-primary text-light">تسجيل خروج</a>
        </div>
    </div>
 </div>
@endif



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
