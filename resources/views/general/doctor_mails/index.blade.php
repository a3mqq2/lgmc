@extends('layouts.'.get_area_name())
@section('title', 'طلبات أوراق الخارج')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-envelope text-primary me-2"></i>
                    طلبات  اوراق الخارج
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mt-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">طلبات اوراق الخارج</li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto">
                <div class="btn-group">
                    <a href="{{ route('admin.doctor-mails.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        طلب جديد
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2"></i>
                    تصفية النتائج
                </h5>
            </div>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <form action="{{ route('admin.doctor-mails.index') }}" method="GET" id="filterForm">
                    <div class="row g-3">
                        <!-- Search Input -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted">البحث</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="search" 
                                       placeholder="رقم الطلب، اسم الطبيب، البريد الإلكتروني..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>


                        <!-- Status Filter -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted">حالة الطلب</label>
                            <select class="form-select" name="status">
                                <option value="">جميع الحالات</option>
                                <option value="under_approve" {{ request('status') == 'under_approve' ? 'selected' : '' }}>
                                    <i class="fas fa-clock"></i> قيد الموافقة
                                </option>
                                <option value="under_edit" {{ request('status') == 'under_edit' ? 'selected' : '' }}>
                                    <i class="fas fa-spinner"></i> قيد التعديل
                                </option>
                                <option value="under_payment" {{ request('status') == 'under_payment' ? 'selected' : '' }}>
                                    <i class="fas fa-check"></i> قيد الدفع
                                </option>
                                <option value="under_proccess" {{ request('status') == 'under_proccess' ? 'selected' : '' }}>
                                  <i class="fas fa-times"></i> قيد الإجراء
                              </option>
                              <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>
                                <i class="fas fa-times"></i> مكتمل 
                            </option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted">من تاريخ</label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small text-muted">إلى تاريخ</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>


                        <!-- Action Buttons -->
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>
                                    بحث
                                </button>
                                <a href="{{ route('admin.doctor-mails.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-2"></i>
                                    إعادة تعيين
                                </a>
                                @if(request()->filled('search') || request()->filled('status') || request()->filled('doctor_id'))
                                    <div class="ms-auto">
                                        <small class="text-muted">
                                            عرض {{ $doctorMails->count() }} من {{ $doctorMails->total() }} نتيجة
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class=" px-4">#</th>
                            <th class="">رقم الطلب</th>
                            <th class="">الطبيب</th>
                            <th class="">الخدمات</th>
                            <th class="">الإيميلات</th>
                            <th class="">المبلغ الإجمالي</th>
                            <th class="">الحالة</th>
                            <th class="">تاريخ الإنشاء</th>
                            <th class=" text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctorMails as $mail)
                            <tr>
                                <td class="px-4">{{ $loop->iteration + ($doctorMails->currentPage() - 1) * $doctorMails->perPage() }}</td>
                                <td>
                                    <span class="badge bg-secondary">#{{ str_pad($mail->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            @if($mail->doctor->photo)
                                                <img src="{{ asset($mail->doctor->photo) }}" class="rounded-circle" alt="">
                                            @else
                                                <div class="avatar-title rounded-circle bg-primary text-white">
                                                    {{ substr($mail->doctor->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $mail->doctor->name }}</div>
                                            <small class="text-muted">{{ $mail->doctor->code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($mail->services->take(2) as $service)
                                            <span class="badge bg-info">{{ $service->service_name }}</span>
                                        @endforeach
                                        @if($mail->services->count() > 2)
                                            <span class="badge bg-secondary">+{{ $mail->services->count() - 2 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-envelope text-muted me-2"></i>
                                        <span>{{ $mail->emails->count() }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ number_format($mail->grand_total, 2) }}</span>
                                    <small class="text-muted">د.ل</small>
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'under_approve' => 'warning',
                                            'under_payment' => 'info',
                                            "under_edit" => "secondary",
                                            'under_proccess' => 'primary',
                                            "done" => "success",
                                        ];
                                        $statusIcons = [
                                            'under_approve' => 'clock',
                                            'under_payment' => 'spinner',
                                            "under_edit" => "edit",
                                            'done' => 'check-circle',
                                            "under_proccess" => "spinner",
                                        ];
                                        $statusLabels = [
                                            'under_approve' => 'قيد الموافقة',
                                            'under_payment' => 'قيد الدفع',
                                            "under_edit" => "قيد التعديل",
                                            "under_proccess" => "قيد الاجراء",
                                            'done' => 'مكتمل',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusClasses[$mail->status] ?? 'secondary' }} bg-opacity-10 text-light px-3 py-2">
                                        <i class="fas fa-{{ $statusIcons[$mail->status] ?? 'question' }} me-1"></i>
                                        {{ $statusLabels[$mail->status] ?? $mail->status }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <div class="text-muted small">{{ $mail->created_at->format('Y/m/d') }}</div>
                                        <div class="text-muted small">{{ $mail->created_at->format('h:i A') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.doctor-mails.show', $mail) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           data-bs-toggle="tooltip" 
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($mail->status == 'pending')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success"
                                                    onclick="updateStatus({{ $mail->id }}, 'in_progress')"
                                                    data-bs-toggle="tooltip" 
                                                    title="بدء التنفيذ">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        @endif
                                        @if(in_array($mail->status, ['pending', 'in_progress']))
                                            <a href="{{ route('admin.doctor-mails.edit', $mail) }}" 
                                               class="btn btn-sm btn-outline-warning"
                                               data-bs-toggle="tooltip" 
                                               title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete({{ $mail->id }})"
                                                data-bs-toggle="tooltip" 
                                                title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                        <p class="text-muted">لا توجد طلبات خارج</p>
                                        <a href="{{ route('admin.doctor-mails.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>
                                            إنشاء طلب جديد
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($doctorMails->hasPages())
            <div class="card-footer bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        عرض {{ $doctorMails->firstItem() }} إلى {{ $doctorMails->lastItem() }} من {{ $doctorMails->total() }} سجل
                    </div>
                    {{ $doctorMails->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p class="mb-0">هل أنت متأكد من حذف هذا الطلب؟</p>
                <p class="text-muted small">لا يمكن التراجع عن هذا الإجراء</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Form -->
<form id="statusForm" method="POST" class="d-none">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" id="statusInput">
</form>

@endsection

@section('styles')
<style>
    .page-header {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 1rem;
    }

    .avatar {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1rem;
    }

    .avatar-sm {
        width: 2rem;
        height: 2rem;
        font-size: 0.875rem;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }

    .badge {
        font-weight: 500;
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }

    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }

    .empty-state {
        padding: 3rem 0;
    }

    .select2-container--default .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 0.75rem);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }
</style>
@endsection

@section('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Initialize Select2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'اختر...',
            allowClear: true,
            dir: 'rtl'
        });
    });

    // Delete confirmation
    function confirmDelete(id) {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const form = document.getElementById('deleteForm');
        form.action = `/admin/doctor-mails/${id}`;
        modal.show();
    }

    // Update status
    function updateStatus(id, status) {
        if (confirm('هل أنت متأكد من تغيير حالة الطلب؟')) {
            const form = document.getElementById('statusForm');
            const statusInput = document.getElementById('statusInput');
            statusInput.value = status;
            form.action = `/admin/doctor-mails/${id}/status`;
            form.submit();
        }
    }

    // Export data
    function exportData() {
        const params = new URLSearchParams(window.location.search);
        params.append('export', 'excel');
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    // Auto-submit form on select change
    document.querySelectorAll('select[name="status"], select[name="has_files"], select[name="email_count"]').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endsection