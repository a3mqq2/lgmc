


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










@if (auth()->user()->permissions()->where('name', 'doctor-libyan')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#doctors" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i>
        <span data-key="t-layouts">العضويات 
            @if (auth()->user()->branch_id != 1)
                - ليبيين
            @endif
        </span>
    </a>
    <div class="collapse menu-dropdown" id="doctors">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.create', ['type' => "libyan"]) }}" class="nav-link"> إضافة عضو جديد    </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "libyan", 'membership_status' => 'under_approve']) }}" class="nav-link">طلبات الموقع </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "libyan", 'membership_status' => 'active']) }}" class="nav-link">عضويات مفعله</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "libyan", 'membership_status' => 'expired']) }}" class="nav-link">عضويات  منتهيه صلاحيتهم</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "libyan", 'membership_status' => 'banned']) }}" class="nav-link">عضويات موقوفه</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "libyan", 'membership_status' => 'suspended']) }}" class="nav-link">عضويات معلقه</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "libyan"]) }}" class="nav-link"> جميع العضويات </a>
            </li>

        </ul>
    </div>
</li>
@endif





@if (auth()->user()->branch_id != 1)
@if (auth()->user()->permissions()->where('name', 'doctor-palestinian')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#palestinian" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i>
        <span data-key="t-layouts">العضويات الفلسطينيين</span>
    </a>
    <div class="collapse menu-dropdown" id="palestinian">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.create', ['type' => "palestinian"]) }}" class="nav-link"> إضافة عضو جديد    </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian", 'membership_status' => 'under_approve']) }}" class="nav-link">طلبات الموقع </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian", 'membership_status' => 'active']) }}" class="nav-link">عضويات مفعله</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian", 'membership_status' => 'expired']) }}" class="nav-link">عضويات  منتهيه صلاحيتهم</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian", 'membership_status' => 'banned']) }}" class="nav-link">عضويات موقوفه</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian", 'membership_status' => 'suspended']) }}" class="nav-link">عضويات معلقه</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "palestinian"]) }}" class="nav-link"> جميع العضويات </a>
            </li>
        </ul>
    </div>
</li>
@endif





@if (auth()->user()->permissions()->where('name', 'doctor-foreign')->count())
<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#foreign" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i>
        <span data-key="t-layouts">العضويات الأجانب</span>
    </a>
    <div class="collapse menu-dropdown" id="foreign">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.create', ['type' => "foreign"]) }}" class="nav-link"> إضافة عضو جديد    </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign", 'membership_status' => 'under_approve']) }}" class="nav-link">طلبات الموقع </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign", 'membership_status' => 'active']) }}" class="nav-link">عضويات مفعله</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign", 'membership_status' => 'expired']) }}" class="nav-link">عضويات  منتهيه صلاحيتهم</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign", 'membership_status' => 'banned']) }}" class="nav-link">عضويات موقوفه</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign", 'membership_status' => 'suspended']) }}" class="nav-link">عضويات معلقه</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "foreign"]) }}" class="nav-link"> جميع العضويات </a>
            </li>
        </ul>
    </div>
</li>
@endif


@endif



@if(auth()->user()->permissions()->where('name','manage-medical-facilities-branch')->count() && auth()->user()->branch_id != 1)

<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#medical-facility" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-hospital"></i>
        <span data-key="t-layouts">المنشآت طبية</span>
    </a>
    <div class="collapse menu-dropdown" id="medical-facility">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.medical-facilities.create') }}" class="nav-link" data-key="t-horizontal">إنشاء  منشأة طبية جديدة</a>
                <a href="{{ route(get_area_name().'.medical-facilities.index') }}" class="nav-link" data-key="t-horizontal">عرض جميع المنشآت طبية</a>
            </li>
        </ul>
    </div>
</li>
@endif





@if(auth()->user()->permissions()->where('name','doctor-visitor')->count())

<li class="nav-item">
    <a class="nav-link menu-link" data-bs-target="#visitors" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i>
        <span data-key="t-layouts">الاطباء الزوار</span>
    </a>
    <div class="collapse menu-dropdown" id="visitors">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor", 'membership_status' => 'under_approve']) }}" class="nav-link">طلبات الموقع </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor", 'membership_status' => 'active']) }}" class="nav-link">عضويات مفعله</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor", 'membership_status' => 'expired']) }}" class="nav-link">عضويات  منتهيه صلاحيتهم</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor", 'membership_status' => 'banned']) }}" class="nav-link">عضويات موقوفه</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor", 'membership_status' => 'suspended']) }}" class="nav-link">عضويات معلقه</a>
                <a href="{{ route(get_area_name().'.doctors.index', ['type' => "visitor"]) }}" class="nav-link"> جميع العضويات </a>
            </li>
        </ul>
    </div>
</li>
@endif






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

            <li class="nav-item">
                <a href="{{route('user.signatures.index')}}" class="nav-link menu-link" aria-controls="sidebarLayouts">
                    <i class="fa fa-signature"></i>
                    <span data-key="t-layouts"> التوقيعات </span>
                </a>
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



<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route(get_area_name().'.reports.index') }}">
        <i class="fa fa-file"></i> <span>  التقارير   </span>
    </a>
</li>




<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route(get_area_name().'.profile.change-password') }}">
        <i class="ri-lock-password-line"></i> <span>تغيير كلمة المرور</span>
    </a>
</li>
