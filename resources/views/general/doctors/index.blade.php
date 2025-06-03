@extends('layouts.' . get_area_name())
@section('title', 'قائمة الاطباء')

@section('content')
<div class="row">
  


    <!-- Add this modal to your blade template after the delete modal -->

{{-- button of modal --}}
<div class="col-md-12 mb-3">
    <button type="button" class="btn btn-primary text-light" data-bs-toggle="modal" data-bs-target="#reportModal">
        <i class="fa fa-file-alt"></i> <span class="text-light">إنشاء تقرير الأطباء</span>
    </button>
</div>

<!-- Report Generation Modal -->


    @if (get_area_name() == "finance")
    <div class="col-md-12">
        <a href="{{ route(get_area_name().'.invoices.create', ['type' => request('type')] ) }}" class="btn btn-success mb-2"><i class="fa fa-plus"></i>  اضافة مستحقات يدوية  </a>
    </div>
    @endif



    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h4 class="card-title">قائمة الاطباء</h4>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name().'.doctors.index') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="name"> الرقم النقابي الاول </label>
                            <input type="text" class="form-control" id="name" name="doctor_number" placeholder="الرقم النقابي الاول" value="{{ request()->input('doctor_number') }}">
                        </div>
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
                            <label for="email"> صفة الطبيب</label>
                            <select class="form-control" name="doctor_rank_id" id="doctor_rank_id">
                                <option value="">اختر صفة الطبيب</option>
                                @foreach ($doctorRanks as $doctorRank)
                                    <option value="{{ $doctorRank->id }}" {{ request()->input('doctor_rank_id') == $doctorRank->id ? 'selected' : '' }}>
                                        {{ $doctorRank->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="registered_at">تاريخ الانتساب للنقابة</label>
                            <input type="date" lang="ar" dir="rtl" class="form-control date-picker" id="registered_at" name="registered_at" value="{{ request()->input('registered_at') }}">
                        </div>
                        {{-- <div class="col-md-3">
                            <label for="">التخصص</label>
                            <select class="form-control select2" name="specialization" id="specialization">
                                <option value="">اختر التخصص</option>
                                @foreach ($specialties as $specialization)
                                    <option value="{{ $specialization->id }}" {{ request()->input('specialization') == $specialization->id ? 'selected' : '' }}>
                                        {{ $specialization->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}


                        <div class="col-md-3">

                            @if(count(array_filter(request()->except('page')))) 
                            <div class="mb-3">
                                <a href="{{ route(get_area_name().'.doctors.print_list', request()->all()) }}" class="btn btn-secondary">
                                    <i class="fa fa-print"></i> طباعة النتائج
                                </a>
                            </div>
                        @endif
                        </div>
                        
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">بحث</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="bg-light">#</th>
                                <th class="bg-light">كود الطبيب</th>
                                
                                @if (request('type') != "visitor")
                                 <th class="bg-light"> الرقم النقابي الاول </th>
                                @endif

                                <th class="bg-light">الاسم</th>
                                <th class="bg-light">رقم الهاتف</th>
                                <th class="bg-light"> الصفة / التخصص </th>

                        
                                @if (request('type') == "visitor")
                                    <th class="bg-light"> تاريخ بدء الزيارة </th>
                                    <th class="bg-light"> تاريخ انتهاء الزيارة </th>
                                @endif

                                @if (request('type') == "libyan")
                                <th class="bg-light">الرقم الوطني</th>
                                @endif
                                <th class="bg-light text-dark" >نوع الطبيب</th>
                                

                                <th class="bg-light">حالة الاشتراك</th>

                                @if (request('init_approve') )
                                    <th class="bg-light"> تاريخ الزيارة</th>
                                @endif


                                @if (get_area_name() == "finance")
                                <th class="bg-danger text-light">القيمة المستحقة من الطبيب</th>
                                <th class="bg-success text-light">القيمة المدفوعة الكلية</th>
                                <th class="bg-warning text-light">القيمة المعفى عنه</th>
                                @endif
                              

                                <th class="bg-light">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doctors as $doctor)
                            <tr
                                @if ($doctor->membership_status->value == "banned")
                                class="bg-gradient-danger text-light"
                                @endif
                            >
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $doctor->code }}</td>
                                

                                @if (request("type") != "visitor")
                                <td>{{ $doctor->doctor_number }}</td>
                                @endif

                                <td>{{ $doctor->name }}</td>
                                <td>{{ $doctor->phone }}</td>
                                <td>
                                    {{ $doctor->doctor_rank->name?? '-' }}

                                   {{ $doctor->specialization  }}
                                </td>



                                @if (request('type') == "visitor")
                                <td>{{ $doctor->visit_from }}</td>
                                <td>{{ $doctor->visit_to }}</td>

                                @endif

                                @if (request('type') == "libyan")
                                <td>{{ $doctor->national_number }}</td>
                                @endif
                                <td class="{{$doctor->type->badgeClass()}}" >
                                    {{ $doctor->type->label() }}
                                </td>
                     

                                <td>
                                    <span class="badge {{$doctor->membership_status->badgeClass()}} ">
                                                {{ $doctor->membership_status->label() }}
                                            </span>
                                </td>



                                @if (request('init_approve'))
                                    <td>
                                        {{$doctor->visiting_date}}
                                    </td>
                                @endif
                                    @if (get_area_name() == "finance")
                                    <td>
                                        @php
                                        $total = $doctor->invoices->where('status', \App\Enums\InvoiceStatus::unpaid)->sum('amount');
                                        $paid = $doctor->invoices->where('status', \App\Enums\InvoiceStatus::paid)->sum('amount');
                                        $relief = $doctor->invoices->where('status', \App\Enums\InvoiceStatus::relief)->sum('amount');
                                        @endphp

                                        {{ number_format($total, 2) }} د.ل
                                    </td>
                                    <td>
                                        {{ number_format($paid, 2) }} د.ل
                                    </td>
                                    <td>
                                        {{ number_format($doctor->full_break_amount, 2) }} د.ل
                                    </td>
                               @endif
                              


                                <td>


                                    @if (get_area_name() != "finance")
                                    <a href="{{ route(get_area_name() . '.doctors.show', $doctor) }}" class="btn btn-primary btn-sm text-light">عرض <i class="fa fa-eye"></i></a>
                                    @endif




                                    @if (get_area_name() == "finance")
                                        <a href="{{route('finance.total_invoices.create', $doctor)}}" class="btn btn-primary text-light">دفع الفواتير</a>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد أنك تريد حذف هذا الطبيب؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">حذف</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="GET" id="reportForm" action="{{route(get_area_name().'.doctors.generate_report')}}"   target="_blank">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-light pb-2" id="reportModalLabel">
                        <i class="fa fa-file-alt"></i> إنشاء تقرير الأطباء
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- Report Type Selection -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">نوع التقرير</h6>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="report_type" id="summary_report" value="summary" checked>
                                <label class="btn btn-outline-primary" for="summary_report">
                                    <i class="fa fa-chart-bar"></i> تقرير إجمالي
                                </label>
                                
                                <input type="radio" class="btn-check" name="report_type" id="detailed_report" value="detailed">
                                <label class="btn btn-outline-primary" for="detailed_report">
                                    <i class="fa fa-list-alt"></i> تقرير تفصيلي
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Options -->
                    <div class="filter-options">
                        <h6 class="text-primary mb-3">معايير التقرير</h6>
                        
                        <!-- Doctor Rank Filter -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="report_doctor_rank_id" class="form-label">
                                    <i class="fa fa-user-md"></i> صفة الطبيب
                                </label>
                                <select class="form-control select2" name="doctor_rank_id[]" id="report_doctor_rank_id" multiple>
                                    <option value="">جميع الصفات</option>
                                    @foreach ($doctor_ranks as $doctorRank)
                                        <option value="{{ $doctorRank->id }}">{{ $doctorRank->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Institution Filter -->
                            <div class="col-md-6">
                                <label for="report_institution_id" class="form-label">
                                    <i class="fa fa-hospital"></i> المؤسسة الصحية
                                </label>
                                <select class="form-control select2" name="institution_id[]" id="report_institution_id" multiple>
                                    <option value="">جميع المؤسسات</option>
                                    @foreach ($institutions ?? [] as $institution)
                                        <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Specialty Filter -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="report_specialty_id" class="form-label">
                                    <i class="fa fa-stethoscope"></i> التخصص
                                </label>
                                <select class="form-control select2" name="specialty_id[]" id="report_specialty_id" multiple>
                                    <option value="">جميع التخصصات</option>
                                    @foreach ($specialties as $specialty)
                                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Doctor Type Filter -->
                            <div class="col-md-6">
                                <label for="report_doctor_type" class="form-label">
                                    <i class="fa fa-flag"></i> نوع الطبيب
                                </label>
                                <select class="form-control select2" name="doctor_type[]" id="report_doctor_type" multiple>
                                    <option value="">جميع الأنواع</option>
                                    <option value="libyan">ليبي</option>
                                    <option value="palestinian">فلسطيني</option>
                                    <option value="foreign">أجنبي</option>
                                    <option value="visitor">زائر</option>
                                </select>
                            </div>
                        </div>

                        <!-- Registration Date Range -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="fa fa-calendar-alt"></i> تاريخ الانتساب للنقابة
                                </label>
                            </div>
                            <div class="col-md-6">
                                <input type="date" class="form-control" name="registered_from" id="registered_from" placeholder="من تاريخ">
                            </div>
                            <div class="col-md-6">
                                <input type="date" class="form-control" name="registered_to" id="registered_to" placeholder="إلى تاريخ">
                            </div>
                        </div>

                        <!-- Membership Expiry Date Range -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="fa fa-clock"></i> تاريخ انتهاء العضوية
                                </label>
                            </div>
                            <div class="col-md-6">
                                <input type="date" class="form-control" name="membership_expired_from" id="membership_expired_from" placeholder="من تاريخ">
                            </div>
                            <div class="col-md-6">
                                <input type="date" class="form-control" name="membership_expired_to" id="membership_expired_to" placeholder="إلى تاريخ">
                            </div>
                        </div>

                        <!-- Membership Status Filter -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="report_membership_status" class="form-label">
                                    <i class="fa fa-user-check"></i> حالة العضوية
                                </label>
                                <select class="form-control select2" name="membership_status[]" id="report_membership_status" multiple>
                                    <option value="">جميع الحالات</option>
                                    <option value="active">نشط</option>
                                    <option value="inactive">غير نشط</option>
                                    <option value="suspended">معلق</option>
                                    <option value="banned">محظور</option>
                                    <option value="expired">منتهي</option>
                                </select>
                            </div>

                            <!-- Gender Filter -->
                            <div class="col-md-6">
                                <label for="report_gender" class="form-label">
                                    <i class="fa fa-venus-mars"></i> الجنس
                                </label>
                                <select class="form-control" name="gender" id="report_gender">
                                    <option value="">الكل</option>
                                    <option value="male">ذكر</option>
                                    <option value="female">أنثى</option>
                                </select>
                            </div>
                        </div>

                        <!-- Additional Options for Detailed Report -->
                        <div id="detailedOptions" style="display: none;">
                            <h6 class="text-primary mb-3">خيارات التقرير التفصيلي</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="include_photo" id="include_photo">
                                        <label class="form-check-label" for="include_photo">
                                            تضمين صور الأطباء
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="include_contact" id="include_contact" checked>
                                        <label class="form-check-label" for="include_contact">
                                            تضمين معلومات الاتصال
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="include_financial" id="include_financial">
                                        <label class="form-check-label" for="include_financial">
                                            تضمين البيانات المالية
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="include_membership_history" id="include_membership_history">
                                        <label class="form-check-label" for="include_membership_history">
                                            تضمين تاريخ العضوية
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Report Format -->
                        <div class="row mb-3 mt-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">تنسيق التقرير</h6>
                                <div class="btn-group w-100" role="group">
                                    {{-- <input type="radio" class="btn-check" name="format" id="format_pdf" value="pdf" >
                                    <label class="btn btn-outline-secondary" for="format_pdf">
                                        <i class="fa fa-file-pdf"></i> PDF
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="format" id="format_excel" value="excel">
                                    <label class="btn btn-outline-secondary" for="format_excel">
                                        <i class="fa fa-file-excel"></i> Excel
                                    </label>
                                     --}}
                                    {{-- <input type="radio" class="btn-check" name="format" id="format_print" value="print" checked>
                                    <label class="btn btn-outline-secondary" for="format_print">
                                        <i class="fa fa-print"></i> طباعة مباشرة
                                    </label> --}}

                                    <input type="hidden" name="format" value="print">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> إلغاء
                    </button>
                    <button type="button" class="btn btn-info" onclick="previewReport()">
                        <i class="fa fa-eye"></i> معاينة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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