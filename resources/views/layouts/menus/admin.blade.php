
@can('users')
<li class="nav-item">
    <a class="nav-link menu-link" href="#users" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-users"></i><span data-key="t-layouts">   الموظفين  </span>
    </a>
    <div class="collapse menu-dropdown" id="users">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.users.create') }}" class="nav-link" data-key="t-horizontal">  إنشاء مستخدم جديد  </a>
                <a href="{{ route(get_area_name().'.users.index') }}" class="nav-link" data-key="t-horizontal">  عرض جميع الموظفين  </a>
            </li>
        </ul>
    </div>
</li>
@endcan


@can('doctors_admin')
    
<li class="nav-item">
    <a class="nav-link menu-link" href="#doctors" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i><span data-key="t-layouts">    الأطباء   </span>
    </a>
    <div class="collapse menu-dropdown" id="doctors">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.create') }}" class="nav-link" data-key="t-horizontal">   اضافة طبيب جديد   </a>
                <a href="{{ route(get_area_name().'.doctors.index') }}" class="nav-link" data-key="t-horizontal">  عرض جميع   الاطباء  </a>
            </li>

        </ul>
    </div>
</li>

@endcan

@can('medical_facilities_admin')

<li class="nav-item">
    <a class="nav-link menu-link" href="#medical-facility" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-hospital"></i><span data-key="t-layouts">    المرافق الطبية  </span>
    </a>
    <div class="collapse menu-dropdown" id="medical-facility">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.medical-facilities.create') }}" class="nav-link" data-key="t-horizontal">  إنشاء مرفق جديد  </a>
                <a href="{{ route(get_area_name().'.medical-facilities.index') }}" class="nav-link" data-key="t-horizontal">  عرض جميع  المرافق الطبية  </a>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-link" href="#medical-facility-types" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                    <i class="fa fa-hospital"></i><span data-key="t-layouts">   أنواع المرافق الطبية  </span>
                </a>
                <div class="collapse menu-dropdown" id="medical-facility-types">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route(get_area_name().'.medical-facility-types.create') }}" class="nav-link" data-key="t-horizontal">  إنشاء نوع جديد  </a>
                            <a href="{{ route(get_area_name().'.medical-facility-types.index') }}" class="nav-link" data-key="t-horizontal">  عرض جميع أنواع المرافق الطبية  </a>
                        </li>
                    </ul>
                </div>
            </li>


        </ul>
    </div>
</li>


@endcan




@can('settings')


<li class="nav-item">
    <a class="nav-link menu-link" href="#settings" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-cog"></i><span data-key="t-layouts">   اعدادات التسجيل   </span>
    </a>
    <div class="collapse menu-dropdown" id="settings">
        <ul class="nav nav-sm flex-column">
            

            <li class="nav-item">
                <a class="nav-link menu-link" href="#capacities" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                    <i class="fa fa-briefcase"></i><span data-key="t-layouts">    الصفات  </span>
                </a>
                <div class="collapse menu-dropdown" id="capacities">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route(get_area_name().'.capacities.create') }}" class="nav-link" data-key="t-horizontal">  إضافة صفة طبيب جديدة  </a>
                            <a href="{{ route(get_area_name().'.capacities.index') }}" class="nav-link" data-key="t-horizontal">  عرض جميع  صفات الاطباء  </a>
                        </li>
                    </ul>
                </div>
            </li>


                <li class="nav-item">
                    <a class="nav-link menu-link" href="#academic-degrees" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i class="fa fa-graduation-cap"></i><span data-key="t-layouts">   الدرجات العلمية  </span>
                    </a>
                    <div class="collapse menu-dropdown" id="academic-degrees">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route(get_area_name().'.academic-degrees.create') }}" class="nav-link" data-key="t-horizontal">  إضافة درجة علمية جديدة  </a>
                                <a href="{{ route(get_area_name().'.academic-degrees.index') }}" class="nav-link" data-key="t-horizontal">  عرض جميع الدرجات العلمية  </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#universities" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i class="fa fa-university"></i><span data-key="t-layouts">   الجامعات  </span>
                    </a>
                    <div class="collapse menu-dropdown" id="universities">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route(get_area_name().'.universities.create') }}" class="nav-link" data-key="t-horizontal">  إضافة جامعة جديدة  </a>
                                <a href="{{ route(get_area_name().'.universities.index') }}" class="nav-link" data-key="t-horizontal">  عرض جميع الجامعات  </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#countries" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i class="fa fa-globe"></i><span data-key="t-layouts">   الجنسيات  </span>
                    </a>
                    <div class="collapse menu-dropdown" id="countries">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route(get_area_name().'.countries.create') }}" class="nav-link" data-key="t-horizontal">  إضافة دولة جديدة  </a>
                                <a href="{{ route(get_area_name().'.countries.index') }}" class="nav-link" data-key="t-horizontal">  عرض جميع الدول      </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#specialties" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i class="fa fa-stethoscope"></i><span data-key="t-layouts">   التخصصات الطبية  </span>
                    </a>
                    <div class="collapse menu-dropdown" id="specialties">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route(get_area_name().'.specialties.create') }}" class="nav-link" data-key="t-horizontal">  إنشاء تخصص  جديد  </a>
                                <a href="{{ route(get_area_name().'.specialties.index') }}" class="nav-link" data-key="t-horizontal">  عرض التخصصات       </a>
                            </li>
                            <li class="nav-item">
                            </li>
                        </ul>
                    </div>
                </li>



                <li class="nav-item">
                    <a class="nav-link menu-link" href="#fileTypes" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                        <i class="fa fa-file"></i><span data-key="t-layouts">   المستندات المطلوبة   </span>
                    </a>
                    <div class="collapse menu-dropdown" id="fileTypes">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route(get_area_name().'.file-types.create') }}" class="nav-link" data-key="t-horizontal">  إنشاء مستند  جديد  </a>
                                <a href="{{ route(get_area_name().'.file-types.index') }}" class="nav-link" data-key="t-horizontal">  عرض المستندات       </a>
                            </li>
                            <li class="nav-item">
                            </li>
                        </ul>
                    </div>
                </li>
                
        </ul>
    </div>
