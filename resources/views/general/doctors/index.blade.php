
@extends('layouts.' . get_area_name())
@section('title', 'قائمة الاطباء')

@section('content')
<div class="row">

    {{-- تقارير مالية للمدير المالي --}}
    @if (get_area_name() != "finance")
    {{-- زر التقارير العادية للمناطق الأخرى --}}
    <div class="col-md-12 mb-3">
        <button type="button" class="btn btn-primary text-light" data-bs-toggle="modal" data-bs-target="#reportModal">
            <i class="fa fa-file-alt"></i> <span class="text-light">إنشاء تقرير الأطباء</span>
        </button>
    </div>
    @endif

    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h4 class="card-title">
                    قائمة الاطباء 
                    @if(get_area_name() == "finance")
                        - المالية
                    @endif
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name().'.doctors.index') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="name">اسم الطبيب</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="اسم الطبيب" value="{{ request()->input('name') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="phone">رقم الهاتف</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="رقم الهاتف" maxlength="10" value="{{ request()->input('phone') }}">
                        </div>

                        <input type="hidden" name="type" value="{{request('type')}}">

                        <div class="col-md-3">
                            <label for="email">صفة الطبيب</label>
                            <select class="form-control" name="doctor_rank_id" id="doctor_rank_id">
                                <option value="">اختر صفة الطبيب</option>
                                @foreach (\App\Models\DoctorRank::where('doctor_type', 'libyan' )->get() as $doctorRank)
                                    <option value="{{ $doctorRank->id }}" {{ request()->input('doctor_rank_id') == $doctorRank->id ? 'selected' : '' }}>
                                        {{ $doctorRank->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- فلاتر مالية إضافية --}}
                        @if(get_area_name() == "finance")
                        <div class="col-md-3">
                            <label for="payment_status">حالة الدفع</label>
                            <select class="form-control" name="payment_status" id="payment_status">
                                <option value="">جميع الحالات</option>
                                <option value="has_unpaid" {{ request()->input('payment_status') == 'has_unpaid' ? 'selected' : '' }}>له مستحقات</option>
                                <option value="no_dues" {{ request()->input('payment_status') == 'no_dues' ? 'selected' : '' }}>لا توجد مستحقات</option>
                            </select>
                        </div>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="registered_at">تاريخ الانتساب للنقابة</label>
                            <input type="date" lang="ar" dir="rtl" class="form-control date-picker" id="registered_at" name="registered_at" value="{{ request()->input('registered_at') }}">
                        </div>

                        @if(get_area_name() == "finance")
                        {{-- فلاتر مالية إضافية --}}
                        <div class="col-md-3">
                            <label for="min_amount">الحد الأدنى للمستحقات</label>
                            <input type="number" step="0.01" class="form-control" id="min_amount" name="min_amount" placeholder="0.00" value="{{ request()->input('min_amount') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="max_amount">الحد الأقصى للمستحقات</label>
                            <input type="number" step="0.01" class="form-control" id="max_amount" name="max_amount" placeholder="9999.99" value="{{ request()->input('max_amount') }}">
                        </div>
                        @endif

                        @if(get_area_name() != "finance")
                        <div class="col-md-3">
                            @if(count(array_filter(request()->except('page')))) 
                            <div class="mb-3">
                                <a href="{{ route(get_area_name().'.doctors.print_list', request()->all()) }}" class="btn btn-secondary">
                                    <i class="fa fa-print"></i> طباعة النتائج
                                </a>
                            </div>
                            @endif
                        </div>
                        @endif
                        
                        <div class="col-md-3 d-block">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary">بحث</button>
                            @if(count(array_filter(request()->except('page'))))
                            <a href="{{route(get_area_name().'.doctors.print_list', request()->all())}}" class="btn btn-secondary" target="_blank">
                                <i class="fa fa-print"></i> طباعة التقرير
                            </a>
                            @endif
                        </div>
                       
                    </div>

                </form>

                {{-- إحصائيات سريعة للمدير المالي --}}
                @if(get_area_name() == "finance")
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <h5 class="mb-1">{{ number_format(\App\Models\Doctor::where('branch_id', auth()->user()->branch_id)->get()->sum(function($doctor) { return $doctor->invoices->where('status', \App\Enums\InvoiceStatus::unpaid)->sum('amount'); }), 2) }} د.ل</h5>
                                    <small>إجمالي المستحقات</small>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="mb-1">{{ number_format(\App\Models\Doctor::where('branch_id', auth()->user()->branch_id)->get()->sum(function($doctor) { return $doctor->invoices->where('status', \App\Enums\InvoiceStatus::paid)->sum('amount'); }), 2) }} د.ل</h5>
                                    <small>إجمالي المدفوعات</small>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="mb-1">{{ \App\Models\Doctor::where('branch_id', auth()->user()->branch_id)->get()->filter(function($doctor) { return $doctor->invoices->where('status', \App\Enums\InvoiceStatus::unpaid)->sum('amount') > 0; })->count() }}</h5>
                                    <small>أطباء لديهم مستحقات</small>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="mb-1">{{ $doctors->count() }}</h5>
                                    <small>إجمالي الأطباء</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="bg-light">#</th>
                                <th class="bg-light">كود الطبيب</th>
                                <th class="bg-light">الاسم</th>
                                <th class="bg-light">رقم الهاتف</th>
                                <th class="bg-light">الصفة / التخصص</th>

                                @if (request('type') == "visitor")
                                    <th class="bg-light">تاريخ بدء الزيارة</th>
                                    <th class="bg-light">تاريخ انتهاء الزيارة</th>
                                @endif

                                @if (request('type') == "libyan")
                                <th class="bg-light">الرقم الوطني</th>
                                @endif
                                
                                <th class="bg-light text-dark">نوع الطبيب</th>
                                <th class="bg-light">حالة الاشتراك</th>

                                @if (request('init_approve'))
                                    <th class="bg-light">تاريخ الزيارة</th>
                                @endif

                                @if (get_area_name() == "finance")
                                <th class="bg-danger text-light">المستحقات</th>
                                <th class="bg-success text-light">المدفوعات</th>
                                <th class="bg-warning text-light">عدد الفواتير</th>
                                <th class="bg-info text-light">آخر دفعة</th>
                                @endif

                                <th class="bg-light">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doctors as $doctor)
                            <tr @if ($doctor->membership_status->value == "banned") class="bg-gradient-danger text-light" @endif>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $doctor->code }}</td>
                                <td>{{ $doctor->name }}</td>
                                <td>{{ $doctor->phone }}</td>
                                <td>
                                    {{ $doctor->doctor_rank->name ?? '-' }}
                                    {{ $doctor->specialization }}
                                </td>

                                @if (request('type') == "visitor")
                                <td>{{ $doctor->visit_from }}</td>
                                <td>{{ $doctor->visit_to }}</td>
                                @endif

                                @if (request('type') == "libyan")
                                <td>{{ $doctor->national_number }}</td>
                                @endif
                                
                                <td class="{{$doctor->type->badgeClass()}}">
                                    {{ $doctor->type->label() }}
                                </td>

                                <td>
                                    <span class="badge {{$doctor->membership_status->badgeClass()}}">
                                        {{ $doctor->membership_status->label() }}
                                    </span>
                                </td>

                                @if (request('init_approve'))
                                    <td>{{$doctor->visiting_date}}</td>
                                @endif

                                @if (get_area_name() == "finance")
                                @php
                                    $unpaidTotal = $doctor->invoices->where('status', \App\Enums\InvoiceStatus::unpaid)->sum('amount');
                                    $paidTotal = $doctor->invoices->where('status', \App\Enums\InvoiceStatus::paid)->sum('amount');
                                    $reliefTotal = $doctor->invoices->where('status', \App\Enums\InvoiceStatus::relief)->sum('amount');
                                    $totalInvoices = $doctor->invoices->count();
                                    $lastPayment = $doctor->invoices->where('status', \App\Enums\InvoiceStatus::paid)->sortByDesc('received_at')->first();
                                @endphp
                                
                                <td class="{{ $unpaidTotal > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                    {{ number_format($unpaidTotal, 2) }} د.ل
                                    @if($reliefTotal > 0)
                                        <br><small class="text-muted">(إعفاء: {{ number_format($reliefTotal, 2) }})</small>
                                    @endif
                                </td>
                                
                                <td class="text-success fw-bold">
                                    {{ number_format($paidTotal, 2) }} د.ل
                                </td>
                                
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $totalInvoices }}</span>
                                </td>
                                
                                <td class="text-center">
                                    @if($lastPayment)
                                        {{ $lastPayment->received_at ? $lastPayment->received_at : '-' }}
                                        <br><small class="text-muted">{{ number_format($lastPayment->amount, 2) }} د.ل</small>
                                    @else
                                        <span class="text-muted">لا توجد دفعات</span>
                                    @endif
                                </td>
                                @endif

                                <td>
                                    @if (get_area_name() == "finance")
                                        {{-- أزرار خاصة بالمدير المالي --}}
                                      
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{route(get_area_name().'.invoices.index',['doctor_id' => $doctor->id])}}" class="btn btn-primary" href="">
                                                <i class="fa fa-file-invoice"></i> عرض الملف المالي
                                            </a>
    
                                            <a class="btn btn-primary" href="{{route(get_area_name().'.invoices.print-list',['doctor_id' => $doctor->id ])}}" target="_blank">
                                                <i class="fa fa-print"></i> طباعة الملف المالي 
                                            </a>
                                        </div>
                                    @else
                                        <a href="{{ route(get_area_name() . '.doctors.show', $doctor) }}" class="btn btn-primary btn-sm text-light">
                                            عرض <i class="fa fa-eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $doctors->appends($_GET)->links() }}
            </div>
        </div>
    </div>
