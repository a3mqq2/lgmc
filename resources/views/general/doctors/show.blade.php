@extends('layouts.' . get_area_name())
@section('title', 'تفاصيل الطبيب')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="main-content-label">الإجراءات</h4>
        
                    <!-- Print ID Button -->

                    <!-- Doctor Request Modal Trigger Button -->
                    <button type="button" class="btn btn-primary text-light" data-bs-toggle="modal" data-bs-target="#doctorRequestModal">
                        <i class="fa fa-plus"></i> إضافة طلب جديد
                    </button>

                    <!-- Doctor Request Modal -->
                    <div class="modal fade" id="doctorRequestModal" tabindex="-1" aria-labelledby="doctorRequestModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="POST" action="{{ route(get_area_name().'.doctor-requests.store') }}">
                                    @csrf

                                    <!-- Modal Header -->
                                    <div class="modal-header  text-light">
                                        <h5 class="modal-title" id="doctorRequestModalLabel">
                                            <i class="fa fa-plus"></i> إضافة طلب جديد
                                        </h5>
                                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="modal-body">
                                        <input type="hidden" name="doctor_type" value="{{ $doctor->type }}">

                                        <div class="row">
                                            <!-- Doctor Selection -->
                                            <div class="col-md-4 mb-3">
                                                <label for="doctor_id" class="form-label">
                                                    <i class="fa fa-user-md"></i> اسم الطبيب <span class="text-danger">*</span>
                                                </label>
                                                <select name="doctor_id" id="doctor_id" class="form-control select2 @error('doctor_id') is-invalid @enderror" required>
                                                    <option value="{{$doctor->id}}">{{$doctor->name}}</option>
                                                </select>
                                                @error('doctor_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Pricing Selection -->
                                            <div class="col-md-4 mb-3">
                                                <label for="pricing_id" class="form-label">
                                                    <i class="fa fa-list"></i> اختيار نوع الطلب <span class="text-danger">*</span>
                                                </label>
                                                <select name="pricing_id" id="pricing_id" class="form-control @error('pricing_id') is-invalid @enderror" required>
                                                    <option value="">اختر نوع الطلب</option>
                                                    @foreach(App\Models\Pricing::where('doctor_type', $doctor->type)->where('type','service')->get() as $pricing)
                                                        <option value="{{ $pricing->id }}" {{ old('pricing_id') == $pricing->id ? 'selected' : '' }}>
                                                            {{ $pricing->name }} - {{ number_format($pricing->amount, 2) }} د.ل
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('pricing_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Request Date -->
                                            <div class="col-md-4 mb-3">
                                                <label for="date" class="form-label">
                                                    <i class="fa fa-calendar-alt"></i> تاريخ الطلب <span class="text-danger">*</span>
                                                </label>
                                                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" class="form-control @error('date') is-invalid @enderror" required>
                                                @error('date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Notes -->
                                        <div class="mb-3">
                                            <label for="notes" class="form-label">
                                                <i class="fa fa-info-circle"></i> الملاحظات
                                            </label>
                                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="أضف ملاحظات إضافية">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="modal-footer d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fa fa-times"></i> إلغاء
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-check"></i> إضافة الطلب
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



                    <!-- Doctor Transfer Modal Trigger Button -->
                    <button type="button" class="btn btn-warning text-light" data-bs-toggle="modal" data-bs-target="#doctorTransferModal">
                        <i class="fa fa-exchange-alt"></i> طلب نقل طبيب
                    </button>

                    <!-- Doctor Transfer Modal -->
                    <div class="modal fade" id="doctorTransferModal" tabindex="-1" aria-labelledby="doctorTransferModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="{{ route(get_area_name() . '.doctor-transfers.store') }}" method="POST">
                                    @csrf

                                    <!-- Modal Header -->
                                    <div class="modal-header  text-light">
                                        <h5 class="modal-title" id="doctorTransferModalLabel">
                                            <i class="fa fa-exchange-alt"></i> إضافة طلب نقل طبيب
                                        </h5>
                                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <!-- Select Doctor -->
                                            <div class="col-md-6">
                                                <label for="doctor_id" class="form-label">
                                                    <i class="fa fa-user-md"></i> الطبيب
                                                </label>
                                                <select name="doctor_id" id="doctor_id" class="form-control select2 @error('doctor_id') is-invalid @enderror" required>
                                                    <option value="{{$doctor->id}}">{{$doctor->name}}</option>                                                    
                                                </select>
                                                @error('doctor_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Select Destination Branch -->
                                            <div class="col-md-6">
                                                <label for="to_branch_id" class="form-label">
                                                    <i class="fa fa-map-marker-alt"></i> إلى الفرع الجديد
                                                </label>
                                                <select name="to_branch_id" id="to_branch_id" class="form-control select2 @error('to_branch_id') is-invalid @enderror" required>
                                                    <option value="">-- اختر الفرع --</option>
                                                    @foreach(App\Models\Branch::all() as $branch)
                                                        <option value="{{ $branch->id }}" {{ old('to_branch_id') == $branch->id ? 'selected' : '' }}>
                                                            {{ $branch->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('to_branch_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Notes -->
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label for="note" class="form-label">
                                                    <i class="fa fa-info-circle"></i> ملاحظات
                                                </label>
                                                <textarea name="note" id="note" rows="4" class="form-control @error('note') is-invalid @enderror" placeholder="أدخل ملاحظات حول النقل (اختياري)">{{ old('note') }}</textarea>
                                                @error('note')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="modal-footer d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fa fa-times"></i> إلغاء
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-check"></i> حفظ الطلب
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



                    {{-- <a href="{{ route(get_area_name().'.doctors.print-id', $doctor) }}" class="btn btn-primary text-light">
                        <i class="fa fa-id-card"></i> طباعة بطاقة الهوية
                    </a>
         --}}
                    <!-- Toggle Ban/Unban Button (Modal Trigger) -->
                    <button type="button" class="btn {{ $doctor->membership_status->value === 'banned' ? 'btn-success' : 'btn-danger' }}" data-bs-toggle="modal" data-bs-target="#toggleBanDoctorModal">
                        <i class="fa {{ $doctor->membership_status->value === 'banned' ? 'fa-lock-open' : 'fa-lock' }}"></i>
                        {{ $doctor->membership_status->value === 'banned' ? 'رفع الحظر' : 'حظر الطبيب' }}
                    </button>
        
                    <!-- Toggle Ban/Unban Modal -->
                    <div class="modal fade" id="toggleBanDoctorModal" tabindex="-1" aria-labelledby="toggleBanDoctorModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route(get_area_name() . '.doctors.toggle-ban', $doctor->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="toggleBanDoctorModalLabel">
                                            {{ $doctor->membership_status->value === 'banned' ? 'تأكيد رفع الحظر' : 'تأكيد حظر الطبيب' }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                    </div>
                                    <div class="modal-body">
                                        هل أنت متأكد أنك تريد
                                        <strong>{{ $doctor->membership_status->value === 'banned' ? 'رفع الحظر عن' : 'حظر' }}</strong>
                                        الطبيب: <strong>{{ $doctor->name }}</strong>؟
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn {{ $doctor->membership_status->value === 'banned' ? 'btn-success' : 'btn-danger' }}">
                                            تأكيد
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
        
                    <!-- Approve Membership (Initial Approval) -->
                    @if (get_area_name() == "user" && $doctor->code == null && $doctor->membership_status === \App\Enums\MembershipStatus::Pending)
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveInitial{{ $doctor->id }}">
                            القبول المبدئي للعضوية
                        </button>
        
                        <div class="modal fade" id="approveInitial{{ $doctor->id }}" tabindex="-1" aria-labelledby="approveInitialLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route(get_area_name().'.doctors.approve', $doctor) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="approveInitialLabel">تأكيد العضوية المبدئية</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="meet_date">حدد موعد الزيارة</label>
                                            <input type="date" name="meet_date" value="{{ \Carbon\Carbon::now()->addDay()->format('Y-m-d') }}" class="form-control" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">تأكيد</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
        
                    <!-- Approve Membership (Final Approval) -->
                    @if (get_area_name() == "user" && $doctor->code == null && $doctor->membership_status === \App\Enums\MembershipStatus::InitApprove)
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveFinal{{ $doctor->id }}">
                            القبول النهائي للعضوية
                        </button>
        
                        <div class="modal fade" id="approveFinal{{ $doctor->id }}" tabindex="-1" aria-labelledby="approveFinalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route(get_area_name().'.doctors.approve', ['doctor' => $doctor, 'init' => 1]) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="approveFinalLabel">تأكيد العضوية النهائية</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                        </div>
                                        <div class="modal-body">
                                            هل أنت متأكد من الموافقة النهائية على عضوية الطبيب: <strong>{{ $doctor->name }}</strong>؟
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">تأكيد</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
        
                    <!-- Reject Membership Modal -->
                    @if (get_area_name() == "user" && in_array($doctor->membership_status, [\App\Enums\MembershipStatus::Pending, \App\Enums\MembershipStatus::InitApprove]))
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectMembership{{ $doctor->id }}">
                            رفض العضوية
                        </button>
        
                        <div class="modal fade" id="rejectMembership{{ $doctor->id }}" tabindex="-1" aria-labelledby="rejectMembershipLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route(get_area_name().'.doctors.reject', ['doctor' => $doctor]) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectMembershipLabel">تأكيد رفض العضوية</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="notes">سبب الرفض</label>
                                            <textarea name="notes" class="form-control" rows="4" required placeholder="أدخل سبب الرفض هنا..."></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">تأكيد الرفض</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
        
                    @if (get_area_name() != "finance")
                        <a href="{{ route(get_area_name() . '.doctors.edit', $doctor) }}" class="btn btn-info text-light">
                            تعديل <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route(get_area_name() . '.doctors.print', $doctor) }}" class="btn btn-secondary text-light">
                            طباعة <i class="fa fa-print"></i>
                        </a>
                        <button type="button" class="btn btn-danger text-light" data-bs-toggle="modal" data-bs-target="#deleteDoctorModal">
                            حذف <i class="fa fa-trash"></i>
                        </button>
        
                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteDoctorModal" tabindex="-1" aria-labelledby="deleteDoctorLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route(get_area_name().'.doctors.destroy', $doctor->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteDoctorLabel">تأكيد الحذف</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                        </div>
                                        <div class="modal-body">
                                            هل أنت متأكد أنك تريد حذف الطبيب: <strong>{{ $doctor->name }}</strong>؟ لا يمكن التراجع عن هذا الإجراء.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">حذف</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
        
                    @if (get_area_name() == "finance")
                        <a href="{{ route('finance.total_invoices.create', $doctor) }}" class="btn btn-primary text-light">
                            دفع الفواتير
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        
       
        
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="main-content-label">المعلومات الشخصية </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="{{$doctor->type->badgeClass()}}">نوع الطبيب</th>
                                    <th class="bg-primary text-light">كود الطبيب</th>
                                    <th class="bg-primary text-light"> الرقم النقابي الاول </th>
                                    <th class="bg-primary text-light">الاسم</th>
                                    <th class="bg-primary text-light">الاسم بالإنجليزية</th>
                                    @if ($doctor->type == "libyan")
                                    <th class="bg-primary text-light">الرقم الوطني</th>
                                    @endif
                                    <th class="bg-primary text-light">اسم الأم</th>
                                    <th class="bg-primary text-light">الدولة</th>
                                    <th class="bg-primary text-light">تاريخ الميلاد</th>
                                    <th class="bg-primary text-light">الحالة الاجتماعية</th>
                                </tr>
                                <tr>
                                    <td  class="{{$doctor->type->badgeClass()}}" >{{ $doctor->type->label() }}</td>
                                    <td>{{ $doctor->code }}</td>
                                    <td>{{ $doctor->doctor_number }}</td>
                                    <td>{{ $doctor->name }}</td>
                                    <td>{{ $doctor->name_en }}</td>
                                    <td>{{ $doctor->mother_name }}</td>
                                    @if ($doctor->type == "libyan")
                                    <td>{{ $doctor->national_number }}</td>
                                    @endif
                                    <td>{{ $doctor->country->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->date_of_birth ? $doctor->date_of_birth->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        {{ $doctor->marital_status->label() }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-primary text-light">الجنس</th>
                                    <th class="bg-primary text-light">رقم الجواز</th>
                                    <th class="bg-primary text-light">تاريخ انتهاء الجواز</th>
                                    <th class="bg-primary text-light">الإقامة</th>
                                    <th class="bg-primary text-light">رقم الهاتف</th>
                                    <th class="bg-primary text-light"> رقم الواتساب  </th>
                                    <th class="bg-primary text-light">البريد الالكتروني</th>
                                    <th class="bg-primary text-light">حالة العضوية</th>
                                    <th class="bg-primary text-light">تاريخ انتهاء العضوية</th>
                                </tr>
                                <tr>
                                    <td>{{ $doctor->gender ? $doctor->gender->label() : "" }}</td>
                                    <td>{{ $doctor->passport_number }}</td>
                                    <td>{{ $doctor->passport_expiration ? $doctor->passport_expiration->format('Y-m-d') : 'N/A' }}</td>
                                    <td>{{ $doctor->address }}</td>
                                    <td>{{ $doctor->phone }}</td>
                                    <td>{{ $doctor->phone_2 }}</td>
                                    <td>{{ $doctor->email }}</td>
                                    <td>
                                        <span class="badge {{$doctor->membership_status->badgeClass()}} ">
                                            {{ $doctor->membership_status->label() }}
                                        </span>
                                    </td>
                                    <td>{{ $doctor->membership_expiration_date ? $doctor->membership_expiration_date->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4 class="main-content-label"> بكالوريس </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-primary text-light">جامعة التخرج</th>
                                    <th class="bg-primary text-light">تاريخ إنهاء الامتياز</th>
                                    <th class="bg-primary text-light">الدرجة العلمية</th>
                                    <th class="bg-primary text-light">جهة التخرج</th>
                                    <th class="bg-primary text-light">تاريخ التأهيل</th>
                                    <th class="bg-primary text-light">سنة  الإمتياز</th>
                                </tr>
                                <tr>
                                    <td>{{ $doctor->handGraduation->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->internership_complete ? $doctor->internership_complete->format('Y-m-d') : 'N/A' }}</td>
                                    <td>{{ $doctor->academicDegree->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->qualificationUniversity->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->certificate_of_excellence_date ? $doctor->certificate_of_excellence_date: 'N/A' }}</td>
                                    <td>{{$doctor->internership_complete ? $doctor->internership_complete->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4 class="main-content-label"> العمل الحالي  </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-primary text-light">الفرع</th>
                                    <th class="bg-primary text-light">التخصص الأول</th>
                                    <th class="bg-primary text-light">التخصص الدقيق</th>
                                    <th class="bg-primary text-light">تاريخ الإضافة</th>
                                </tr>
                                <tr>
                                    <td>{{ $doctor->branch?->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->specialty1->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->specialty2->name ?? 'N/A'}}
                                    <td>{{ $doctor->created_at->format('Y-m-d') }}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4 class="main-content-label"> العمل السابق  </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-primary text-light">الجهات السابقة</th>
                                    <th class="bg-primary text-light">الخبرة</th>
                                    <th class="bg-primary text-light">ملاحظات</th>

                                </tr>
                                <tr>
                                    <td>
                                        @foreach ($doctor->institutions as $medicalFacility)
                                            <span class="badge bg-primary badge-primary text-light">{{ $medicalFacility->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $doctor->experience }}</td>
                                    <td>{{ $doctor->notes }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4 class="main-content-label">  ملفات الطبيب  </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th class="bg-primary text-light">#</th>
                                <th class="bg-primary text-light">اسم الملف</th>
                                <th class="bg-primary text-light">نوع الملف</th>
                                <th class="bg-primary text-light">تحميل</th>
                            </thead>
                            <tbody>
                                @foreach ($doctor->files as $file)
                                    <tr>
                                        <th>{{$file->id}}</th>
                                        <th>{{$file->file_name}}</th>
                                        <th>{{$file->fileType ? $file->fileType->name : ""}}</th>
                                        <td>

                                            {{-- add preview button --}}
                                            <a href="{{Storage::url($file->file_path)}}" class="btn  btn-primary" target="_blank"><i class="fa fa-eye"></i></a>

                                            <a download href="{{Storage::url($file->file_path)}}" class="btn  btn-primary"><i class="fa fa-download"></i></a>
                                            {{-- <form action="{{ route(get_area_name().'.files.destroy', ['doctor' => $doctor->id, 'file' => $file->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger " onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذه الدرجة العلمية؟')">حذف</button>
                                            </form> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <h4 class="main-content-label">   اذونات مزاولة الطبيب   </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="bg-primary text-light">#</th>
                                    <th class="bg-primary text-light">المرخص له</th>
                                    <th class="bg-primary text-light">تاريخ الإصدار</th>
                                    <th class="bg-primary text-light">تاريخ الانتهاء</th>
                                    <th class="bg-primary text-light">الحالة</th>
                                    <th class="bg-primary text-light">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($doctor->licenses as $licence)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $licence->licensable->name }}</td>
                                    <td>{{ $licence->issued_date }}</td>
                                    <td>{{ $licence->expiry_date }}</td>
                                    <td>{!! $licence->status_badge !!}</td>
                                    <td>
                                        <a href="{{ route(get_area_name().'.licences.show', $licence) }}" class="btn btn-primary  text-light">عرض <i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>






                    <h4 class="main-content-label">  سجلات الطبيب  </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="bg-primary text-light">#</th>
                                    <th class="bg-primary text-light">المستخدم</th>
                                    <th class="bg-primary text-light">تفاصيل</th>
                                    <th class="bg-primary text-light">تاريخ الانشاء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($doctor->logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $log->user->name }}</td>
                                    <td>{{ $log->details}}</td>
                                    <td>{{ $log->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