</li>

@endcan

@can('admin_accounting')
<li class="nav-item">
    <a class="nav-link menu-link" href="#vaults" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-vault"></i><span data-key="t-layouts">   الخزائن المالية  </span>
    </a>
    <div class="collapse menu-dropdown" id="vaults">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{route(get_area_name().'.vaults.create')}}" class="nav-link" data-key="t-horizontal">    اضافه خزينة جديد     </a>
                <a href="{{route(get_area_name().'.vaults.index')}}" class="nav-link" data-key="t-horizontal">  عرض جميع الخزائن      </a>
            </li>
        </ul>
    </div>
</li>


<li class="nav-item">
    <a class="nav-link menu-link" href="#layer-group" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-layer-group"></i><span data-key="t-layouts">   تصنيفات الحركات المالية  </span>
    </a>
    <div class="collapse menu-dropdown" id="layer-group">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{route(get_area_name().'.transaction-types.create')}}" class="nav-link" data-key="t-horizontal">    اضافه تصنيف جديد     </a>
                <a href="{{route(get_area_name().'.transaction-types.index')}}" class="nav-link" data-key="t-horizontal">  عرض جميع التصنيفات      </a>
            </li>
        </ul>
    </div>
</li>



<li class="nav-item">
    <a class="nav-link menu-link" href="#transactions" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-money-bills"></i><span data-key="t-layouts">   الحركات المالية    </span>
    </a>
    <div class="collapse menu-dropdown" id="transactions">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{route(get_area_name().'.transactions.create')}}" class="nav-link" data-key="t-horizontal">    اضافه حركة مالية جديدة     </a>
                <a href="{{route(get_area_name().'.transactions.index')}}" class="nav-link" data-key="t-horizontal">  عرض جميع الحركات المالية      </a>
            </li>
        </ul>
    </div>
</li>
@endcan


@can('doctors_licences_admin')
    
@php
$doctor_license_under_approve_branch = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->where('status', 'under_approve_branch')->count();


    $doctor_license_under_approve_admin = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->where('status', 'under_approve_admin')->count();



    $doctor_license_under_payment = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->where('status', 'under_payment')->count();


    $doctor_license_revoked = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->where('status', 'revoked')->count();





    $doctor_license_expired = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->where('status', 'expired')->count();



    $doctor_license_active = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->where('status', 'active')->count();




@endphp


<li class="nav-item">
<a class="nav-link menu-link" href="#licences" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
    <i class="fa fa-id-card"></i><span data-key="t-layouts">    أذونات المزاولة - الاطباء   </span>