</div>

{{-- مودالات خاصة بالمدير المالي --}}
@if(get_area_name() == "finance")

{{-- مودال إضافة فاتورة يدوية --}}
<div class="modal fade" id="addManualInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('finance.invoices.store') }}" method="POST">
                @csrf
                <input type="hidden" name="doctor_id" id="modalDoctorId">
                
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-plus me-2"></i>
                        إضافة فاتورة لـ <span id="modalDoctorName"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quickDescription" class="form-label">وصف الفاتورة</label>
                        <textarea name="description" id="quickDescription" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="quickPreviousSwitch">
                        <label class="form-check-label" for="quickPreviousSwitch">
                            حساب اشتراكات سابقة
                        </label>
                    </div>

                    <div id="quickRankTableContainer" style="display:none" class="mb-3">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>الصفة</th>
                                    <th>من سنة</th>
                                    <th>إلى سنة</th>
                                    <th>إجراء</th>
                                </tr>
                            </thead>
                            <tbody id="quickRankTableBody"></tbody>
                        </table>
                        <button type="button" id="quickAddRowBtn" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-plus"></i> إضافة بند
                        </button>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="quickAmount" class="form-label">قيمة الفاتورة (د.ل)</label>
                            <input type="number" step="0.01" name="amount" id="quickAmount" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">معاينة الإجمالي</label>
                            <div class="form-control bg-light" id="quickTotalPreview">0.00 د.ل</div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">إنشاء الفاتورة</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- مودال التقارير المالية --}}
