




<li class="nav-item">
    <a class="nav-link menu-link" href="#tickets" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-ticket"></i><span data-key="t-layouts">     التذاكر    </span>
    </a>
    <div class="collapse menu-dropdown" id="tickets">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.tickets.create') }}" class="nav-link" data-key="t-horizontal">  إضافة تذكرة جديدة    </a>
                <a href="{{ route(get_area_name().'.tickets.index', ['my' => 1]) }}" class="nav-link" data-key="t-horizontal">  التذاكر المحالة لي      </a>
                @if(auth()->user()->permissions->where('name','manage-branch-tickets')->count())
                <a href="{{ route(get_area_name().'.tickets.index') }}" class="nav-link" data-key="t-horizontal">  عرض جميع التذاكر    </a>
                @endif
            </li>
        </ul>
    </div>
</li>


@if(auth()->user()->permissions->where('name', 'doctor-requests')->count())

<li class="nav-item">
    <a class="nav-link menu-link" href="#doctor-requests" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-clipboard-list"></i><span data-key="t-layouts">    طلبات الأطباء   </span>
    </a>
    <div class="collapse menu-dropdown" id="doctor-requests">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctor-requests.index', ['doctor_type' => "libyan"]) }}" class="nav-link" data-key="t-horizontal"> طلبات الاطباء الليبيين  </a>
                <a href="{{ route(get_area_name().'.doctor-requests.index', ['doctor_type' => "palestinian"]) }}" class="nav-link" data-key="t-horizontal"> طلبات الاطباء الفلسطينيين  </a>
                <a href="{{ route(get_area_name().'.doctor-requests.index', ['doctor_type' => "foreign"]) }}" class="nav-link" data-key="t-horizontal"> طلبات الاطباء الاجانب  </a>
                <a href="{{ route(get_area_name().'.doctor-requests.index', ['doctor_type' => "visitor"]) }}" class="nav-link" data-key="t-horizontal"> طلبات الاطباء الزوار  </a>
            </li>
        </ul>
    </div>
</li>

@endif
@if(auth()->user()->permissions->where('name','manage-medical-facilities')->count())
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
@endif


@if(auth()->user()->permissions->where('name','manage-doctors')->count())
    
<li class="nav-item">
    <a class="nav-link menu-link" href="#doctors" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i><span data-key="t-layouts">    الأطباء   </span>
    </a>
    <div class="collapse menu-dropdown" id="doctors">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "libyan"]) }}" class="nav-link" data-key="t-horizontal">    الاطباء الليبيين  </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign"]) }}" class="nav-link" data-key="t-horizontal">    الاطباء الآجانب  </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor"]) }}" class="nav-link" data-key="t-horizontal">    الاطباء الزوار  </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian"]) }}" class="nav-link" data-key="t-horizontal">    الاطباء الفلسطينيين  </a>
                <a href="{{ route(get_area_name().'.doctors.index') }}" class="nav-link" data-key="t-horizontal">    عرض جميع الآطباء   </a>
            </li>

        </ul>
    </div>
</li>

@endif


{{-- 
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

@endcan --}}


@if(auth()->user()->permissions->where('name','doctor-practice-permits')->count())

@php
    $libyan_doctor_license_under_approve_branch = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_approve_branch')->where('doctor_type', 'libyan')->count();


        $libyan_doctor_license_under_approve_admin = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_approve_admin')->where('doctor_type', 'libyan')->count();



        $libyan_doctor_license_under_payment = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_payment')->where('doctor_type', 'libyan')->count();


        $libyan_doctor_license_revoked = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'revoked')->where('doctor_type', 'libyan')->count();





        $libyan_doctor_license_expired = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'expired')->where('doctor_type', 'libyan')->count();



        $libyan_doctor_license_active = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'active')->where('doctor_type', 'libyan')->count();




@endphp

