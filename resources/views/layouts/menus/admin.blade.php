
{{-- TICKETS --}}


{{-- USERS / STAFF --}}
@if(auth()->user()->permissions()->where('name', 'manage-staff')->count())

<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#users" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-users"></i>
        <span data-key="t-layouts">الموظفين</span>
    </a>
    <div class="collapse menu-dropdown" id="users">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.users.create') }}" class="nav-link" data-key="t-horizontal">
                    إنشاء مستخدم جديد
                </a>
                <a href="{{ route(get_area_name().'.users.index') }}" class="nav-link" data-key="t-horizontal">
                    عرض جميع الموظفين
                </a>
            </li>
        </ul>
    </div>
</li>
@endif

{{-- PRICING SERVICES --}}


{{-- doctor mails --}}


@if(auth()->user()->permissions()->where('name', 'doctor-mail')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#doctor_mails" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-file"></i>
        <span data-key="t-layouts">طلبات اوراق الخارج</span>
    </a>
    <div class="collapse menu-dropdown" id="doctor_mails">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctor-mails.create') }}" class="nav-link" data-key="t-horizontal">
                    اضف طلب جديد 
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctor-mails.index') }}" class="nav-link" data-key="t-horizontal">
                    عرض جميع الطلبات
                </a>
            </li>
        </ul>
    </div>
</li>



<li class="nav-item">
    <a class="nav-link menu-link" data-bs-toggle="collapse" data-bs-target="#emails" role="button" aria-expanded="false" aria-controls="emails">
        <i class="fa fa-envelope"></i>
        <span data-key="t-layouts">البريد الإلكترونية</span>
    </a>
    <div class="collapse menu-dropdown" id="emails">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.emails.create') }}" class="nav-link" data-key="t-horizontal">
                    إضافة بريد جديد
                </a>
                <a href="{{ route(get_area_name().'.emails.index') }}" class="nav-link" data-key="t-horizontal">
                    عرض جميع البريد
                </a>
            </li>
        </ul>
    </div>
</li>
@endif



@if(auth()->user()->permissions()->where('name', 'services-pricing')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#pricing_licenses_all" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-money-bill"></i>
        <span data-key="t-layouts">تسعير الخدمات</span>
    </a>
    <div class="collapse menu-dropdown" id="pricing_licenses_all">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                {{-- <a href="{{ route(get_area_name().'.pricings.create') }}" class="nav-link" data-key="t-horizontal">
                    إضافة تسعير جديد
                </a> --}}
                <a href="{{ route(get_area_name().'.pricings.index') }}" class="nav-link" data-key="t-horizontal">
                    عرض جميع التسعيرات
                </a>
            </li>
        </ul>
    </div>
</li>
@endif






@if (auth()->user()->permissions()->where('name', 'doctor-visitors')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#visitor" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i>
        <span data-key="t-layouts">الاطباء الزوار</span>
    </a>
    <div class="collapse menu-dropdown" id="visitor">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor", 'membership_status' => 'pending']) }}" class="nav-link">طلبات الموقع </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor", 'membership_status' => 'init_approve']) }}" class="nav-link">   موافقة مبدئية  </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor", 'membership_status' => 'active']) }}" class="nav-link">الاطباء المفعليين</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor", 'membership_status' => 'inactive']) }}" class="nav-link">الاطباء الغير مفعليينم</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor", 'membership_status' => 'banned']) }}" class="nav-link">الاطباء الموقوفين</a>

            </li>

        </ul>
    </div>
</li>
@endif



@if (auth()->user()->permissions()->where('name', 'doctor-palestinian')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#palestinian" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i>
        <span data-key="t-layouts">الاطباء الفلسطينيين</span>
    </a>
    <div class="collapse menu-dropdown" id="palestinian">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian", 'membership_status' => 'pending']) }}" class="nav-link">طلبات الموقع </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian", 'membership_status' => 'init_approve']) }}" class="nav-link">   موافقة مبدئية للاطباء </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian", 'membership_status' => 'active']) }}" class="nav-link">الاطباء المفعليين</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian", 'membership_status' => 'inactive']) }}" class="nav-link">الاطباء المنتهية صلاحيتهم</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian", 'membership_status' => 'banned']) }}" class="nav-link">الاطباء الموقوفين</a>

            </li>

        </ul>
    </div>
</li>
@endif





@if (auth()->user()->permissions()->where('name', 'doctor-foreign')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#doctors" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i>
        <span data-key="t-layouts">الاطباء الاجانب</span>
    </a>
    <div class="collapse menu-dropdown" id="doctors">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign", 'membership_status' => 'pending']) }}" class="nav-link">طلبات الموقع </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign", 'membership_status' => 'init_approve']) }}" class="nav-link">  موافقة مبدئية للاطباء </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign", 'membership_status' => 'active']) }}" class="nav-link">الاطباء المفعليين</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign", 'membership_status' => 'inactive']) }}" class="nav-link">الاطباء المنتهية صلاحيتهم</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign", 'membership_status' => 'banned']) }}" class="nav-link">الاطباء الموقوفين</a>

            </li>

        </ul>
    </div>
</li>
@endif






{{-- MEDICAL FACILITIES --}}

@if(auth()->user()->permissions()->where('name', 'manage-medical-facilities')->count())

<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#medical-facility" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-hospital"></i>
        <span data-key="t-layouts">المنشآت طبية</span>
    </a>
    <div class="collapse menu-dropdown" id="medical-facility">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.medical-facilities.create') }}" class="nav-link" data-key="t-horizontal">إنشاء مرفق جديد</a>
                <a href="{{ route(get_area_name().'.medical-facilities.index') }}" class="nav-link" data-key="t-horizontal">عرض جميع المنشآت طبية</a>
            </li>
        </ul>
    </div>
</li>
@endif


{{-- MEDICAL LICENSES (تراخيص المنشآت الطبية) --}}


@if(auth()->user()->permissions()->where('name', 'manage-medical-licenses')->count())


@php
$medical_license_under_approve_branch = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->where('status', 'under_approve_branch')->count();


    $medical_license_under_approve_admin = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->where('status', 'under_approve_admin')->count();



    $medical_license_under_payment = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->where('status', 'under_payment')->count();


    $medical_license_revoked = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->where('status', 'revoked')->count();





    $medical_license_expired = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->where('status', 'expired')->count();



    $medical_license_active = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->where('status', 'active')->count();




@endphp
<li class="nav-item">
<a class="nav-link menu-link" data-bs-target="#licences_facilities" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
    <i class="fa-solid fa-house-medical"></i> <span data-key="t-layouts">     تراخيص المنشآت الطبية  </span>
</a>
<div class="collapse menu-dropdown" id="licences_facilities">
    <ul class="nav nav-sm flex-column">
        <li class="nav-item">
            <a href="{{route('admin.licences.create', ['type' => "facilities"])}}" class="nav-link" data-key="t-horizontal">    اضافه  اذن مزاولة      </a>
            {{-- <a href="{{route(get_area_name().'.licences.index', ['type' => "facilities", "status" => "under_approve_branch"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الفرع  <br> <span class="badge badge-dark bg-dark">{{$medical_license_under_approve_branch}}</span>    </a> --}}
            <a href="{{route(get_area_name().'.licences.index', ['type' => "facilities", "status" => "under_approve_admin"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الادارة     <br> <span class="badge badge-dark bg-dark">{{$medical_license_under_approve_admin}}</span>     </a>
            <a href="{{route(get_area_name().'.licences.index', ['type' => "facilities", "status" => "under_payment"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد المراجعة المالية    <br> <span class="badge badge-dark bg-dark">{{$medical_license_under_payment}}</span>  </a>
            <a href="{{route(get_area_name().'.licences.index', ['type' => "facilities", "status" => "revoked"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المتوقفه     <br> <span class="badge badge-dark bg-dark">{{$medical_license_revoked}}</span> </a>
            <a href="{{route(get_area_name().'.licences.index', ['type' => "facilities", "status" => "expired"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المنتهي صلاحيتها     <br> <span class="badge badge-dark bg-dark">{{$medical_license_expired}}</span> </a>
            <a href="{{route(get_area_name().'.licences.index', ['type' => "facilities", "status" => "active"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - السارية      <br> <span class="badge badge-dark bg-dark">{{$medical_license_active}}</span> </a>
            <a href="{{route(get_area_name().'.licences.index', ['type' => "facilities"])}}" class="nav-link" data-key="t-horizontal">  عرض جميع  اذونات المزاولة      </a>
        </li>
    </ul>
</div>
</li>


@endif

{{-- BRANCHES (managing branches & reports) --}}
@if(auth()->user()->permissions()->where('name','manage-branches')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#branches" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-building"></i>
        <span data-key="t-layouts">الفروع</span>
    </a>
    <div class="collapse menu-dropdown" id="branches">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.branches.create') }}" class="nav-link" data-key="t-horizontal">
                    اضافه فرع جديد
                </a>
                <a href="{{ route(get_area_name().'.branches.index') }}" class="nav-link" data-key="t-horizontal">
                    عرض جميع الفروع
                </a>
            </li>
        </ul>
    </div>
</li>
@endif

{{-- REPORTS (branch-reports or manage-branches-reports, depends on your choice) --}}
@if(auth()->user()->permissions()->where('name','manage-branches-reports')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="{{ route(get_area_name().'.reports.index') }}">
        <i class="fa fa-file"></i>
        <span>التقارير</span>
    </a>
</li>
@endif

{{-- SECURITY LOGS (could be 'manage-staff' or 'manage-all-tickets' or some other permission) --}}
@if(auth()->user()->permissions()->where('name','logs')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="{{ route(get_area_name().'.logs') }}">
        <i class="fa fa-font-awesome"></i>
        <span>السجلات الأمنية</span>
    </a>
</li>
@endif

{{-- BLACKLIST --}}
{{-- @if(auth()->user()->permissions()->where('name','manage-blacklist')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#blacklists" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="blacklists">
        <i class="fa fa-ban"></i>
        <span data-key="t-blacklists">البلاك ليست</span>
    </a>
    <div class="collapse menu-dropdown" id="blacklists">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.blacklists.create') }}" class="nav-link" data-key="t-create-blacklist">إنشاء شخص جديد</a>
                <a href="{{ route(get_area_name().'.blacklists.index') }}" class="nav-link" data-key="t-view-blacklists">عرض البلاك ليست</a>
            </li>
        </ul>
    </div>
</li>
@endif --}}

{{-- REGISTRATION SETTINGS --}}
@if(auth()->user()->permissions()->where('name','registration-settings')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#settings" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-cog"></i>
        <span data-key="t-layouts">اعدادات التسجيل</span>
    </a>
    <div class="collapse menu-dropdown" id="settings">
        <ul class="nav nav-sm flex-column">


        


            <li class="nav-item">
                <a class="nav-link menu-link" href="#institutions" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                    <i class="fas fa-building"></i><span data-key="t-layouts"> جهات العمل </span>
                </a>
                <div class="collapse menu-dropdown" id="institutions">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route(get_area_name().'.institutions.create') }}" class="nav-link" data-key="t-horizontal">
                                 إضافة جهة جديدة
                            </a>
                            <a href="{{ route(get_area_name().'.institutions.index') }}" class="nav-link" data-key="t-horizontal">
                                 عرض جميع الجهات
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Countries --}}
            <li class="nav-item">
                <a class="nav-link menu-link" data-bs-target="#countries" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                    <i class="fa fa-globe"></i>
                    <span data-key="t-layouts">الجنسيات</span>
                </a>
                <div class="collapse menu-dropdown" id="countries">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route(get_area_name().'.countries.create') }}" class="nav-link" data-key="t-horizontal">
                                إضافة دولة جديدة
                            </a>
                            <a href="{{ route(get_area_name().'.countries.index') }}" class="nav-link" data-key="t-horizontal">
                                عرض جميع الدول
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Specialties --}}
            <li class="nav-item">
                <a class="nav-link menu-link" data-bs-target="#specialties" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                    <i class="fa fa-stethoscope"></i>
                    <span data-key="t-layouts">التخصصات الطبية</span>
                </a>
                <div class="collapse menu-dropdown" id="specialties">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route(get_area_name().'.specialties.create') }}" class="nav-link" data-key="t-horizontal">
                                إنشاء تخصص جديد
                            </a>
                            <a href="{{ route(get_area_name().'.specialties.index') }}" class="nav-link" data-key="t-horizontal">
                                عرض التخصصات
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- File Types --}}
            {{-- <li class="nav-item">
                <a class="nav-link menu-link" data-bs-target="#fileTypes" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                    <i class="fa fa-file"></i>
                    <span data-key="t-layouts">المستندات المطلوبة</span>
                </a>
                <div class="collapse menu-dropdown" id="fileTypes">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route(get_area_name().'.file-types.create') }}" class="nav-link" data-key="t-horizontal">
                                إنشاء مستند جديد
                            </a>
                            <a href="{{ route(get_area_name().'.file-types.index') }}" class="nav-link" data-key="t-horizontal">
                                عرض المستندات
                            </a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            {{-- Medical Facility Types --}}
            {{-- <li class="nav-item">
                <a class="nav-link menu-link" data-bs-target="#medical-facility-types" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                    <i class="fa fa-hospital"></i>
                    <span data-key="t-layouts">أنواع المنشآت طبية</span>
                </a>
                <div class="collapse menu-dropdown" id="medical-facility-types">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route(get_area_name().'.medical-facility-types.create') }}" class="nav-link" data-key="t-horizontal">
                                إنشاء نوع جديد
                            </a>
                            <a href="{{ route(get_area_name().'.medical-facility-types.index') }}" class="nav-link" data-key="t-horizontal">
                                عرض جميع أنواع المنشآت طبية
                            </a>
                        </li>
                    </ul>
                </div>
            </li> --}}
        </ul>
    </div>
</li>
@endif

<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="{{ route(get_area_name().'.profile.change-password') }}">
        <i class="ri-lock-password-line"></i> <span>تغيير كلمة المرور</span>
    </a>
</li>




