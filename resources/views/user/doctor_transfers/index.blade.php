@extends('layouts.' . get_area_name())

@section('title', 'قائمة تحويلات الأطباء')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="fa fa-exchange-alt"></i> تحويلات الأطباء
                </h4>
                <div class="d-flex gap-2">
                    <a href="{{ route(get_area_name() . '.doctor-transfers.create') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> إضافة طلب نقل جديد
                    </a>
                        <button type="button" class="btn btn-primary text-light" data-bs-toggle="modal" data-bs-target="#printReportModal">
                            <i class="fa fa-print"></i> تقرير قابل للطباعة
                        </button>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="GET" action="{{ route(get_area_name() . '.doctor-transfers.index') }}" class="mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label for="q" class="form-label">بحث</label>
                            <input type="text" id="q" name="q" class="form-control"
                                   placeholder="ابحث برقم الطلب أو اسم الطبيب أو السبب"
                                   value="{{ request('q') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">الحالة</label>
                            <select id="status" name="status" class="form-select">
                                <option value="">جميع الحالات</option>
                                <option value="pending"  {{ request('status')=='pending'  ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>موافق عليه</option>
                                <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>مرفوض</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-filter"></i> تصفية
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route(get_area_name() . '.doctor-transfers.index') }}" class="btn btn-secondary w-100">
                                <i class="fa fa-times"></i> إعادة تعيين
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>الطبيب</th>
                                <th>من الفرع</th>
                                <th>إلى الفرع</th>
                                <th>المستخدم</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($doctorTransfers as $transfer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transfer->doctor->name ?? '-' }}</td>
                                    <td>{{ $transfer->fromBranch->name ?? '-' }}</td>
                                    <td>{{ $transfer->toBranch->name ?? '-' }}</td>
                                    <td>{{ $transfer->createdBy->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transfer->status === 'approved' ? 'success' : ($transfer->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ $transfer->status === 'approved' ? 'موافق عليه' : ($transfer->status === 'rejected' ? 'مرفوض' : 'قيد الانتظار') }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route(get_area_name() . '.doctor-transfers.show', $transfer) }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if($transfer->status === 'pending')
                                            <form action="{{ route(get_area_name() . '.doctor-transfers.destroy', $transfer) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟');">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $transfer->id }}" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="approveModalLabel">الموافقة على طلب النقل</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route(get_area_name() . '.doctor-transfers.approve', $transfer) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="approve_note{{ $transfer->id }}" class="form-label">السبب</label>
                                                        <textarea name="approve_note" id="approve_note{{ $transfer->id }}" class="form-control" rows="3" placeholder="أدخل السبب (اختياري)"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                    <button type="submit" class="btn btn-success">موافقة</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $transfer->id }}" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rejectModalLabel">رفض طلب النقل</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route(get_area_name() . '.doctor-transfers.reject', $transfer) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="rejected_reason{{ $transfer->id }}" class="form-label">سبب الرفض</label>
                                                        <textarea name="rejected_reason" id="rejected_reason{{ $transfer->id }}" class="form-control" rows="3" placeholder="أدخل سبب الرفض (إجباري)" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                    <button type="submit" class="btn btn-danger">رفض</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr><td colspan="7" class="text-center">لا توجد تحويلات أطباء مسجلة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $doctorTransfers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- resources/views/user/doctor_transfers/index.blade.php  (ADD just before @endsection) --}}
{{-- ===== PRINTABLE REPORT BUTTON ===== --}}

{{-- ===== REPORT MODAL ===== --}}
<div class="modal fade" id="printReportModal" tabindex="-1" aria-labelledby="printReportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route(get_area_name().'.doctor-transfers.print') }}" method="GET" target="_blank">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="printReportLabel"><i class="fa fa-file-alt"></i> إعدادات التقرير</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- STATUS --}}
                    <div class="mb-3">
                        <label class="form-label">الحالة</label>
                        <select class="form-select selectize" name="status[]"  multiple>
                            <option value="pending">قيد الانتظار</option>
                            <option value="approved">موافق عليه</option>
                            <option value="rejected">مرفوض</option>
                        </select>
                        <small class="text-muted">يمكن اختيار أكثر من حالة</small>
                    </div>

                    {{-- DATE RANGE --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">من تاريخ</label>
                            <input type="date" class="form-control" name="from_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">إلى تاريخ</label>
                            <input type="date" class="form-control" name="to_date">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        إغلاق
                    </button>
                    <button type="submit" class="btn btn-primary">
                        إنشاء التقرير
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.getElementById('openReport').addEventListener('click', () => {
    fetch("{{ route(get_area_name() . '.doctor-transfers.report') }}")
        .then(res => res.json())
        .then(({ stats, monthly }) => {
            document.getElementById('totalTransfers').textContent    = stats.total;
            document.getElementById('pendingTransfers').textContent  = stats.pending;
            document.getElementById('approvedTransfers').textContent = stats.approved;
            document.getElementById('rejectedTransfers').textContent = stats.rejected;

            const labels = Object.keys(monthly);
            const data   = Object.values(monthly);

            if (window.monthlyChart) {
                window.monthlyChart.destroy();
            }

            const ctx = document.getElementById('monthlyChart').getContext('2d');
            window.monthlyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'عدد التحويلات',
                        data: data,
                        tension: 0.3,
                        fill: false,
                        borderWidth: 2
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true, precision:0 },
                    },
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
});
</script>

@endpush
@endsection