<li class="nav-item">
    <a class="nav-link menu-link" href="#libyan" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-id-card"></i><span data-key="t-layouts">    أذونات المزاولة - لليبيين   </span>
    </a>
    <div class="collapse menu-dropdown" id="libyan">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{route('user.licences.create', ['type' => "doctors", "doctor_type" => "libyan"])}}" class="nav-link" data-key="t-horizontal">    اضافه  اذن مزاولة      </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "libyan" , "status" => "under_approve_branch"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الفرع  <br> <span class="badge badge-dark bg-dark">{{$libyan_doctor_license_under_approve_branch}}</span>    </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "libyan" , "status" => "under_approve_admin"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الادارة     <br> <span class="badge badge-dark bg-dark">{{$libyan_doctor_license_under_approve_admin}}</span>     </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "libyan" , "status" => "under_payment"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد المراجعة المالية    <br> <span class="badge badge-dark bg-dark">{{$libyan_doctor_license_under_payment}}</span>  </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "libyan" , "status" => "revoked"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المتوقفه     <br> <span class="badge badge-dark bg-dark">{{$libyan_doctor_license_revoked}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "libyan" , "status" => "expired"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المنتهي صلاحيتها     <br> <span class="badge badge-dark bg-dark">{{$libyan_doctor_license_expired}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "libyan" , "status" => "active"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - السارية      <br> <span class="badge badge-dark bg-dark">{{$libyan_doctor_license_active}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "libyan" ])}}" class="nav-link" data-key="t-horizontal">  عرض جميع  اذونات المزاولة      </a>
            </li>
        </ul>
    </div>
</li>


@php
    $palestinian_doctor_license_under_approve_branch = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_approve_branch')->where('doctor_type', 'palestinian')->count();


        $palestinian_doctor_license_under_approve_admin = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_approve_admin')->where('doctor_type', 'palestinian')->count();



        $palestinian_doctor_license_under_payment = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_payment')->where('doctor_type', 'palestinian')->count();


        $palestinian_doctor_license_revoked = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'revoked')->where('doctor_type', 'palestinian')->count();


        $palestinian_doctor_license_expired = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'expired')->where('doctor_type', 'palestinian')->count();



        $palestinian_doctor_license_active = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'active')->where('doctor_type', 'palestinian')->count();




@endphp


<li class="nav-item">
    <a class="nav-link menu-link" href="#palestinian" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-id-card"></i><span data-key="t-layouts">    أذونات المزاولة - فلسطينيين   </span>
    </a>
    <div class="collapse menu-dropdown" id="palestinian">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{route('user.licences.create', ['type' => "doctors", "doctor_type" => "palestinian"])}}" class="nav-link" data-key="t-horizontal">    اضافه  اذن مزاولة      </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "palestinian" , "status" => "under_approve_branch"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الفرع  <br> <span class="badge badge-dark bg-dark">{{$palestinian_doctor_license_under_approve_branch}}</span>    </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "palestinian" , "status" => "under_approve_admin"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الادارة     <br> <span class="badge badge-dark bg-dark">{{$palestinian_doctor_license_under_approve_admin}}</span>     </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "palestinian" , "status" => "under_payment"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد المراجعة المالية    <br> <span class="badge badge-dark bg-dark">{{$palestinian_doctor_license_under_payment}}</span>  </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "palestinian" , "status" => "revoked"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المتوقفه     <br> <span class="badge badge-dark bg-dark">{{$palestinian_doctor_license_revoked}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "palestinian" , "status" => "expired"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المنتهي صلاحيتها     <br> <span class="badge badge-dark bg-dark">{{$palestinian_doctor_license_expired}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "palestinian" , "status" => "active"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - السارية      <br> <span class="badge badge-dark bg-dark">{{$palestinian_doctor_license_active}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "palestinian"])}}" class="nav-link" data-key="t-horizontal">  عرض جميع  اذونات المزاولة      </a>
            </li>
        </ul>
    </div>
</li>



@php
    $visitor_doctor_license_under_approve_branch = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_approve_branch')->where('doctor_type', 'visitor')->count();


        $visitor_doctor_license_under_approve_admin = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_approve_admin')->where('doctor_type', 'visitor')->count();



        $visitor_doctor_license_under_payment = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_payment')->where('doctor_type', 'visitor')->count();


        $visitor_doctor_license_revoked = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'revoked')->where('doctor_type', 'visitor')->count();


        $visitor_doctor_license_expired = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'expired')->where('doctor_type', 'visitor')->count();



        $visitor_doctor_license_active = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'active')->where('doctor_type', 'visitor')->count();


@endphp


