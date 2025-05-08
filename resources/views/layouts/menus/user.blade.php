


{{-- 
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
 --}}




@if (auth()->user()->branch_id != 1)
@if(auth()->user()->permissions->where('name','manage-medical-facilities')->count())
<li class="nav-item">
    <a class="nav-link menu-link" href="#medical-facility" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-hospital"></i><span data-key="t-layouts">    المنشآت طبية  </span>
    </a>
    <div class="collapse menu-dropdown" id="medical-facility">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.medical-facilities.create') }}" class="nav-link" data-key="t-horizontal">  إنشاء مرفق جديد  </a>
                <a href="{{ route(get_area_name().'.medical-facilities.index') }}" class="nav-link" data-key="t-horizontal">  عرض جميع  المنشآت طبية  </a>
            </li>

        </ul>
    </div>
</li>
@endif
@endif






@if(auth()->user()->permissions->where('name','manage-doctors')->count())
    
<li class="nav-item">
    <a class="nav-link menu-link" href="#doctors" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i><span data-key="t-layouts">    الأطباء   </span>
    </a>
    <div class="collapse menu-dropdown" id="doctors">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.create', ['type' => "libyan"]) }}" class="nav-link" data-key="t-horizontal">    اضافة طبيب جديد   </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "libyan"]) }}" class="nav-link" data-key="t-horizontal">    الاطباء الليبيين  </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['regestration' => "1"]) }}" class="nav-link" data-key="t-horizontal">    طلبات العضوية عبر الموقع   </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['init_approve' => "1"]) }}" class="nav-link" data-key="t-horizontal">     اطباء تمت الموافقة عليهم بشكل مبدئي    </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['rejection' => "1"]) }}" class="nav-link" data-key="t-horizontal">    اطباء تم رفض عضوياتهم    </a>

                @if (auth()->user()->branch_id != 1)
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign"]) }}" class="nav-link" data-key="t-horizontal">    الاطباء الآجانب  </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor"]) }}" class="nav-link" data-key="t-horizontal">    الاطباء الزوار  </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian"]) }}" class="nav-link" data-key="t-horizontal">    الاطباء الفلسطينيين  </a>
                @endif

                <a href="{{ route(get_area_name().'.doctors.index', ['banned' => "1"]) }}" class="nav-link" data-key="t-horizontal">    الاطباء المحظورين  </a>
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
                {{-- <a href="{{route('user.licences.index', ['type' => "doctors", "doctor_type" => "libyan" , "status" => "under_approve_branch"])}}" class="nav-link" data-key="t-horizontal">    اذونات المزاولة - قيد موافقة الفرع  <br> <span class="badge badge-dark bg-dark">{{$libyan_doctor_license_under_approve_branch}}</span>    </a> --}}
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



@if (auth()->user()->branch_id != 1)

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
@endif



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


@if (auth()->user()->branch_id != 1)
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
@endif


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



@if (auth()->user()->branch_id != 1)

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


@if (auth()->user()->branch_id != 1)
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

@endif




@if(auth()->user()->permissions()->where('name','branch-manager')->count())
<li class="nav-item">
    <a class="nav-link menu-link" href="#settings" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-cog"></i>
        <span data-key="t-layouts"> الاضافات  </span>
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

            {{-- Doctor Ranks --}}
            {{-- <li class="nav-item">
                <a class="nav-link menu-link" href="#doctor_ranks" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                    <i class="fa fa-briefcase"></i>
                    <span data-key="t-layouts">الصفات</span>
                </a>
                <div class="collapse menu-dropdown" id="doctor_ranks">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route(get_area_name().'.doctor_ranks.create') }}" class="nav-link" data-key="t-horizontal">
                                إضافة صفة طبيب جديدة
                            </a>
                            <a href="{{ route(get_area_name().'.doctor_ranks.index') }}" class="nav-link" data-key="t-horizontal">
                                عرض جميع صفات الأطباء
                            </a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            {{-- Academic Degrees --}}
            {{-- <li class="nav-item">
                <a class="nav-link menu-link" href="#academic-degrees" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                    <i class="fa fa-graduation-cap"></i>
                    <span data-key="t-layouts">الدرجات العلمية</span>
                </a>
                <div class="collapse menu-dropdown" id="academic-degrees">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route(get_area_name().'.academic-degrees.create') }}" class="nav-link" data-key="t-horizontal">
                                إضافة درجة علمية جديدة
                            </a>
                            <a href="{{ route(get_area_name().'.academic-degrees.index') }}" class="nav-link" data-key="t-horizontal">
                                عرض جميع الدرجات العلمية
                            </a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            {{-- Universities --}}
            <li class="nav-item">
                <a class="nav-link menu-link" href="#universities" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                    <i class="fa fa-university"></i>
                    <span data-key="t-layouts">الجامعات</span>
                </a>
                <div class="collapse menu-dropdown" id="universities">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route(get_area_name().'.universities.create') }}" class="nav-link" data-key="t-horizontal">
                                إضافة جامعة جديدة
                            </a>
                            <a href="{{ route(get_area_name().'.universities.index') }}" class="nav-link" data-key="t-horizontal">
                                عرض جميع الجامعات
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Countries --}}
            {{-- <li class="nav-item">
                <a class="nav-link menu-link" href="#countries" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
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
            </li> --}}

            {{-- Specialties --}}
            <li class="nav-item">
                <a class="nav-link menu-link" href="#specialties" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
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
                <a class="nav-link menu-link" href="#fileTypes" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
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
                <a class="nav-link menu-link" href="#medical-facility-types" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
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






