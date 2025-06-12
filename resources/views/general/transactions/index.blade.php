@extends('layouts.' . get_area_name())
@section('title', 'قائمة المعاملات المالية')

@section('content')
    {{-- إحصائيات سريعة --}}

    <div class="row">
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
                            <button type="button" class="btn btn-outline-light btn-sm me-2" data-bs-toggle="modal" data-bs-target="#reportModal">
                                <i class="fa fa-chart-bar"></i> تقرير مفصل
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
                                        <label for="financial_category_id" class="form-label select2">
                                            <i class="fa fa-tags"></i> التصنيف المالي
                                        </label>
                                        <select name="financial_category_id" id="financial_category_id" class="form-control select2">
                                            <option value="">جميع التصنيفات</option>
                                            @foreach ($financialCategories as $category)
                                                <option value="{{ $category->id }}" {{ $category->id == request('financial_category_id') ? 'selected' : '' }}>
                                                    <span class="badge bg-{{ $category->type_color }}">{{ $category->type_name }}</span>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
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
                                        <i class="fa fa-tags"></i> التصنيف المالي
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
                                            @if($transaction->financialCategory)
                                                <div class="d-flex flex-column">
                                                    <span class="badge bg-{{ $transaction->financialCategory->type_color }} mb-1">
                                                        <i class="fa fa-tag me-1"></i>
                                                        {{ $transaction->financialCategory->name }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fa fa-minus"></i> غير محدد
                                                </span>
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
                                                {{ number_format($transaction->balance ?? 0, 2) }} د.ل
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
                                        <td colspan="9" class="text-center py-4">
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

                    {{-- الصفحات --}}
                    <div class="row mt-3">
                        <div class="col-md-12">
                            {{ $transactions->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- مودال التقرير المفصل --}}
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="reportModalLabel">
                        <i class="fa fa-chart-bar me-2"></i>
                        كشف حساب الخزينة المفصل
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route(get_area_name().'.transactions.report') }}" method="GET" target="_blank">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="report_vault_id" class="form-label">
                                    <i class="fa fa-vault"></i> اختر الخزينة
                                </label>
                                <select name="vault_id" id="report_vault_id" class="form-control select2" required>
                                    <option value="">حدد خزينة</option>
                                    @foreach ($vaults as $vault)
                                        <option value="{{ $vault->id }}" {{ request('vault_id') == $vault->id ? 'selected' : '' }}>
                                            {{ $vault->name }} - الرصيد: {{ number_format($vault->balance, 2) }} د.ل
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="report_from_date" class="form-label">
                                    <i class="fa fa-calendar"></i> من تاريخ
                                </label>
                                <input type="date" name="from_date" id="report_from_date" class="form-control" 
                                       value="{{ request('from_date', now()->subMonth()->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="report_to_date" class="form-label">
                                    <i class="fa fa-calendar"></i> إلى تاريخ
                                </label>
                                <input type="date" name="to_date" id="report_to_date" class="form-control" 
                                       value="{{ request('to_date', now()->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="report_type" class="form-label">
                                    <i class="fa fa-filter"></i> نوع المعاملة
                                </label>
                                <select name="type" id="report_type" class="form-control">
                                    <option value="">جميع المعاملات</option>
                                    <option value="deposit">الإيداعات فقط</option>
                                    <option value="withdrawal">السحوبات فقط</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="report_format" class="form-label">
                                    <i class="fa fa-file"></i> صيغة التقرير
                                </label>
                                <select name="format" id="report_format" class="form-control">
                                    <option value="view">عرض في المتصفح</option>
                                    <option value="print">للطباعة</option>
                                </select>
                            </div>
                        </div>

                        <div class="border-top pt-3">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-chart-bar me-2"></i>
                                    إنشاء التقرير
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="generateQuickReport()">
                                    <i class="fa fa-eye me-2"></i>
                                    معاينة سريعة
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- معاينة سريعة للتقرير --}}
                    <div id="quickReportPreview" class="mt-4" style="display: none;">
                        <hr>
                        <h6><i class="fa fa-eye me-2"></i>معاينة سريعة للتقرير</h6>
                        <div id="previewContent" class="bg-light p-3 rounded">
                            {{-- سيتم ملء المحتوى بـ JavaScript --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-2"></i>
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
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
});

// طباعة التقرير الحالي
function printCurrentReport() {
    const params = new URLSearchParams(window.location.search);
    params.set('format', 'print');
    
    const printUrl = `{{ route(get_area_name().'.transactions.report') }}?${params.toString()}`;
    window.open(printUrl, '_blank');
}