<div class="modal fade" id="financialReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fa fa-chart-line"></i> التقارير المالية
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fa fa-file-invoice-dollar fa-3x text-warning mb-3"></i>
                                <h6>تقرير المستحقات</h6>
                                <p class="text-muted">جميع المستحقات المالية للأطباء</p>
                         
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fa fa-money-bill-wave fa-3x text-success mb-3"></i>
                                <h6>تقرير المدفوعات</h6>
                                <p class="text-muted">جميع المدفوعات المستلمة</p>
                                <a href=" " class="btn btn-success" target="_blank">
                                    <i class="fa fa-download"></i> تحميل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fa fa-chart-pie fa-3x text-info mb-3"></i>
                                <h6>تقرير شامل</h6>
                                <p class="text-muted">تقرير مالي شامل بجميع التفاصيل</p>
                                <a href=" " class="btn btn-info" target="_blank">
                                    <i class="fa fa-download"></i> تحميل
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fa fa-calendar-alt fa-3x text-primary mb-3"></i>
                                <h6>تقرير شهري</h6>
                                <p class="text-muted">تقرير مالي لشهر محدد</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#monthlyReportModal">
                                    <i class="fa fa-cog"></i> إعداد
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Template للصفوف الجديدة في الفاتورة السريعة --}}
<div id="quickRowTemplate" style="display: none">
    <table>
        <tr>
            <td>
                <select name="ranks[]" class="form-control form-control-sm">
                    <option value="">-- اختر الصفة --</option>
                    @foreach(App\Models\DoctorRank::all() as $rank)
                        <option value="{{ $rank->id }}" data-price="{{ optional(App\Models\Pricing::find($rank->id))->amount ?? 0 }}">{{ $rank->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="from_years[]" min="1900" max="2100" class="form-control form-control-sm">
            </td>
            <td>
                <input type="number" name="to_years[]" min="1900" max="2100" class="form-control form-control-sm">
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger quick-remove-row">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    </table>
</div>

@endif


@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for report modal
    if (typeof $.fn.select2 !== 'undefined') {
        $('#reportModal .select2').select2({
            placeholder: 'اختر...',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#reportModal'),
            language: {
                noResults: function() {
                    return "لا توجد نتائج";
                },
                searching: function() {
                    return "جاري البحث...";
                }
            }
        });
    }

    // Toggle detailed options based on report type
    const reportTypeInputs = document.querySelectorAll('input[name="report_type"]');
    const detailedOptions = document.getElementById('detailedOptions');
    
    reportTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value === 'detailed') {
                detailedOptions.style.display = 'block';
            } else {
                detailedOptions.style.display = 'none';
            }
        });
    });

    // Date range validation
    const registeredFrom = document.getElementById('registered_from');
    const registeredTo = document.getElementById('registered_to');
    const membershipFrom = document.getElementById('membership_expired_from');
    const membershipTo = document.getElementById('membership_expired_to');

    if (registeredFrom && registeredTo) {
        registeredFrom.addEventListener('change', function() {
            registeredTo.min = this.value;
        });

        registeredTo.addEventListener('change', function() {
            registeredFrom.max = this.value;
        });
    }

    if (membershipFrom && membershipTo) {
        membershipFrom.addEventListener('change', function() {
            membershipTo.min = this.value;
        });

        membershipTo.addEventListener('change', function() {
            membershipFrom.max = this.value;
        });
    }

    // Reset form when modal is closed
    $('#reportModal').on('hidden.bs.modal', function () {
        document.getElementById('reportForm').reset();
        $('.select2').val(null).trigger('change');
        document.getElementById('detailedOptions').style.display = 'none';
    });

    // Delete modal functionality (keep existing)
    var deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var doctorId = button.getAttribute('data-doctor-id');
            var action = "{{ url(get_area_name() . '/doctors') }}/" + doctorId;
            var deleteForm = document.getElementById('deleteForm');
            deleteForm.setAttribute('action', action);
        });
    }
});

