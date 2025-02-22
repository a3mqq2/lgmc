



@if (auth()->user()->permissions->where('name','total_invoices')->count() && auth()->user()->branch_id)
<li class="nav-item">
    <a class="nav-link menu-link" href="#doctors" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i><span data-key="t-layouts">    الفواتير الكلية   </span>
    </a>
    <div class="collapse menu-dropdown" id="doctors">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.index') }}" class="nav-link" data-key="t-horizontal">    عرض جميع الآطباء   </a>
                <a href="{{ route(get_area_name().'.total_invoices.index') }}" class="nav-link" data-key="t-horizontal">    عرض جميع الفواتير   </a>
            </li>

        </ul>
    </div>
</li>
@endif


{{-- <li class="nav-item">
    <a class="nav-link menu-link" href="#tickets" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-ticket"></i><span data-key="t-layouts">     التذاكر    </span>
    </a>
    <div class="collapse menu-dropdown" id="tickets">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.tickets.create') }}" class="nav-link" data-key="t-horizontal">  إضافة تذكرة جديدة    </a>
                <a href="{{ route(get_area_name().'.tickets.index', ['my' => 1]) }}" class="nav-link" data-key="t-horizontal">  التذاكر المحالة لي      </a>
                @if (auth()->user()->permissions->where('name','manage-all-tickets')->count())
                <a href="{{ route(get_area_name().'.tickets.index') }}" class="nav-link" data-key="t-horizontal">  عرض جميع التذاكر    </a>
                    
                @endif
            </li>
        </ul>
    </div>
</li> --}}




<li class="nav-item">
    <a class="nav-link menu-link" href="#invoices" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-file-invoice"></i><span data-key="t-layouts">    الفواتير   </span>
    </a>
    <div class="collapse menu-dropdown" id="invoices">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.invoices.index', ['invoiceable' => 'Doctor'] ) }}" class="nav-link" data-key="t-horizontal">    فواتير الآطباء  </a>
                <a href="{{ route(get_area_name().'.invoices.index', ['invoiceable' => 'MedicalFaclitiy']) }}" class="nav-link" data-key="t-horizontal">    فواتير المنشآت الطبية  </a>
            </li>

        </ul>
    </div>
</li>




<li class="nav-item">
    <a class="nav-link menu-link" href="#transfers" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-exchange-alt"></i><span data-key="t-layouts">    تحويلات الخزائن   </span>
    </a>
    <div class="collapse menu-dropdown" id="transfers">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.vault-transfers.create') }}" class="nav-link" data-key="t-horizontal">   اضافة تحويل جديد </a>
                <a href="{{ route(get_area_name().'.vault-transfers.index') }}" class="nav-link" data-key="t-horizontal">    عرض جميع التحويلات </a>
            </li>

        </ul>
    </div>
</li>



@if (auth()->user()->permissions->where('name','financial-administration')->count() && !auth()->user()->branch_id)
    
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
@endif




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

<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route(get_area_name().'.profile.change-password') }}">
        <i class="ri-lock-password-line"></i> <span>تغيير كلمة المرور</span>
    </a>
</li>