// إنشاء معاينة سريعة للتقرير
function generateQuickReport() {
    const vaultId = document.getElementById('report_vault_id').value;
    const fromDate = document.getElementById('report_from_date').value;
    const toDate = document.getElementById('report_to_date').value;
    const reportType = document.getElementById('report_type').value;

    if (!vaultId || !fromDate || !toDate) {
        alert('يرجى ملء جميع الحقول المطلوبة');
        return;
    }

    // إظهار معاينة سريعة
    const previewDiv = document.getElementById('quickReportPreview');
    const previewContent = document.getElementById('previewContent');
    
    previewContent.innerHTML = `
        <div class="text-center">
            <i class="fa fa-spinner fa-spin fa-2x text-primary mb-3"></i>
            <p>جاري تحضير المعاينة...</p>
        </div>
    `;
    previewDiv.style.display = 'block';

    // محاكاة تحميل البيانات (يجب استبدالها بطلب AJAX حقيقي)
    setTimeout(() => {
        const selectedVault = document.querySelector(`#report_vault_id option[value="${vaultId}"]`).textContent;
        
        previewContent.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th>الخزينة:</th>
                            <td>${selectedVault}</td>
                        </tr>
                        <tr>
                            <th>الفترة:</th>
                            <td>من ${fromDate} إلى ${toDate}</td>
                        </tr>
                        <tr>
                            <th>نوع التقرير:</th>
                            <td>${reportType || 'جميع المعاملات'}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-info mb-0">
                        <i class="fa fa-info-circle me-2"></i>
                        <strong>ملاحظة:</strong> هذه معاينة سريعة. للحصول على التقرير الكامل، انقر على "إنشاء التقرير".
                    </div>
                </div>
            </div>
        `;
    }, 1500);
}

// تحديث تلقائي للصفحة كل 5 دقائق
setTimeout(function() {
    location.reload();
}, 300000);
</script>
@endpush

@push('styles')
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

/* أنماط التصنيفات المالية */
.badge.bg-success {
    background-color: #198754 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
}

.badge.bg-secondary {
    background-color: #6c757d !important;
}

/* أنماط الإجماليات */
tfoot tr {
    background-color: #f8f9fa;
    border-top: 2px solid #dee2e6;
}

tfoot td {
    font-weight: 600;
    font-size: 1.1em;
}

/* أنماط بطاقات الإحصائيات */
.card.bg-success .opacity-75 {
    opacity: 0.75;
}

.card.bg-danger .opacity-75 {
    opacity: 0.75;
}

.card.bg-info .opacity-75 {
    opacity: 0.75;
}

.card.bg-warning .opacity-75 {
    opacity: 0.75;
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
    
    /* إخفاء بعض الأعمدة في الشاشات الصغيرة */
    .table th:nth-child(4), 
    .table td:nth-child(4),
    .table th:nth-child(8), 
    .table td:nth-child(8) {
        display: none;
    }

    /* تحسين بطاقات الإحصائيات للموبايل */
    .card h4 {
        font-size: 1.2rem;
    }
    
    .card h6 {
        font-size: 0.9rem;
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

/* أنماط خاصة بالتصنيفات المالية */
.financial-category-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.financial-category-badge .fa-tag {
    font-size: 0.8em;
}

/* تحسين عرض التصنيفات في الجدول */
.table td .d-flex.flex-column {
    min-width: 120px;
}

.table td .badge {
    white-space: nowrap;
}

/* تحسين الفلاتر */
.select2-container .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}

.select2-container .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
}

/* تحسين المودالات */
.modal-body .form-label {
    font-weight: 600;
    color: #495057;
}

.modal-body .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* أنماط معاينة التقرير */
#quickReportPreview {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* تحسين أزرار الرأس */
.card-header .btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.3);
}

/* أنماط التحقق من الصحة */
.form-control:valid {
    border-color: #198754;
}

.form-control:invalid {
    border-color: #dc3545;
}

/* تحسين شكل الجدول */
.table thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #212529;
}

/* أنماط الطباعة */
@media print {
    .card-header .btn-group,
    .btn,
    .modal {
        display: none !important;
    }
    
    .table {
        font-size: 12px;
    }
    
    .card {
        box-shadow: none;
        border: 1px solid #000;
    }
}
</style>
@endpush