// Preview report function
function previewReport() {
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);
    formData.append('preview', '1');
    
    // Change action temporarily for preview
    const originalAction = form.action;
    form.action = form.action.replace('generate_report', 'preview_report');
    
    // Submit form for preview
    form.submit();
    
    // Restore original action
    setTimeout(() => {
        form.action = originalAction;
    }, 100);
}
</script>

<style>
/* Report Modal Styles */
#reportModal .modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

#reportModal .form-label {
    font-weight: 600;
    color: #495057;
}

#reportModal .btn-group {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#reportModal .filter-options {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-top: 10px;
}

#reportModal h6 {
    border-bottom: 2px solid #007bff;
    padding-bottom: 8px;
    margin-bottom: 15px;
}

#detailedOptions {
    background-color: #e9ecef;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
}

/* Select2 Styles for Report Modal */
.select2-container {
    z-index: 9999 !important;
}

.select2-dropdown {
    z-index: 9999 !important;
}

.select2-container--open {
    z-index: 9999 !important;
}

.select2-container--default .select2-selection--multiple {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    min-height: 38px;
}

.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.select2-container--default .select2-selection--single {
    height: 38px !important;
    line-height: 36px !important;
}

/* Modal z-index fix */
.modal {
    z-index: 1050;
}

.modal-backdrop {
    z-index: 1040;
}
</style>
@endsection