<li class="nav-item">
    <a class="nav-link menu-link" href="#visitors" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-id-card"></i><span data-key="t-layouts">    أذونات المزاولة - زوار   </span>
    </a>
    <div class="collapse menu-dropdown" id="visitors">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{route('user.licences.create', ['type' => "doctors", "doctor_type" => "visitor"])}}" class="nav-link" data-key="t-horizontal">    اضافه  اذن مزاولة      </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "visitor" , "status" => "under_approve_branch"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الفرع  <br> <span class="badge badge-dark bg-dark">{{$visitor_doctor_license_under_approve_branch}}</span>    </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "visitor" , "status" => "under_approve_admin"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الادارة     <br> <span class="badge badge-dark bg-dark">{{$visitor_doctor_license_under_approve_admin}}</span>     </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "visitor" , "status" => "under_payment"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد المراجعة المالية    <br> <span class="badge badge-dark bg-dark">{{$visitor_doctor_license_under_payment}}</span>  </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "visitor" , "status" => "revoked"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المتوقفه     <br> <span class="badge badge-dark bg-dark">{{$visitor_doctor_license_revoked}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "visitor" , "status" => "expired"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المنتهي صلاحيتها     <br> <span class="badge badge-dark bg-dark">{{$visitor_doctor_license_expired}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "visitor" , "status" => "active"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - السارية      <br> <span class="badge badge-dark bg-dark">{{$visitor_doctor_license_active}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "visitor" ])}}" class="nav-link" data-key="t-horizontal">  عرض جميع  اذونات المزاولة      </a>
            </li>
        </ul>
    </div>
</li>


@php
    $foreign_doctor_license_under_approve_branch = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_approve_branch')->where('doctor_type', 'foreign')->count();


        $foreign_doctor_license_under_approve_admin = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_approve_admin')->where('doctor_type', 'foreign')->count();



        $foreign_doctor_license_under_payment = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'under_payment')->where('doctor_type', 'foreign')->count();


        $foreign_doctor_license_revoked = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'revoked')->where('doctor_type', 'foreign')->count();


        $foreign_doctor_license_expired = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'expired')->where('doctor_type', 'foreign')->count();



        $foreign_doctor_license_active = App\Models\Licence::whereHasMorph('licensable', App\Models\Doctor::class)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->where('status', 'active')->where('doctor_type', 'foreign')->count();


@endphp



<li class="nav-item">
    <a class="nav-link menu-link" href="#foreign" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-id-card"></i><span data-key="t-layouts">    أذونات المزاولة - اجانب   </span>
    </a>
    <div class="collapse menu-dropdown" id="foreign">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{route('user.licences.create', ['type' => "doctors", "doctor_type" => "foreign"])}}" class="nav-link" data-key="t-horizontal">    اضافه  اذن مزاولة      </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "foreign" , "status" => "under_approve_branch"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الفرع  <br> <span class="badge badge-dark bg-dark">{{$foreign_doctor_license_under_approve_branch}}</span>    </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "foreign" , "status" => "under_approve_admin"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الادارة     <br> <span class="badge badge-dark bg-dark">{{$foreign_doctor_license_under_approve_admin}}</span>     </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "foreign" , "status" => "under_payment"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد المراجعة المالية    <br> <span class="badge badge-dark bg-dark">{{$foreign_doctor_license_under_payment}}</span>  </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "foreign" , "status" => "revoked"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المتوقفه     <br> <span class="badge badge-dark bg-dark">{{$foreign_doctor_license_revoked}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "foreign" , "status" => "expired"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - المنتهي صلاحيتها     <br> <span class="badge badge-dark bg-dark">{{$foreign_doctor_license_expired}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "foreign" , "status" => "active"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - السارية      <br> <span class="badge badge-dark bg-dark">{{$foreign_doctor_license_active}}</span> </a>
                <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "foreign" ])}}" class="nav-link" data-key="t-horizontal">  عرض جميع  اذونات المزاولة      </a>
            </li>
        </ul>
    </div>
</li>







@endif


@if(auth()->user()->permissions->where('name', 'manage-medical-licenses')->count())


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
@endif



@if(auth()->user()->permissions->where('name', 'branch-reports')->count())
<li class="nav-item">
    <a class="nav-link menu-link" href="{{route('user.reports.index')}}">
        <i class="fa fa-file"></i> <span> التقارير  </span>
    </a>
</li>
@endif

<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route(get_area_name().'.profile.change-password') }}">
        <i class="ri-lock-password-line"></i> <span>تغيير كلمة المرور</span>
    </a>
</li>
