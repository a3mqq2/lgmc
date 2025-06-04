@extends('layouts.' . get_area_name())
@section('title', 'قائمة المعاملات المالية')

@section('content')
    <div class="row">
        {{-- إحصائيات سريعة --}}
        <div class="col-md-12 mb-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fa fa-arrow-down fa-2x mb-2"></i>
                            <h4>{{ number_format($statistics['total_deposits'], 2) }} د.ل</h4>
                            <small>إجمالي الإيداعات</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="fa fa-arrow-up fa-2x mb-2"></i>
                            <h4>{{ number_format($statistics['total_withdrawals'], 2) }} د.ل</h4>
                            <small>إجمالي السحوبات</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fa fa-balance-scale fa-2x mb-2"></i>
                            <h4>{{ number_format($statistics['net_balance'], 2) }} د.ل</h4>
                            <small>الرصيد الصافي</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fa fa-calculator fa-2x mb-2"></i>
                            <h4>{{ $statistics['total_transactions'] }}</h4>
                            <small>إجمالي المعاملات</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- الأزرار الرئيسية --}}
        <div class="col-md-12 mb-3">
            <div class="row">
                <div class="col-md-8">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDepositModal">
                            <i class="fa fa-plus"></i> إضافة إيداع
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addWithdrawalModal">
                            <i class="fa fa-minus"></i> إضافة سحب
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group">
                        @if(count(array_filter(request()->except('page'))))
                        <a  class="btn btn-secondary">
                            <i class="fa fa-download"></i> تصدير
                        </a>
                        <a   class="btn btn-outline-secondary" target="_blank">
                            <i class="fa fa-print"></i> طباعة
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-list"></i> قائمة المعاملات المالية
                        </h4>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-light text-dark me-2">
                                {{ $transactions->total() }} معاملة
                            </span>
                            <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                                <i class="fa fa-filter"></i> الفلاتر
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- فلاتر البحث --}}
                    <div class="collapse show" id="filtersCollapse">
                        <div class="bg-light p-3 rounded mb-3">
                            <form action="{{ route(get_area_name().'.transactions.index') }}" method="GET" id="filtersForm">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="user_id" class="form-label">
                                            <i class="fa fa-user"></i> المستخدم
                                        </label>
                                        <select name="user_id" id="user_id" class="form-control select2">
                                            <option value="">جميع المستخدمين</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ $user->id == request('user_id') ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="vault_id" class="form-label">
                                            <i class="fa fa-vault"></i> الخزينة
                                        </label>
                                        <select name="vault_id" id="vault_id" class="form-control select2">
                                            <option value="">جميع الخزائن</option>
                                            @foreach ($vaults as $vault)
                                                <option value="{{ $vault->id }}" {{ $vault->id == request('vault_id') ? 'selected' : '' }}>
                                                    {{ $vault->name }} - {{ number_format($vault->balance, 2) }} د.ل
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="type" class="form-label">
                                            <i class="fa fa-exchange-alt"></i> نوع العملية
                                        </label>
                                        <select class="form-control" id="type" name="type">
                                            <option value="">جميع العمليات</option>
                                            <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>
                                                <i class="fa fa-arrow-down"></i> إيداع
                                            </option>
                                            <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>
                                                <i class="fa fa-arrow-up"></i> سحب
                                            </option>
                                            <option value="transfer" {{ request('type') == 'transfer' ? 'selected' : '' }}>
                                                <i class="fa fa-exchange-alt"></i> تحويل
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="amount_range" class="form-label">
                                            <i class="fa fa-money-bill"></i> نطاق المبلغ
                                        </label>
                                        <select name="amount_range" id="amount_range" class="form-control">
                                            <option value="">جميع المبالغ</option>
                                            <option value="0-100" {{ request('amount_range') == '0-100' ? 'selected' : '' }}>أقل من 100 د.ل</option>
                                            <option value="100-500" {{ request('amount_range') == '100-500' ? 'selected' : '' }}>100 - 500 د.ل</option>
                                            <option value="500-1000" {{ request('amount_range') == '500-1000' ? 'selected' : '' }}>500 - 1000 د.ل</option>
                                            <option value="1000+" {{ request('amount_range') == '1000+' ? 'selected' : '' }}>أكثر من 1000 د.ل</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="from_date" class="form-label">
                                            <i class="fa fa-calendar"></i> من تاريخ
                                        </label>
                                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="to_date" class="form-label">
                                            <i class="fa fa-calendar"></i> إلى تاريخ
                                        </label>
                                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="desc" class="form-label">
                                            <i class="fa fa-search"></i> البحث في الوصف
                                        </label>
                                        <input type="text" class="form-control" id="desc" name="desc" value="{{ request('desc') }}" placeholder="ابحث في الوصف...">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="sort_by" class="form-label">
                                            <i class="fa fa-sort"></i> ترتيب حسب
                                        </label>
                                        <select name="sort_by" id="sort_by" class="form-control">
                                            <option value="created_at_desc" {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>الأحدث</option>
                                            <option value="created_at_asc" {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>الأقدم</option>
                                            <option value="amount_desc" {{ request('sort_by') == 'amount_desc' ? 'selected' : '' }}>أعلى مبلغ</option>
                                            <option value="amount_asc" {{ request('sort_by') == 'amount_asc' ? 'selected' : '' }}>أقل مبلغ</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i> تطبيق الفلاتر
                                            </button>
                                            <a href="{{ route(get_area_name().'.transactions.index') }}" class="btn btn-outline-secondary">
                                                <i class="fa fa-undo"></i> إعادة التعيين
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    {{-- جدول المعاملات --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                             
                                    <th>
                                        <i class="fa fa-hashtag"></i> رقم المعاملة
                                    </th>
                                    <th>
                                        <i class="fa fa-vault"></i> الخزينة
                                    </th>
                                    <th>
                                        <i class="fa fa-user"></i> المستخدم
                                    </th>
                                    <th>
                                        <i class="fa fa-file-text"></i> الوصف
                                    </th>
                                    <th class="bg-danger text-light">
                                        <i class="fa fa-arrow-up"></i> سحب
                                    </th>
                                    <th class="bg-success text-light">
                                        <i class="fa fa-arrow-down"></i> إيداع
                                    </th>
                                    <th>
                                        <i class="fa fa-balance-scale"></i> الرصيد بعد العملية
                                    </th>
                                    <th>
                                        <i class="fa fa-clock"></i> التاريخ والوقت
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr class="{{ $transaction->type == 'withdrawal' ? 'table-danger' : ($transaction->type == 'deposit' ? 'table-success' : 'table-info') }}">
                                        <td>
                                            <strong>#{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                            @if($transaction->reference_id)
                                                <br><small class="text-muted">مرجع: {{ $transaction->reference_id }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $transaction->vault->name }}</span>
                                            @if($transaction->to_vault_id)
                                                <br><small class="text-muted">إلى: {{ $transaction->toVault->name }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $transaction->user->name }}</strong>
                                                    <br><small class="text-muted">{{ $transaction->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="transaction-desc" style="max-width: 200px;">
                                                {{ Str::limit($transaction->desc, 50) }}
                                                @if(strlen($transaction->desc) > 50)
                                                    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="tooltip" title="{{ $transaction->desc }}">
                                                        <i class="fa fa-info-circle"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($transaction->type == "withdrawal")
                                                <span class="text-danger fw-bold">
                                                    <i class="fa fa-minus-circle"></i>
                                                    {{ number_format($transaction->amount, 2) }} د.ل
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaction->type == "deposit")
                                                <span class="text-success fw-bold">
                                                    <i class="fa fa-plus-circle"></i>
                                                    {{ number_format($transaction->amount, 2) }} د.ل
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ number_format($transaction->balance_after ?? 0, 2) }} د.ل
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $transaction->created_at->format('Y-m-d') }}</strong>
                                                <br><small class="text-muted">{{ $transaction->created_at->format('H:i:s') }}</small>
                                                <br><small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">لا توجد معاملات</h5>
                                                <p class="text-muted">لم يتم العثور على معاملات مالية بالمعايير المحددة</p>
                                                <a href="{{ route(get_area_name().'.transactions.index') }}" class="btn btn-outline-primary">
                                                    <i class="fa fa-undo"></i> إعادة تعيين الفلاتر
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- إجراءات جماعية --}}
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div id="bulkActions" style="display: none;">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-primary" onclick="bulkExport()">
                                        <i class="fa fa-download"></i> تصدير المحدد
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="bulkPrint()">
                                        <i class="fa fa-print"></i> طباعة المحدد
                                    </button>
                                    @if(auth()->user()->can('delete_transactions'))
                                    <button type="button" class="btn btn-outline-danger" onclick="bulkDelete()">
                                        <i class="fa fa-trash"></i> حذف المحدد
                                    </button>
                                    @endif
                                </div>
                                <span id="selectedCount" class="ms-3 text-muted"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{ $transactions->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة إيداع --}}
    <div class="modal fade" id="addDepositModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route(get_area_name().'.transactions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="deposit">
                    
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fa fa-plus"></i> إضافة إيداع
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="depositVault" class="form-label">الخزينة</label>
                            <select name="vault_id" id="depositVault" class="form-control" required>
                                <option value="">اختر الخزينة</option>
                                @foreach($vaults as $vault)
                                    <option value="{{ $vault->id }}">{{ $vault->name }} - الرصيد: {{ number_format($vault->balance, 2) }} د.ل</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="depositAmount" class="form-label">المبلغ</label>
                            <input type="number" step="0.01" name="amount" id="depositAmount" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="depositDesc" class="form-label">الوصف</label>
                            <textarea name="desc" id="depositDesc" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-plus"></i> إضافة الإيداع
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- مودال إضافة سحب --}}
    <div class="modal fade" id="addWithdrawalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route(get_area_name().'.transactions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="withdrawal">
                    
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fa fa-minus"></i> إضافة سحب
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="withdrawalVault" class="form-label">الخزينة</label>
                            <select name="vault_id" id="withdrawalVault" class="form-control" required>
                                <option value="">اختر الخزينة</option>
                                @foreach($vaults as $vault)
                                    <option value="{{ $vault->id }}" data-balance="{{ $vault->balance }}">
                                        {{ $vault->name }} - الرصيد: {{ number_format($vault->balance, 2) }} د.ل
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="withdrawalAmount" class="form-label">المبلغ</label>
                            <input type="number" step="0.01" name="amount" id="withdrawalAmount" class="form-control" required>
                            <div id="balanceWarning" class="text-danger mt-1" style="display: none;">
                                <i class="fa fa-exclamation-triangle"></i> المبلغ أكبر من الرصيد المتاح
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="withdrawalDesc" class="form-label">الوصف</label>
                            <textarea name="desc" id="withdrawalDesc" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger" id="submitWithdrawal">
                            <i class="fa fa-minus"></i> تنفيذ السحب
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- مودال عرض تفاصيل المعاملة --}}
    <div class="modal fade" id="viewTransactionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fa fa-eye"></i> تفاصيل المعاملة
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body" id="transactionDetails">
                    {{-- سيتم ملؤها بـ JavaScript --}}
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="printCurrentTransaction()">
                        <i class="fa fa-print"></i> طباعة
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة Select2
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2({
            placeholder: 'اختر...',
            allowClear: true,
            width: '100%'
        });
    }

    // تهيئة Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // التحديد الجماعي
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.transaction-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const selected = document.querySelectorAll('.transaction-checkbox:checked');
        if (selected.length > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = `تم تحديد ${selected.length} معاملة`;
        } else {
            bulkActions.style.display = 'none';
        }
    }

    // التحقق من رصيد السحب
    const withdrawalVault = document.getElementById('withdrawalVault');
    const withdrawalAmount = document.getElementById('withdrawalAmount');
    const balanceWarning = document.getElementById('balanceWarning');
    const submitWithdrawal = document.getElementById('submitWithdrawal');

    function checkBalance() {
        const selectedVault = withdrawalVault.options[withdrawalVault.selectedIndex];
        const balance = parseFloat(selectedVault.dataset.balance || 0);
        const amount = parseFloat(withdrawalAmount.value || 0);

        if (amount > balance) {
            balanceWarning.style.display = 'block';
            submitWithdrawal.disabled = true;
        } else {
            balanceWarning.style.display = 'none';
            submitWithdrawal.disabled = false;
        }
    }

    if (withdrawalVault && withdrawalAmount) {
        withdrawalVault.addEventListener('change', checkBalance);
        withdrawalAmount.addEventListener('input', checkBalance);
    }

    // عرض تفاصيل المعاملة
    const viewTransactionModal = document.getElementById('viewTransactionModal');
    if (viewTransactionModal) {
        viewTransactionModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const transaction = JSON.parse(button.getAttribute('data-transaction'));
            
            const detailsContainer = document.getElementById('transactionDetails');
            detailsContainer.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>رقم المعاملة:</th>
                                <td>#${String(transaction.id).padStart(6, '0')}</td>
                            </tr>
                            <tr>
                                <th>نوع العملية:</th>
                                <td>
                                    <span class="badge ${transaction.type === 'deposit' ? 'bg-success' : 'bg-danger'}">
                                        ${transaction.type === 'deposit' ? 'إيداع' : 'سحب'}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>المبلغ:</th>
                                <td class="${transaction.type === 'deposit' ? 'text-success' : 'text-danger'} fw-bold">
                                    ${parseFloat(transaction.amount).toLocaleString('ar-LY', {minimumFractionDigits: 2})} د.ل
                                </td>
                            </tr>
                            <tr>
                                <th>الخزينة:</th>
                                <td>${transaction.vault.name}</td>
                            </tr>
                            <tr>
                                <th>المستخدم:</th>
                                <td>${transaction.user.name}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>التاريخ:</th>
                                <td>${new Date(transaction.created_at).toLocaleDateString('ar-LY')}</td>
                            </tr>
                            <tr>
                                <th>الوقت:</th>
                                <td>${new Date(transaction.created_at).toLocaleTimeString('ar-LY')}</td>
                            </tr>
                            <tr>
                                <th>الرصيد بعد العملية:</th>
                                <td class="text-info fw-bold">
                                    ${parseFloat(transaction.balance_after || 0).toLocaleString('ar-LY', {minimumFractionDigits: 2})} د.ل
                                </td>
                            </tr>
                            ${transaction.reference_id ? `
                            <tr>
                                <th>الرقم المرجعي:</th>
                                <td>${transaction.reference_id}</td>
                            </tr>` : ''}
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h6>الوصف:</h6>
                        <div class="bg-light p-3 rounded">
                            ${transaction.desc}
                        </div>
                    </div>
                </div>
            `;
        });
    }
});

// دوال JavaScript للإجراءات
function confirmDelete(transactionId) {
    if (confirm('هل أنت متأكد من حذف هذه المعاملة؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route(get_area_name().'.transactions.destroy', '') }}/${transactionId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function printTransaction(transactionId) {

}

function printCurrentTransaction() {
    // طباعة المعاملة المعروضة حالياً
    window.print();
}

function bulkExport() {
    const selected = Array.from(document.querySelectorAll('.transaction-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        alert('يرجى تحديد معاملة واحدة على الأقل');
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';

    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    selected.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'transaction_ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
}

function bulkPrint() {
    const selected = Array.from(document.querySelectorAll('.transaction-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        alert('يرجى تحديد معamlة واحدة على الأقل');
        return;
    }
    
    const params = new URLSearchParams();
    selected.forEach(id => params.append('transaction_ids[]', id));
    
}

function bulkDelete() {
    const selected = Array.from(document.querySelectorAll('.transaction-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        alert('يرجى تحديد معاملة واحدة على الأقل');
        return;
    }
    
    if (confirm(`هل أنت متأكد من حذف ${selected.length} معاملة؟ هذا الإجراء لا يمكن التراجع عنه.`)) {
        const form = document.createElement('form');
        form.method = 'POST';

        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        selected.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'transaction_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// تحديث تلقائي للصفحة كل 5 دقائق
setTimeout(function() {
    location.reload();
}, 300000);
</script>

<style>
/* تحسينات CSS */
.avatar-sm {
    width: 32px;
    height: 32px;
}

.empty-state {
    padding: 40px 20px;
}

.transaction-desc {
    word-wrap: break-word;
}

.table-hover tbody tr:hover {
    background-color: rgba(247, 247, 247, 0.618);
}

.btn-group-sm > .btn {
    font-size: 0.8rem;
}

/* أنماط الجدول المحسنة */
.table th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.table-dark th {
    border-color: #454d55;
}

/* أنماط البطاقات */
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

/* أنماط الإحصائيات */
.card.bg-success, .card.bg-danger, .card.bg-info, .card.bg-warning {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* أنماط الفلاتر */
.bg-light {
    background-color: #f8f9fa !important;
}

/* أنماط الأزرار */
.btn-outline-primary:hover {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

/* أنماط الجداول المشروطة */
.table-success {
    background-color: rgba(25, 135, 84, 0.1);
}

.table-danger {
    background-color: rgba(220, 53, 69, 0.1);
}

.table-info {
    background-color: rgba(13, 202, 240, 0.1);
}

/* أنماط المودال */
.modal-header.bg-success,
.modal-header.bg-danger,
.modal-header.bg-info,
.modal-header.bg-warning {
    border-bottom: none;
}

/* أنماط النصوص */
.fw-bold {
    font-weight: 700 !important;
}

.text-muted {
    color: #6c757d !important;
}

/* أنماط الشارات */
.badge {
    font-size: 0.75em;
    font-weight: 600;
}

/* تحسينات الاستجابة */
@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin-bottom: 5px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .avatar-sm {
        width: 24px;
        height: 24px;
    }
}

/* أنماط التحديد */
.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

/* أنماط التحميل */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

/* أنماط التنبيهات */
.alert {
    border: none;
    border-radius: 8px;
}

.alert-info {
    background-color: rgba(13, 202, 240, 0.1);
    color: #055160;
}
</style>
@endsection