</a>
<div class="collapse menu-dropdown" id="licences">
    <ul class="nav nav-sm flex-column">
        <li class="nav-item">
            {{-- <a href="{{route(get_area_name().'.licences.index', ['type' => "doctors", "status" => "under_approve_branch"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الفرع  <br> <span class="badge badge-dark bg-dark">{{$doctor_license_under_approve_branch}}</span>    </a> --}}
            <a href="{{route(get_area_name().'.licences.index', ['type' => "doctors", "status" => "under_approve_admin"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الادارة     <br> <span class="badge badge-dark bg-dark">{{$doctor_license_under_approve_admin}}</span>     </a>
            <a href="{{route(get_area_name().'.licences.index', ['type' => "doctors", "status" => "under_payment"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد المراجعة المالية    <br> <span class="badge badge-dark bg-dark">{{$doctor_license_under_payment}}</span>  </a>
            <a href="{{route(get_area_name().'.licences.index', ['type' => "doctors", "status" => "revoked"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المتوقفه     <br> <span class="badge badge-dark bg-dark">{{$doctor_license_revoked}}</span> </a>
            <a href="{{route(get_area_name().'.licences.index', ['type' => "doctors", "status" => "expired"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المنتهي صلاحيتها     <br> <span class="badge badge-dark bg-dark">{{$doctor_license_expired}}</span> </a>
            <a href="{{route(get_area_name().'.licences.index', ['type' => "doctors", "status" => "active"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - السارية      <br> <span class="badge badge-dark bg-dark">{{$doctor_license_active}}</span> </a>
            <a href="{{route(get_area_name().'.licences.index', ['type' => "doctors"])}}" class="nav-link" data-key="t-horizontal">  عرض جميع  اذونات المزاولة      </a>
        </li>
    </ul>
</div>
</li>
@endcan


@can('medical_facility_admin')
@php
$medical_license_under_approve_branch = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->where('status', 'under_approve_branch')->count();


    $medical_license_under_approve_admin = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->where('status', 'under_approve_admin')->count();



    $medical_license_under_payment = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->where('status', 'under_payment')->count();


    $medical_license_revoked = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->where('status', 'revoked')->count();





    $medical_license_expired = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->where('status', 'expired')->count();



    $medical_license_active = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->where('status', 'active')->count();




@endphp
<li class="nav-item">
<a class="nav-link menu-link" href="#licences_facilities" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
    <i class="fa-solid fa-house-medical"></i> <span data-key="t-layouts">     تراخيص المنشآت الطبية  </span>
</a>
<div class="collapse menu-dropdown" id="licences_facilities">
    <ul class="nav nav-sm flex-column">
        <li class="nav-item">
            <a href="{{route('admin.licences.index', ['type' => "facilities", "status" => "under_approve_branch"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الفرع  <br> <span class="badge badge-dark bg-dark">{{$medical_license_under_approve_branch}}</span>    </a>
            <a href="{{route('admin.licences.index', ['type' => "facilities", "status" => "under_approve_admin"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الادارة     <br> <span class="badge badge-dark bg-dark">{{$medical_license_under_approve_admin}}</span>     </a>
            <a href="{{route('admin.licences.index', ['type' => "facilities", "status" => "under_payment"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد المراجعة المالية    <br> <span class="badge badge-dark bg-dark">{{$medical_license_under_payment}}</span>  </a>
            <a href="{{route('admin.licences.index', ['type' => "facilities", "status" => "revoked"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المتوقفه     <br> <span class="badge badge-dark bg-dark">{{$medical_license_revoked}}</span> </a>
            <a href="{{route('admin.licences.index', ['type' => "facilities", "status" => "expired"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المنتهي صلاحيتها     <br> <span class="badge badge-dark bg-dark">{{$medical_license_expired}}</span> </a>
            <a href="{{route('admin.licences.index', ['type' => "facilities", "status" => "active"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - السارية      <br> <span class="badge badge-dark bg-dark">{{$medical_license_active}}</span> </a>
            <a href="{{route('admin.licences.index', ['type' => "facilities"])}}" class="nav-link" data-key="t-horizontal">  عرض جميع  اذونات المزاولة      </a>
        </li>
    </ul>
</div>
</li>
@endcan


@can('branches')
<li class="nav-item">
    <a class="nav-link menu-link" href="#branches" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-building"></i><span data-key="t-layouts">   الفروع  </span>
    </a>
    <div class="collapse menu-dropdown" id="branches">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{route(get_area_name().'.branches.create')}}" class="nav-link" data-key="t-horizontal">    اضافه فرع جديد     </a>
                <a href="{{route(get_area_name().'.branches.index')}}" class="nav-link" data-key="t-horizontal">  عرض جميع الفروع      </a>
            </li>
        </ul>
    </div>
</li>
@endcan


@can('admin_reports')
<li class="nav-item">
    <a class="nav-link menu-link" href="{{route('admin.reports.index')}}">
        <i class="fa fa-file"></i> <span> التقارير  </span>
    </a>
</li>

@endcan


@can('logs')
<li class="nav-item">
    <a class="nav-link menu-link" href="{{route(get_area_name().'.logs')}}">
        <i class="fa fa-font-awesome"></i> <span>السجلات الامنية</span>
    </a>
</li>
@endcan