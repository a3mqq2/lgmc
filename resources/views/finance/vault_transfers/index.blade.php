@extends('layouts.' . get_area_name())

@section('title', 'قائمة تحويلات الحساب')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="fa fa-exchange-alt"></i> تحويلات الحساب
                </h4>
                <a href="{{ route(get_area_name() . '.vault-transfers.create') }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> إضافة تحويل جديد
                </a>
            </div>

            <!-- Filters Section -->
            <div class="card-body border-bottom">
                <form method="GET" action="{{ route(get_area_name() . '.vault-transfers.index') }}" id="filtersForm">
                    <div class="row g-3">
                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <label for="status" class="form-label">الحالة</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>موافق عليه</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                            </select>
                        </div>

                        <!-- From Vault Filter -->
                       @if (!auth()->user()->branch_id)
                       <div class="col-md-2">
                        <label for="from_vault_id" class="form-label">من الحساب</label>
                        <select name="from_vault_id" id="from_vault_id" class="form-select">
                            <option value="">جميع الحسابات</option>
                            @foreach($fromVaults as $vault)
                                <option value="{{ $vault->id }}" {{ request('from_vault_id') == $vault->id ? 'selected' : '' }}>
                                    {{ $vault->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- To Vault Filter -->
                    <div class="col-md-2">
                        <label for="to_vault_id" class="form-label">إلى الحساب</label>
                        <select name="to_vault_id" id="to_vault_id" class="form-select">
                            <option value="">جميع الحسابات</option>
                            @foreach($toVaults as $vault)
                                <option value="{{ $vault->id }}" {{ request('to_vault_id') == $vault->id ? 'selected' : '' }}>
                                    {{ $vault->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                       @endif

                        <!-- User Filter -->
                        <div class="col-md-2">
                            <label for="user_id" class="form-label">المستخدم</label>
                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">جميع المستخدمين</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Branch Filter (if user has no branch_id) -->
                        @if(!auth()->user()->branch_id)
                        <div class="col-md-2">
                            <label for="branch_id" class="form-label">الفرع</label>
                            <select name="branch_id" id="branch_id" class="form-select">
                                <option value="">جميع الفروع</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Amount Range -->
                        <div class="col-md-2">
                            <label for="amount_from" class="form-label">المبلغ من</label>
                            <input type="number" name="amount_from" id="amount_from" class="form-control" 
                                   placeholder="0" step="0.01" value="{{ request('amount_from') }}">
                        </div>

                        <div class="col-md-2">
                            <label for="amount_to" class="form-label">المبلغ إلى</label>
                            <input type="number" name="amount_to" id="amount_to" class="form-control" 
                                   placeholder="0" step="0.01" value="{{ request('amount_to') }}">
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">من تاريخ</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" 
                                   value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-2">
                            <label for="date_to" class="form-label">إلى تاريخ</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" 
                                   value="{{ request('date_to') }}">
                        </div>

                        <!-- Search by Description -->
                        <div class="col-md-2">
                            <label for="search" class="form-label">البحث في الوصف</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="ابحث في الوصف..." value="{{ request('search') }}">
                        </div>

                        <!-- Per Page Selection -->
                        <div class="col-md-2">
                            <label for="per_page" class="form-label">عدد النتائج</label>
                            <select name="per_page" id="per_page" class="form-select" onchange="this.form.submit()">
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter"></i> تطبيق الفلاتر
                            </button>
                            <a href="{{ route(get_area_name() . '.vault-transfers.index') }}" class="btn btn-secondary">
                                <i class="fa fa-refresh"></i> إعادة تعيين
                            </a>
                            <button type="button" class="btn btn-info" id="toggleFilters">
                                <i class="fa fa-eye"></i> إخفاء/إظهار
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Export Button -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted">
                        عرض {{ $vaultTransfers->firstItem() ?? 0 }} إلى {{ $vaultTransfers->lastItem() ?? 0 }} 
                        من أصل {{ $vaultTransfers->total() }} نتيجة
                        @if(request()->hasAny(['status', 'from_vault_id', 'to_vault_id', 'user_id', 'branch_id', 'amount_from', 'amount_to', 'date_from', 'date_to', 'search']))
                            <span class="badge bg-info">مفلترة</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($totalAmount > 0)
                            <div class="text-muted">
                                إجمالي المبلغ: <strong>{{ number_format($totalAmount, 2) }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'from_vault', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none text-dark">
                                        من الحساب
                                        @if(request('sort') == 'from_vault')
                                            <i class="fa fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'to_vault', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none text-dark">
                                        إلى الحساب
                                        @if(request('sort') == 'to_vault')
                                            <i class="fa fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'amount', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none text-dark">
                                        القيمة
                                        @if(request('sort') == 'amount')
                                            <i class="fa fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>الوصف</th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'user', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none text-dark">
                                        المستخدم
                                        @if(request('sort') == 'user')
                                            <i class="fa fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>الفرع</th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none text-dark">
                                        الحالة
                                        @if(request('sort') == 'status')
                                            <i class="fa fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none text-dark">
                                        تاريخ الإنشاء
                                        @if(request('sort') == 'created_at')
                                            <i class="fa fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vaultTransfers as $transfer)
                                <tr>
                                    <td>{{ $loop->iteration + ($vaultTransfers->currentPage() - 1) * $vaultTransfers->perPage() }}</td>
                                    <td>{{ $transfer->fromVault->name ?? '-' }}</td>
                                    <td>{{ $transfer->toVault->name ?? '-' }}</td>
                                    <td>{{ number_format($transfer->amount, 2) }}</td>
                                    <td>{{ $transfer->description ?? 'لا يوجد' }}</td>
                                    <td>{{ $transfer->user->name ?? '-' }}</td>
                                    <td>{{ $transfer->branch?->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transfer->status === 'approved' ? 'success' : ($transfer->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ $transfer->status === 'approved' ? 'موافق عليه' : ($transfer->status === 'rejected' ? 'مرفوض' : 'قيد الانتظار') }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $transfer->created_at->format('Y-m-d H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($transfer->status === 'pending')
                                            @if ($transfer->status == "pending" && $transfer->fromVault->branch_id && ($transfer->fromVault->branch_id == auth()->user()->branch_id))
                                            <a href="{{ route(get_area_name() . '.vault-transfers.edit', $transfer) }}" class="btn btn-warning btn-sm">
                                             <i class="fa fa-edit"></i>
                                         </a>
                                         <form action="{{ route(get_area_name() . '.vault-transfers.destroy', $transfer) }}" method="POST" style="display: inline-block;">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟');">
                                                 <i class="fa fa-trash"></i>
                                             </button>
                                         </form>
                                            @endif
                                            @if ($transfer->status === 'pending' && ( ($transfer->toVault->branch_id && ($transfer->toVault->branch_id == auth()->user()->branch_id)) || (!$transfer->toVault->branch_id && !auth()->user()->branch_id )  ) )
                                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $transfer->id }}">
                                             <i class="fa fa-check"></i> 
                                         </button>
                                         <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $transfer->id }}">
                                             <i class="fa fa-times"></i> 
                                         </button>

                                         <!-- Approve Modal -->
                                         <div class="modal fade" id="approveModal{{ $transfer->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $transfer->id }}" aria-hidden="true">
                                             <div class="modal-dialog">
                                                 <div class="modal-content">
                                                     <div class="modal-header bg-success text-white">
                                                         <h5 class="modal-title" id="approveModalLabel{{ $transfer->id }}">الموافقة على التحويل #{{ $transfer->id }}</h5>
                                                         <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                     </div>
                                                     <form action="{{ route(get_area_name() . '.vault-transfers.approve', $transfer) }}" method="POST">
                                                         @csrf
                                                         @method('PATCH')
                                                         <div class="modal-body">
                                                             <div class="alert alert-info">
                                                                 <strong>تفاصيل التحويل:</strong><br>
                                                                 من: {{ $transfer->fromVault->name ?? '-' }}<br>
                                                                 إلى: {{ $transfer->toVault->name ?? '-' }}<br>
                                                                 المبلغ: {{ number_format($transfer->amount, 2) }}
                                                             </div>
                                                             <div class="mb-3">
                                                                 <label for="approve_note{{ $transfer->id }}" class="form-label">ملاحظات الموافقة</label>
                                                                 <textarea name="approve_note" id="approve_note{{ $transfer->id }}" class="form-control" rows="3" placeholder="أدخل ملاحظاتك (اختياري)"></textarea>
                                                             </div>
                                                         </div>
                                                         <div class="modal-footer">
                                                             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                             <button type="submit" class="btn btn-success">
                                                                 <i class="fa fa-check"></i> موافقة على التحويل
                                                             </button>
                                                         </div>
                                                     </form>
                                                 </div>
                                             </div>
                                         </div>

                                         <!-- Reject Modal -->
                                         <div class="modal fade" id="rejectModal{{ $transfer->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $transfer->id }}" aria-hidden="true">
                                             <div class="modal-dialog">
                                                 <div class="modal-content">
                                                     <div class="modal-header bg-danger text-white">
                                                         <h5 class="modal-title" id="rejectModalLabel{{ $transfer->id }}">رفض التحويل #{{ $transfer->id }}</h5>
                                                         <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                     </div>
                                                     <form action="{{ route(get_area_name() . '.vault-transfers.reject', $transfer) }}" method="POST">
                                                         @csrf
                                                         @method('PATCH')
                                                         <div class="modal-body">
                                                             <div class="alert alert-warning">
                                                                 <strong>تفاصيل التحويل:</strong><br>
                                                                 من: {{ $transfer->fromVault->name ?? '-' }}<br>
                                                                 إلى: {{ $transfer->toVault->name ?? '-' }}<br>
                                                                 المبلغ: {{ number_format($transfer->amount, 2) }}
                                                             </div>
                                                             <div class="mb-3">
                                                                 <label for="reject_note{{ $transfer->id }}" class="form-label">سبب الرفض <span class="text-danger">*</span></label>
                                                                 <textarea name="reject_note" id="reject_note{{ $transfer->id }}" class="form-control" rows="3" placeholder="أدخل سبب الرفض (إجباري)" required></textarea>
                                                                 <div class="form-text">يجب توضيح سبب رفض التحويل</div>
                                                             </div>
                                                         </div>
                                                         <div class="modal-footer">
                                                             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                             <button type="submit" class="btn btn-danger">
                                                                 <i class="fa fa-times"></i> رفض التحويل
                                                             </button>
                                                         </div>
                                                     </form>
                                                 </div>
                                             </div>
                                         </div>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">لا توجد تحويلات حساب مسجلة.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $vaultTransfers->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle filters visibility
    const toggleButton = document.getElementById('toggleFilters');
    const filtersForm = document.getElementById('filtersForm');
    
    toggleButton.addEventListener('click', function() {
        if (filtersForm.style.display === 'none') {
            filtersForm.style.display = 'block';
            this.innerHTML = '<i class="fa fa-eye-slash"></i> إخفاء';
        } else {
            filtersForm.style.display = 'none';
            this.innerHTML = '<i class="fa fa-eye"></i> إظهار';
        }
    });

    // Auto-submit form on select changes (optional)
    const autoSubmitElements = ['status', 'from_vault_id', 'to_vault_id', 'user_id', 'branch_id'];
    autoSubmitElements.forEach(function(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.addEventListener('change', function() {
                // Uncomment the line below if you want auto-submit on selection
                // document.getElementById('filtersForm').submit();
            });
        }
    });

    // Store filter state in localStorage
    const filterForm = document.getElementById('filtersForm');
    const saveFiltersBtn = document.createElement('button');
    saveFiltersBtn.type = 'button';
    saveFiltersBtn.className = 'btn btn-outline-info btn-sm';
    saveFiltersBtn.innerHTML = '<i class="fa fa-save"></i> حفظ الفلاتر';
    saveFiltersBtn.onclick = function() {
        const formData = new FormData(filterForm);
        const filters = {};
        for (let [key, value] of formData.entries()) {
            if (value) filters[key] = value;
        }
        localStorage.setItem('vaultTransferFilters', JSON.stringify(filters));
        alert('تم حفظ الفلاتر بنجاح');
    };

    const loadFiltersBtn = document.createElement('button');
    loadFiltersBtn.type = 'button';
    loadFiltersBtn.className = 'btn btn-outline-secondary btn-sm ms-1';
    loadFiltersBtn.innerHTML = '<i class="fa fa-folder-open"></i> تحميل الفلاتر';
    loadFiltersBtn.onclick = function() {
        const savedFilters = localStorage.getItem('vaultTransferFilters');
        if (savedFilters) {
            const filters = JSON.parse(savedFilters);
            Object.keys(filters).forEach(key => {
                const element = filterForm.querySelector(`[name="${key}"]`);
                if (element) element.value = filters[key];
            });
            alert('تم تحميل الفلاتر المحفوظة');
        } else {
            alert('لا توجد فلاتر محفوظة');
        }
    };

    // Add buttons to filter section
    const filterButtons = document.querySelector('#filtersForm .col-md-4');
    if (filterButtons) {
        filterButtons.appendChild(saveFiltersBtn);
        filterButtons.appendChild(loadFiltersBtn);
    }

    // Quick filter buttons
    const quickFiltersContainer = document.createElement('div');
    quickFiltersContainer.className = 'mb-3';
    quickFiltersContainer.innerHTML = `
        <div class="btn-group" role="group" aria-label="Quick Filters">
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="applyQuickFilter('pending')">
                <i class="fa fa-clock"></i> قيد الانتظار
            </button>
            <button type="button" class="btn btn-outline-success btn-sm" onclick="applyQuickFilter('approved')">
                <i class="fa fa-check"></i> موافق عليها
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="applyQuickFilter('rejected')">
                <i class="fa fa-times"></i> مرفوضة
            </button>
            <button type="button" class="btn btn-outline-info btn-sm" onclick="applyQuickFilter('today')">
                <i class="fa fa-calendar"></i> اليوم
            </button>
            <button type="button" class="btn btn-outline-warning btn-sm" onclick="applyQuickFilter('thisweek')">
                <i class="fa fa-calendar-week"></i> هذا الأسبوع
            </button>
        </div>
    `;

    // Insert quick filters before the main filters
    filterForm.parentNode.insertBefore(quickFiltersContainer, filterForm);

    // Quick filter function
    window.applyQuickFilter = function(type) {
        // Reset form first
        filterForm.reset();
        
        switch(type) {
            case 'pending':
            case 'approved':
            case 'rejected':
                document.getElementById('status').value = type;
                break;
            case 'today':
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('date_from').value = today;
                document.getElementById('date_to').value = today;
                break;
            case 'thisweek':
                const now = new Date();
                const startOfWeek = new Date(now.setDate(now.getDate() - now.getDay()));
                const endOfWeek = new Date(now.setDate(now.getDate() - now.getDay() + 6));
                document.getElementById('date_from').value = startOfWeek.toISOString().split('T')[0];
                document.getElementById('date_to').value = endOfWeek.toISOString().split('T')[0];
                break;
        }
        filterForm.submit();
    };
});
</script>

@endsection