@if (auth()->user()->branch_id != 1)


<li class="nav-item">
    <a class="nav-link menu-link" href="#doctor_mails" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-file"></i>
        <span data-key="t-layouts">طلبات اوراق الخارج</span>
    </a>
    <div class="collapse menu-dropdown" id="doctor_mails">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctor-mails.index') }}" class="nav-link" data-key="t-horizontal">
                    عرض جميع الطلبات
                </a>
            </li>
        </ul>
    </div>
</li>


@endif



@if(auth()->user()->permissions->where('name', 'manage-doctor-transfers')->count())
<li class="nav-item">
    <a class="nav-link menu-link" href="#doctor-transfers" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-exchange-alt"></i><span data-key="t-layouts"> تحويلات الأطباء </span>
    </a>
    <div class="collapse menu-dropdown" id="doctor-transfers">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctor-transfers.create') }}" class="nav-link" data-key="t-horizontal"> إضافة طلب نقل جديد </a>
                <a href="{{ route(get_area_name().'.doctor-transfers.index', ['status' => 'pending']) }}" class="nav-link" data-key="t-horizontal"> التحويلات قيد الانتظار </a>
                <a href="{{ route(get_area_name().'.doctor-transfers.index', ['status' => 'approved']) }}" class="nav-link" data-key="t-horizontal"> التحويلات الموافق عليها </a>
                <a href="{{ route(get_area_name().'.doctor-transfers.index', ['status' => 'rejected']) }}" class="nav-link" data-key="t-horizontal"> التحويلات المرفوضة </a>
                <a href="{{ route(get_area_name().'.doctor-transfers.index') }}" class="nav-link" data-key="t-horizontal"> عرض جميع التحويلات </a>
            </li>
        </ul>
    </div>
</li>
@endif


@if (auth()->user()->branch_id != 1)
@if(auth()->user()->permissions->where('name', 'doctor-requests')->count())

<li class="nav-item">
    <a class="nav-link menu-link" href="#doctor-requests" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-clipboard-list"></i><span data-key="t-layouts">    طلبات الأطباء   </span>
    </a>
    <div class="collapse menu-dropdown" id="doctor-requests">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctor-requests.index', ['doctor_type' => "libyan"]) }}" class="nav-link" data-key="t-horizontal"> طلبات الاطباء الليبيين  </a>

                @if (auth()->user()->branch_id != 1)
                <a href="{{ route(get_area_name().'.doctor-requests.index', ['doctor_type' => "palestinian"]) }}" class="nav-link" data-key="t-horizontal"> طلبات الاطباء الفلسطينيين  </a>
                <a href="{{ route(get_area_name().'.doctor-requests.index', ['doctor_type' => "foreign"]) }}" class="nav-link" data-key="t-horizontal"> طلبات الاطباء الاجانب  </a>
                <a href="{{ route(get_area_name().'.doctor-requests.index', ['doctor_type' => "visitor"]) }}" class="nav-link" data-key="t-horizontal"> طلبات الاطباء الزوار  </a>
                @endif

            </li>
        </ul>
    </div>
</li>

@endif
@endif




<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route(get_area_name().'.profile.change-password') }}">
        <i class="ri-lock-password-line"></i> <span>تغيير كلمة المرور</span>
    </a>
</li>
