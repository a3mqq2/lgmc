


@can('medical_facilties')
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

        </ul>
    </div>
</li>



@endcan


@can('doctors')
    
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



@can('branch_accounting')

<li class="nav-item">
    <a class="nav-link menu-link" href="{{route('user.vaults.index')}}">
        <i class="fa fa-vault"></i> <span> الخزائن المالية </span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link menu-link" href="#transactions" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-money-bills"></i><span data-key="t-layouts">   الحركات المالية    </span>
    </a>
    <div class="collapse menu-dropdown" id="transactions">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{route('user.transactions.create')}}" class="nav-link" data-key="t-horizontal">    اضافه حركة مالية جديدة     </a>
                <a href="{{route('user.transactions.index')}}" class="nav-link" data-key="t-horizontal">  عرض جميع الحركات المالية      </a>
            </li>
        </ul>
    </div>
</li>

@endcan


@can('doctor_licences')
@php
    $doctor_license_under_approve_branch = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_approve_branch')->count();


        $doctor_license_under_approve_admin = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_approve_admin')->count();



        $doctor_license_under_payment = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_payment')->count();


        $doctor_license_revoked = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'revoked')->count();





        $doctor_license_expired = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'expired')->count();



        $doctor_license_active = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'active')->count();




@endphp
<li class="nav-item">
    <a class="nav-link menu-link" href="#licences" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-id-card"></i><span data-key="t-layouts">    أذونات المزاولة - الاطباء   </span>
    </a>
    <div class="collapse menu-dropdown" id="licences">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{route('user.licences.create', ['type' => "doctors"])}}" class="nav-link" data-key="t-horizontal">    اضافه  اذن مزاولة      </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "status" => "under_approve_branch"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الفرع  <br> <span class="badge badge-dark bg-dark">{{$doctor_license_under_approve_branch}}</span>    </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "status" => "under_approve_admin"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الادارة     <br> <span class="badge badge-dark bg-dark">{{$doctor_license_under_approve_admin}}</span>     </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "status" => "under_payment"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد المراجعة المالية    <br> <span class="badge badge-dark bg-dark">{{$doctor_license_under_payment}}</span>  </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "status" => "revoked"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المتوقفه     <br> <span class="badge badge-dark bg-dark">{{$doctor_license_revoked}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "status" => "expired"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المنتهي صلاحيتها     <br> <span class="badge badge-dark bg-dark">{{$doctor_license_expired}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "status" => "active"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - السارية      <br> <span class="badge badge-dark bg-dark">{{$doctor_license_active}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors"])}}" class="nav-link" data-key="t-horizontal">  عرض جميع  اذونات المزاولة      </a>
            </li>
        </ul>
    </div>
</li>

@endcan


@can('medical_faciltiesـlicences')

@php
    $medical_license_under_approve_branch = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_approve_branch')->count();


        $medical_license_under_approve_admin = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_approve_admin')->count();



        $medical_license_under_payment = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_payment')->count();


        $medical_license_revoked = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'revoked')->count();





        $medical_license_expired = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'expired')->count();



        $medical_license_active = App\Models\Licence::whereHasMorph('licensable', App\Models\MedicalFacility::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'active')->count();




@endphp
<li class="nav-item">
    <a class="nav-link menu-link" href="#licences_facilities" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa-solid fa-house-medical"></i> <span data-key="t-layouts">     تراخيص المنشآت الطبية  </span>
    </a>
    <div class="collapse menu-dropdown" id="licences_facilities">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{route('user.licences.create', ['type' => "facilities"])}}" class="nav-link" data-key="t-horizontal">    اضافه  اذن مزاولة      </a>
                <a href="{{route('user.licences.index', ['type' => "facilities", "status" => "under_approve_branch"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الفرع  <br> <span class="badge badge-dark bg-dark">{{$medical_license_under_approve_branch}}</span>    </a>
                <a href="{{route('user.licences.index', ['type' => "facilities", "status" => "under_approve_admin"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الادارة     <br> <span class="badge badge-dark bg-dark">{{$medical_license_under_approve_admin}}</span>     </a>
                <a href="{{route('user.licences.index', ['type' => "facilities", "status" => "under_payment"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد المراجعة المالية    <br> <span class="badge badge-dark bg-dark">{{$medical_license_under_payment}}</span>  </a>
                <a href="{{route('user.licences.index', ['type' => "facilities", "status" => "revoked"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المتوقفه     <br> <span class="badge badge-dark bg-dark">{{$medical_license_revoked}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "facilities", "status" => "expired"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المنتهي صلاحيتها     <br> <span class="badge badge-dark bg-dark">{{$medical_license_expired}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "facilities", "status" => "active"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - السارية      <br> <span class="badge badge-dark bg-dark">{{$medical_license_active}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "facilities"])}}" class="nav-link" data-key="t-horizontal">  عرض جميع  اذونات المزاولة      </a>
            </li>
        </ul>
    </div>
</li>
@endcan



@can('branch_reports')
<li class="nav-item">
    <a class="nav-link menu-link" href="{{route('user.reports.index')}}">
        <i class="fa fa-file"></i> <span> التقارير  </span>
    </a>
</li>
@endcan