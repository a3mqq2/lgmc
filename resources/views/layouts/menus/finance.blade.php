<li class="nav-item">
    <a class="nav-link menu-link" href="#doctors" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i><span data-key="t-layouts">    العضويات 
            @if (auth()->user()->branch_id != 1)
                (ليبيين)
            @endif    </span>
    </a>
    <div class="collapse menu-dropdown" id="doctors">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.index', ['payment_status' => 'has_unpaid', 'type' => 'libyan']) }}" class="nav-link" data-key="t-horizontal">    عرض العضويات التي عليها استحقاق    </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['payment_status' => 'no_dues', 'type' => 'libyan']) }}" class="nav-link" data-key="t-horizontal">    عرض العضويات التي ليس لها استحقاق     </a>
            </li>
        </ul>
    </div>
</li>


@if (auth()->user()->branch_id != 1)
<li class="nav-item">
    <a class="nav-link menu-link" href="#foreign" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i><span data-key="t-layouts">    العضويات 
            @if (auth()->user()->branch_id != 1)
                (أجانب)
            @endif    </span>
    </a>
    <div class="collapse menu-dropdown" id="foreign">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.index', ['payment_status' => 'has_unpaid', 'type' => 'foreign']) }}" class="nav-link" data-key="t-horizontal">    عرض العضويات التي عليها استحقاق   </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['payment_status' => 'no_dues', 'type' => 'foreign']) }}" class="nav-link" data-key="t-horizontal">    عرض العضويات التي ليس لها استحقاق  </a>
            </li>
        </ul>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link menu-link" href="#palestinian" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-user-doctor"></i><span data-key="t-layouts">    العضويات 
            @if (auth()->user()->branch_id != 1)
                (فلسطينيين)
            @endif    </span>
    </a>
    <div class="collapse menu-dropdown" id="palestinian">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route(get_area_name().'.doctors.index', ['payment_status' => 'has_unpaid', 'type' => 'palestinian']) }}" class="nav-link" data-key="t-horizontal">    عرض العضويات التي عليها استحقاق   </a>
                <a href="{{ route(get_area_name().'.doctors.index', ['payment_status' => 'no_dues', 'type' => 'palestinian']) }}" class="nav-link" data-key="t-horizontal">    عرض العضويات التي ليس لها استحقاق  </a>
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
    <a class="nav-link menu-link" href="#transfers" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
        <i class="fa fa-exchange-alt"></i><span data-key="t-layouts">    تحويلات الحسابات   </span>
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

<li class="nav-item">
    <a href="{{route(get_area_name().'.vaults.index')}}" class="nav-link" data-key="t-horizontal"> <i class="fa fa-vault"></i>   قائمة الحسابات      </a>
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

<li class="nav-item">
    <a class="nav-link menu-link" href="#financial-categories" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="financial-categories">
        <i class="fa fa-tags"></i><span data-key="t-layouts">   التصنيفات المالية    </span>
    </a>
    <div class="collapse menu-dropdown" id="financial-categories">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{route('finance.financial-categories.create')}}" class="nav-link" data-key="t-horizontal">    إضافة تصنيف جديد     </a>
                <a href="{{route('finance.financial-categories.index')}}" class="nav-link" data-key="t-horizontal">  عرض جميع التصنيفات      </a>
                <a href="{{route('finance.financial-categories.index', ['type' => 'deposit'])}}" class="nav-link" data-key="t-horizontal">  تصنيفات الإيداع      </a>
                <a href="{{route('finance.financial-categories.index', ['type' => 'withdrawal'])}}" class="nav-link" data-key="t-horizontal">  تصنيفات السحب      </a>
            </li>
        </ul>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route(get_area_name().'.profile.change-password') }}">
        <i class="ri-lock-password-line"></i> <span>تغيير كلمة المرور</span>
    </a>
</li>
