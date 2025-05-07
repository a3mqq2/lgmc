@extends('layouts.' . get_area_name())
@section('title', 'تفاصيل الطبيب')

@section('content')
<div class="row">
   

    <div class="col-md-12 mb-3">
        <h2>{{$doctor->name}}</h2>
    </div>

    @if ((get_area_name() == "user" && $doctor->type->value == "libyan" ) || get_area_name() == "admin" && $doctor->type->value != "libyan")
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="main-content-label">الإجراءات</h4>

              
                @if ($doctor->type->value == 'libyan')
                <button type="button" class="btn btn-warning text-light" data-bs-toggle="modal" data-bs-target="#doctorTransferModal">
                    <i class="fa fa-exchange-alt"></i> طلب نقل طبيب
                </button>
                <div class="modal fade" id="doctorTransferModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action="{{ route(get_area_name() . '.doctor-transfers.store') }}" method="POST">
                                @csrf
                                <div class="modal-header text-light">
                                    <h5 class="modal-title"><i class="fa fa-exchange-alt"></i> إضافة طلب نقل طبيب</h5>
                                    <button type="button" class="btn-close text-light" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fa fa-user-md"></i> الطبيب</label>
                                            <select name="doctor_id" class="form-control select2" required>
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fa fa-map-marker-alt"></i> إلى الفرع الجديد</label>
                                            <select name="to_branch_id" class="form-control select2" required>
                                                <option value="">-- اختر الفرع --</option>
                                                @foreach(App\Models\Branch::all() as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label"><i class="fa fa-info-circle"></i> ملاحظات</label>
                                            <textarea name="note" rows="4" class="form-control" placeholder="أدخل ملاحظات (اختياري)">{{ old('note') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-end">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times"></i> إلغاء</button>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> حفظ الطلب</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                    
                @endif



                @if(auth()->user()->branch_id != 1)
                    <button type="button" class="btn {{ $doctor->membership_status->value === 'banned' ? 'btn-success' : 'btn-danger' }}" data-bs-toggle="modal" data-bs-target="#toggleBanDoctorModal">
                        <i class="fa {{ $doctor->membership_status->value === 'banned' ? 'fa-lock-open' : 'fa-lock' }}"></i>
                        {{ $doctor->membership_status->value === 'banned' ? 'رفع الحظر' : 'حظر الطبيب' }}
                    </button>
                    <div class="modal fade" id="toggleBanDoctorModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route(get_area_name() . '.doctors.toggle-ban', $doctor->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            {{ $doctor->membership_status->value === 'banned' ? 'تأكيد رفع الحظر' : 'تأكيد حظر الطبيب' }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                @endif

                @if(!$doctor->code && $doctor->membership_status === \App\Enums\MembershipStatus::Pending)
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveInitial{{ $doctor->id }}">القبول المبدئي للعضوية</button>
                    <div class="modal fade" id="approveInitial{{ $doctor->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route(get_area_name().'.doctors.approve', $doctor) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">تأكيد العضوية المبدئية</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label>حدد موعد الزيارة</label>
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

                @if(!$doctor->code && $doctor->membership_status === \App\Enums\MembershipStatus::InitApprove)
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveFinal{{ $doctor->id }}">القبول النهائي للعضوية</button>
                    <div class="modal fade" id="approveFinal{{ $doctor->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route(get_area_name().'.doctors.approve',['doctor'=>$doctor,'init'=>1]) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">تأكيد العضوية النهائية</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

                @if(get_area_name()=='user' && in_array($doctor->membership_status,[\App\Enums\MembershipStatus::Pending,\App\Enums\MembershipStatus::InitApprove]))
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectMembership{{ $doctor->id }}">رفض العضوية</button>
                    <div class="modal fade" id="rejectMembership{{ $doctor->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route(get_area_name().'.doctors.reject',['doctor'=>$doctor]) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">تأكيد رفض العضوية</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <textarea name="notes" class="form-control" rows="4" required placeholder="أدخل سبب الرفض"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">تأكيد</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                @if(get_area_name()!='finance')
                    <a href="{{ route(get_area_name().'.doctors.edit',$doctor) }}" class="btn btn-info text-light">تعديل <i class="fa fa-edit"></i></a>
                    <a href="{{ route(get_area_name().'.doctors.print',$doctor) }}" class="btn btn-secondary text-light">طباعة <i class="fa fa-print"></i></a>
                    <button type="button" class="btn btn-danger text-light" data-bs-toggle="modal" data-bs-target="#deleteDoctorModal">حذف <i class="fa fa-trash"></i></button>
                    <div class="modal fade" id="deleteDoctorModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route(get_area_name().'.doctors.destroy',$doctor->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="modal-header">
                                        <h5 class="modal-title">تأكيد الحذف</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        هل أنت متأكد أنك تريد حذف الطبيب: <strong>{{ $doctor->name }}</strong>؟
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

                @if(get_area_name()=='finance')
                    <a href="{{ route('finance.total_invoices.create',$doctor) }}" class="btn btn-primary text-light">دفع الفواتير</a>
                @endif

            </div>
        </div>
    </div>
    @endif

    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <ul class="nav nav-tabs" id="doctorTab" role="tablist">
                    <li class="nav-item"><button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button">البيانات الشخصية</button></li>
                    <li class="nav-item"><button class="nav-link" id="docs-tab" data-bs-toggle="tab" data-bs-target="#docs" type="button">مستندات الطبيب</button></li>
                    <li class="nav-item"><button class="nav-link" id="licenses-tab" data-bs-toggle="tab" data-bs-target="#licenses" type="button">اذونات المزاولة</button></li>
                    @if(auth()->user()->permissions()->where('name','doctor-mail')->count())
                        <li class="nav-item"><button class="nav-link" id="external-tab" data-bs-toggle="tab" data-bs-target="#external" type="button">طلبات الأوراق الخارجية</button></li>
                    @endif
                </ul>

                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <div class="row">
                            <div class="col-md-9">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th class="bg-light text-primary">نوع الطبيب</th>
                                            <td class="{{ $doctor->type->badgeClass() }}">{{ $doctor->type->label() }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light text-primary">كود الطبيب</th>
                                            <td>{{ $doctor->code }}</td>
                                        </tr>
                                        @if ($doctor->type->value !== 'visitor')
                                        <tr>
                                            <th class="bg-light text-primary">الرقم النقابي الأول</th>
                                            <td>{{ $doctor->doctor_number }}</td>
                                        </tr>
                                        @endif
                                       
                                        @if (get_area_name() == "admin")
                                            <tr>
                                                <th class="bg-light text-primary"> الفرع   </th>
                                                <td>{{ $doctor->branch ? $doctor->branch->name : "" }}</td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <th class="bg-light text-primary">الاسم</th>
                                            <td>{{ $doctor->name }}</td>
                                        </tr>
                                        @if (!in_array($doctor->type->value, ['visitor', 'foreign']))
                                        <tr>
                                            <th class="bg-light text-primary">الاسم بالإنجليزية</th>
                                            <td>{{ $doctor->name_en }}</td>
                                        </tr>
                                        @endif
                                        @if ($doctor->type->value === 'libyan')
                                        <tr>
                                            <th class="bg-light text-primary">الرقم الوطني</th>
                                            <td>{{ $doctor->national_number }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light text-primary">اسم الأم</th>
                                            <td>{{ $doctor->mother_name }}</td>
                                        </tr>
                                        @endif
                                        @if ($doctor->type->value !== 'libyan')
                                        <tr>
                                            <th class="bg-light text-primary">الدولة</th>
                                            <td>{{ $doctor->country->name ?? '-' }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th class="bg-light text-primary">الصفة</th>
                                            <td>{{ $doctor->rank_name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light text-primary">التخصص الأول</th>
                                            <td>{{ $doctor->specialty1->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light text-primary">التخصص الدقيق</th>
                                            <td>{{ $doctor->specialty_2 ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light text-primary">تاريخ الميلاد</th>
                                            <td>{{ $doctor->date_of_birth?->format('Y-m-d') ?? '-' }}</td>
                                        </tr>
                                        @if ($doctor->type->value === 'libyan')
                                        <tr>
                                            <th class="bg-light text-primary">الحالة الاجتماعية</th>
                                            <td>{{ $doctor->marital_status->label() }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th class="bg-light text-primary">الجنس</th>
                                            <td>{{ $doctor->gender?->label() }}</td>
                                        </tr>
                                        @if ($doctor->type->value === 'libyan')
                                        <tr>
                                            <th class="bg-light text-primary">رقم الجواز</th>
                                            <td>{{ $doctor->passport_number }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light text-primary">تاريخ انتهاء الجواز</th>
                                            <td>{{ $doctor->passport_expiration?->format('Y-m-d') ?? '-' }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th class="bg-light text-primary">الإقامة</th>
                                            <td>{{ $doctor->address }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light text-primary">رقم الهاتف</th>
                                            <td>{{ $doctor->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light text-primary">رقم الواتساب</th>
                                            <td>{{ $doctor->phone_2 }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light text-primary">البريد الإلكتروني</th>
                                            <td>{{ $doctor->email }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light text-primary">حالة العضوية</th>
                                            <td><span class="badge {{ $doctor->membership_status->badgeClass() }}">{{ $doctor->membership_status->label() }}</span></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light text-primary">تاريخ انتهاء العضوية</th>
                                            <td>{{ $doctor->membership_expiration_date?->format('Y-m-d') ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-3 text-center">
                                @php($img=$doctor->files->first())
                                @if($img)
                                    <img src="{{ Storage::url($img->file_path) }}" class="img-thumbnail" style="max-width:100%;max-height:300px;">
                                    <p class="mt-2 text-muted">{{ $img->file_name }}</p>
                                @else
                                    <div class="text-muted">لا توجد صورة</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    

                    <div class="tab-pane fade" id="docs" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">مستندات الطبيب</h5>

                            @if (get_area_name() == "user" && $doctor->type->value == "libyan")
                                <a href="{{ route(get_area_name().'.doctors.files.create',$doctor) }}" class="btn btn-primary"><i class="fa fa-plus"></i> إضافة مستند للطبيب</a>
                            @endif

                            @if (get_area_name() == "admin" && $doctor->type->value  != "libyan")
                                <a href="{{ route(get_area_name().'.doctors.files.create',$doctor) }}" class="btn btn-primary"><i class="fa fa-plus"></i> إضافة مستند للطبيب</a>
                            @endif


                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="bg-light text-primary">#</th>
                                        <th class="bg-light text-primary">العرض</th>
                                        <th class="bg-light text-primary">اسم الملف</th>
                                        <th class="bg-light text-primary">نوع الملف</th>
                                        <th class="bg-light text-primary">تحميل</th>
                                        <th class="bg-light text-primary">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($doctor->files as $file)
                                        <tr>
                                            <td>{{ $file->id }}</td>
                                            <td><img src="{{ Storage::url($file->file_path) }}" class="img-thumbnail" style="width:50px;height:50px;object-fit:cover;"></td>
                                            <td>{{ $file->file_name }}</td>
                                            <td>{{ $file->fileType?->name ?? '—' }}</td>
                                            <td>
                                                <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                                                <a download href="{{ Storage::url($file->file_path) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-download"></i></a>
                                            </td>
                                            <td>
                                                <form action="{{ route(get_area_name().'.files.destroy',['doctor'=>$doctor->id,'file'=>$file->id]) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد؟')"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="licenses" role="tabpanel">
                        <a href="{{ route(get_area_name().'.licences.create', ['type' => 'doctors', 'doctor_id' => $doctor->id]) }}" class="btn btn-success">
                            <i class="fa fa-plus"></i> إضافة إذن مزاولة جديد
                        </a>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th><th>المرخص له</th><th>تاريخ الإصدار</th><th>تاريخ الانتهاء</th>
                                        <th>المنشأة</th><th>الحالة</th><th>إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($doctor->licenses as $licence)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $licence->licensable->name }}</td>
                                            <td>{{ $licence->issued_date }}</td>
                                            <td>{{ $licence->expiry_date }}</td>
                                            <td>{{ $licence->medicalFacility?->name }}</td>
                                            <td>{!! $licence->status_badge !!}</td>
                                            <td><a href="{{ route(get_area_name().'.licences.show',$licence) }}" class="btn btn-primary text-light"><i class="fa fa-eye"></i></a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if(auth()->user()->permissions()->where('name','doctor-mail')->count())
                        <div class="tab-pane fade" id="external" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th><th>الطبيب</th><th>الإيميلات</th><th>الدول</th>
                                            <th>الإجمالي</th><th>الحالة</th><th>تاريخ الإنشاء</th><th>إجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($doctor->doctor_mails as $mail)
                                            <tr>
                                                <td>{{ $mail->id }}</td>
                                                <td><strong>{{ $mail->doctor->name }}</strong><br><small>{{ $mail->doctor->code }}</small></td>
                                                <td><ul>@foreach($mail->emails as $e)<li>{{ $e }}</li>@endforeach</ul></td>
                                                <td>{{ $mail->country_names }}</td>
                                                <td>{{ number_format($mail->grand_total,2) }} د.ل</td>
                                                <td><span class="badge {{ ['under_approve'=>'bg-warning','under_payment'=>'bg-info','under_proccess'=>'bg-primary','done'=>'bg-success','failed'=>'bg-danger','under_edit'=>'bg-secondary'][$mail->status] ?? 'bg-secondary' }}">{{ ['under_approve'=>'قيد الموافقة','under_payment'=>'قيد الدفع','under_proccess'=>'قيد التجهيز','done'=>'مكتمل','failed'=>'فشل','under_edit'=>'قيد التعديل'][$mail->status] ?? 'غير معروف' }}</span></td>
                                                <td>{{ $mail->created_at->format('Y-m-d') }}</td>
                                                <td class="btn-group btn-group-sm">
                                                    <a href="{{ route(get_area_name().'.doctor-mails.show',$mail) }}" class="btn btn-outline-info"><i class="fa fa-eye"></i></a>
                                                    @if($mail->status==='under_proccess')
                                                        <form action="{{ route(get_area_name().'.doctor-mails.complete',$mail) }}" method="POST" onsubmit="return confirm('تأكيد؟');">@csrf @method('PUT')<button class="btn btn-outline-success"><i class="fa fa-check"></i></button></form>
                                                    @endif
                                                    <a href="{{ route(get_area_name().'.doctor-mails.print',$mail) }}" target="_blank" class="btn btn-outline-primary"><i class="fa fa-print"></i></a>
                                                    <form action="{{ route(get_area_name().'.doctor-mails.destroy',$mail) }}" method="POST" onsubmit="return confirm('حذف؟');">@csrf @method('DELETE')<button class="btn btn-outline-danger"><i class="fa fa-trash"></i></button></form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="8" class="text-center py-4">لا توجد طلبات</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
