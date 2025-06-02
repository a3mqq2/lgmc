@extends('layouts.' . get_area_name())
@section('title', 'تفاصيل الطبيب')

@section('content')
<div class="row">
   

    <div class="col-md-12 mb-3">
        <h2>{{$doctor->name}}</h2>
    </div>

    
    @if ($doctor->membership_status->value != "under_approve" && $doctor->membership_status->value != "under_edit")
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="main-content-label">الإجراءات</h4>

           

                @if ($doctor->transfers->where('status','pending')->count() == 0 && $doctor->branch_id != null)
                       
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
                                            <select name="to_branch_id" class="form-control" required>
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
                                            <label class="form-label"><i class="fa fa-info-circle"></i>سبب النقل</label>
                                            <textarea name="note" rows="4" class="form-control" placeholder="أدخل سبب النقل (اجباري)" required>{{ old('note') }}</textarea>
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



                @if (get_area_name() == "admin")
                
                    @if (auth()->user()->id == 1)
                    <button type="button" class="btn {{ $doctor->membership_status->value === 'banned' ? 'btn-success' : 'btn-danger' }}" data-bs-toggle="modal" data-bs-target="#toggleBanDoctorModal">
                        <i class="fa {{ $doctor->membership_status->value === 'banned' ? 'fa-lock-open' : 'fa-lock' }}"></i>
                        {{ $doctor->membership_status->value === 'banned' ? 'رفع الحظر' : 'حظر الطبيب' }}
                    </button>
                    @endif

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
                    

                
                @if ($doctor->membership_status->value == "pending")
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


                @if ($doctor->membership_status->value == "init_approve")

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


                    <a href="{{ route(get_area_name().'.doctors.edit',$doctor) }}" class="btn btn-info text-light">تعديل <i class="fa fa-edit"></i></a>
                    <a href="{{ route(get_area_name().'.doctors.print',$doctor) }}" class="btn btn-secondary text-light">طباعة بيانات الطبيب <i class="fa fa-print"></i></a>
                    <a href="{{ route(get_area_name().'.doctors.print-id',$doctor) }}" class="btn btn-primary text-light"> طباعة  البطاقة  <i class="fa fa-code"></i></a>
                   

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
                    <li class="nav-item"><button class="nav-link" id="finance-tab" data-bs-toggle="tab" data-bs-target="#finance" type="button"> الملف المالي </button></li>
                    <li class="nav-item"><button class="nav-link" id="licenses-tab" data-bs-toggle="tab" data-bs-target="#licenses" type="button"> اذونات المزاولة  </button></li>
                    @if ($doctor->membership_status->value != "under_approve")
                    @if(auth()->user()->permissions()->where('name','doctor-mail')->count())
                        <li class="nav-item"><button class="nav-link" id="external-tab" data-bs-toggle="tab" data-bs-target="#external" type="button">طلبات الأوراق الخارجية</button></li>
                    @endif
                    @endif
                    
                </ul>

                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <div class="row">
                            <div class="col-md-9">
                                <h3 class="text-primary">البيانات الشخصية</h3>
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
                                        @if (isset($doctor->doctor_number))
                                        <tr>
                                            <th class="bg-light text-primary">الرقم النقابي الأول</th>
                                            <td>{{ $doctor->doctor_number }}</td>
                                        </tr>
                                        @endif
                                       
                                        @if (get_area_name() == "admin" && $doctor->branch)
                                            <tr>
                                                <th class="bg-light text-primary"> الفرع   </th>
                                                <td>{{ $doctor->branch ? $doctor->branch->name : "" }}</td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <th class="bg-light text-primary">الاسم بالكامل</th>
                                            <td>{{ $doctor->name }}</td>
                                        </tr>
                                        @if (!in_array($doctor->type->value, ['visitor', 'foreign']))
                                        <tr>
                                            <th class="bg-light text-primary">الاسم بالإنجليزية</th>
                                            <td>{{ $doctor->name_en }}</td>
                                        </tr>
                                        @endif

                                        @if ($doctor->date_of_birth)
                                        <tr>
                                            <th class="bg-light text-primary">تاريخ الميلاد</th>
                                            <td>{{ date('Y-m-d', strtotime($doctor->date_of_birth)) }}</td>
                                        </tr>
                                        @endif

                                        @if ($doctor->marital_status)
                                        <tr>
                                            <th class="bg-light text-primary">  الحالة الاجتماعية </th>
                                            <td>
                                                {{$doctor->marital_status == "signle" ? 'اعزب' : 'متزوج'}}
                                            </td>
                                        </tr>
                                        @endif

                                        @if ($doctor->gender)
                                        <tr>
                                            <th class="bg-light text-primary">  الجنس   </th>
                                            <td>
                                                {{$doctor->gender == "male" ? 'ذكر' : 'انثى'}}
                                            </td>
                                        </tr>
                                        @endif

                                        @if ($doctor->passport_number)
                                        <tr>
                                            <th class="bg-light text-primary">  رقم جواز السفر   </th>
                                            <td>
                                                {{$doctor->passport_number}}
                                            </td>
                                        </tr>
                                        @endif

                                        @if ($doctor->passport_expiration)
                                        <tr>
                                            <th class="bg-light text-primary">  تاريخ انتهاء صلاحية الجواز </th>
                                            <td>
                                                {{date('Y-m-d', strtotime($doctor->passport_expiration))}}
                                            </td>
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
                                        <tr>
                                            <th class="bg-light text-primary"> حالة الاشتراك </th>
                                            <td class="{{ $doctor->membership_status->badgeClass() }} text-light">{{ $doctor->membership_status->label() }}</td>
                                        </tr>


                                        @if ($doctor->edit_note)
                                        <tr>
                                            <th class="bg-light text-primary">  ملاحظات التعديل  </th>
                                            <td> {{ $doctor->edit_note }} </td>
                                        </tr>
                                        @endif


                                        <tr>
                                            <th class="bg-light text-primary">صلاحية الاشتراك السنوي</th>
                                            <td class="text-center">
                                                {{ $doctor->membership_expiration_date ? date('Y-m-d', strtotime($doctor->membership_expiration_date)) :  '—'}}
                                            </td>
                                        </tr>


                                       @if ($doctor->membership_status->value == "suspended" )
                                       <tr>
                                            <th class="bg-light text-primary"> سبب التعليق    </th>
                                           <td>
                                            <div class="text-center">
                                                {{ $doctor->suspended_reason ? $doctor->suspended_reason : '—' }}
                                            </div>
                                           </td>
                                        </tr>
                                       @endif


                                    </tbody>
                                </table>


                                <h3 class="text-primary"> بيانات الاتصال </h3>
                                <table class="table table-bordered">
                                    <tbody>
                                      

                                        @if ($doctor->address)
                                        <tr>
                                            <th class="bg-light text-primary"> العنوان </th>
                                            <td>{{ $doctor->address }}</td>
                                        </tr>
                                        @endif

                                        <tr>
                                            <th class="bg-light text-primary">رقم الهاتف</th>
                                            <td>{{ $doctor->phone }}</td>
                                        </tr>

                                        @if (isset($doctor->phone_2))
                                        <tr>
                                            <th class="bg-light text-primary">الواتساب </th>
                                            <td>{{ $doctor->phone_2 }}</td>
                                        @endif

                                        <tr>
                                            <th class="bg-light text-primary">البريد الإلكتروني</th>
                                            <td>{{ $doctor->email }}</td>
                                        </tr>
                                        @if (isset($doctor->address))
                                        <tr>
                                            <th class="bg-light text-primary">العنوان</th>
                                            <td>{{ $doctor->address }}</td>
                                        </tr>
                                        @endif
                                      
                                  
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

                        <div class="row">
                            @if ($doctor->hand_graduation_id)
                                <div class="col-md-6">
                                    <h3 class="text-primary">بيانات البكالوريس</h3>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th class="bg-light text-primary"> اسم الجامعة </th>
                                                <td>{{ $doctor->handGraduation->name }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light text-primary">تاريخ الحصول عليها </th>
                                                <td>{{ $doctor->graduation_date }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            @if ($doctor->qualification_university_id || $doctor->internership_complete)
                                <div class="col-md-6">
                                    <h3 class="text-primary">بيانات الامتياز</h3>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th class="bg-light text-primary"> اسم الجامعة </th>
                                                <td>{{ $doctor->qualificationUniversity?->name }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light text-primary">تاريخ الحصول عليها </th>
                                                <td>{{ $doctor->internership_complete }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif


                            @if ($doctor->academic_degree_id)
                                <div class="col-md-12">
                                    <h3 class="text-primary"> الدرجة العلمية الحالية  </h3>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-light text-primary">اسم الدرجة</th>
                                                <td>
                                                    {{ $doctor->academicDegree?->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light text-primary"> الجهة  </th>
                                                <td>
                                                    {{ $doctor->academicDegreeUniversity?->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light text-primary"> بتاريخ   </th>
                                                <td>
                                                    {{ $doctor->certificate_of_excellence_date }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif

                        </div>


                        @if (($doctor->membership_status->value != "under_approve" && $doctor->membership_status->value != "under_edit") || $doctor->type->value == "libyan")
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-primary">بيانات العمل الحالي</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th class="bg-light text-right">الصفة</th>
                                                <td>{{$doctor->doctor_rank ? $doctor->doctor_rank->name : "لم يحدد"}}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light text-right">التخصص</th>
                                                <td>{{$doctor->specialization}}</td>
                                            </tr>
                                           @if ($doctor->branch)
                                            <tr>
                                                    <th class="bg-light text-right">الفرع </th>
                                                    <td>{{$doctor->branch ? $doctor->branch->name : "لم يحدد"}}</td>
                                                </tr>
                                           @endif
                                            <tr>
                                                <th class="bg-light text-right">المنشات الطبية / القطاع الخاص </th>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light text-right"> جهة العمل / المستشفى </th>
                                                <td> {{ $doctor->institutionObj  ? $doctor->institutionObj->name : '-' }} </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        @endif
                       


                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-primary"> الانتساب  </h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th class="bg-light text-right">تاريخ الانتساب</th>
                                                <td>{{date('Y-m-d', strtotime($doctor->registered_at) )}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    

                    <div class="tab-pane fade" id="docs" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">مستندات الطبيب</h5>

                            <a href="{{ route(get_area_name().'.doctors.files.create',$doctor) }}" class="btn btn-primary"><i class="fa fa-plus"></i> إضافة مستند للطبيب</a>

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


                                        @if ($doctor->membership_status->value != "under_approve" && $doctor->membership_status->value != "under_edit")
                                            @if (get_area_name() == "admin" && $doctor->type->value != "libyan" || get_area_name() == "user" && $doctor->type->value == "libyan")
                                            <td>
                                              
                                                <form action="{{ route(get_area_name().'.files.destroy',['doctor'=>$doctor->id,'file'=>$file->id]) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد؟')"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                            @endif
                                        @endif
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>



                    <div class="tab-pane fade" id="finance" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"> الملف المالي للطبيب</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>رقم الفاتورة</th>
                                        <th>الوصف</th>
                                        <th>المستخدم</th>
                                        <th>المبلغ</th>
                                        <th>الحالة</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>دفع</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($doctor->invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->id }}</td>
                                            <td>{{ $invoice->id }}</td>
                                            <td>{{ $invoice->description }}</td>
                                            <td>{{ $invoice->user?->name ?? '-' }}</td>
                                            <td>{{ number_format($invoice->amount, 2) }} د.ل</td>
                                            <td>
                                               <span class="badge {{$invoice->status->badgeClass()}}">
                                                    {{$invoice->status->label()}}
                                               </span>
                                            </td>
                                            <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                @if ($invoice->status->value == "unpaid")
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#received_{{$invoice->id}}">
                                                    استلام القيمة <i class="fa fa-check"></i>
                                                </button>
                                                <div class="modal fade" id="received_{{$invoice->id}}" tabindex="-1" aria-labelledby="received_{{$invoice->id}}Label" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST" action="{{ route(get_area_name() . '.invoices.received', ['invoice' => $invoice->id]) }}">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="received_{{$invoice->id}}Label">تآكيد إستلام القيمة</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="notes" class="form-label">ملاحظات - اختياري</label>
                                                                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                                    <button type="submit" class="btn btn-primary">موافقة</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">لا توجد فواتير متاحة.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>





                    <div class="tab-pane fade" id="licenses" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"> أذونات المزاولة </h5>
                        </div>


                        @if ($doctor->membership_status->value == "active")
                        <!-- زر إضافة إذن مزاولة جديد -->
                        <button type="button" class="btn btn-success text-light mb-3" data-bs-toggle="modal" data-bs-target="#addPracticeLicenseModal">
                            <i class="fa fa-plus"></i> إضافة إذن مزاولة جديد
                        </button>
    
                        <!-- مودال إضافة إذن مزاولة جديد -->
                        <div class="modal fade" id="addPracticeLicenseModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{route(get_area_name().'.licences.store', $doctor)}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                                        
                                        <div class="modal-header text-light">
                                            <h5 class="modal-title">
                                                <i class="fa fa-certificate"></i> إضافة إذن مزاولة جديد
                                            </h5>
                                            <button type="button" class="btn-close text-light" data-bs-dismiss="modal"></button>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <!-- معلومات الطبيب -->
                                                <div class="col-12">
                                                    <div class="alert alert-info">
                                                        <strong>الطبيب:</strong> {{ $doctor->name }} 
                                                        <span class="badge bg-primary">{{ $doctor->code }}</span>
                                                    </div>
                                                </div>
    
                                                <!-- حدد الصفة -->
                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        <i class="fa fa-user-tie"></i> حدد الصفة <span class="text-danger">*</span>
                                                    </label>
                                                    <select name="doctor_rank_id" class="form-control" required>
                                                        <option value="">-- اختر الصفة --</option>
                                                        @foreach($doctor_ranks as $rank)
                                                            <option value="{{ $rank->id }}" 
                                                                {{ $doctor->doctor_rank_id == $rank->id ? 'selected' : '' }}>
                                                                {{ $rank->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
    
                                                <!-- التخصص -->
                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        <i class="fa fa-stethoscope"></i> التخصص
                                                    </label>
                                                    <select name="specialty_id" class="form-control">
                                                        <option value="">-- اختر التخصص --</option>
                                                        @foreach($specialties as $specialty)
                                                            <option value="{{ $specialty->id }}"
                                                                {{ $doctor->specialty_1_id == $specialty->id ? 'selected' : '' }}>
                                                                {{ $specialty->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
    
                                                <!-- تاريخ الإصدار -->
                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        <i class="fa fa-calendar"></i> تاريخ الإصدار <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="date" 
                                                        name="issue_date" 
                                                        class="form-control" 
                                                        value="{{ date('Y-m-d') }}" 
                                                        required>
                                                </div>
    
    
                                                <!-- تاريخ الإصدار -->
                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        <i class="fa fa-calendar"></i> المنشأة الطبية <span class="text-danger">*</span>
                                                    </label>
                                                    
                                                    <select name="medical_facility_id" 
                                                            id="medicalFacilityModalSelect" 
                                                            class="form-control">
                                                        <option value="">حدد منشأة طبية</option>
                                                        @foreach ($medicalFacilities as $medicalFacility)
                                                            <option value="{{$medicalFacility->id}}">{{$medicalFacility->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
    
    
                                            </div>
                                        </div>
                                        
                                        <div class="modal-footer justify-content-end">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fa fa-times"></i> إلغاء
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa fa-save"></i> حفظ الإذن
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>                
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> رقم الأذن </th>
                                        <th> المنشأة الطبية </th>
                                        <th>الحالة</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الاعدادات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($doctor->licenses as $license)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $license->code }}</td>
                                            <td>{{ $license->workIn?->name ?? '—' }}</td>
                                            <td>
                                                <span class="badge {{ $license->status->badgeClass() }}">
                                                    {{ $license->status->label() }}
                                                </span>
                                            </td>
                                            <td>{{ $license->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                @if ($license->status->value == 'active')
                                                <a href="{{ route(get_area_name().'.licences.print', $license) }}" class="btn btn-secondary btn-sm text-light">
                                                    طباعه <i class="fa fa-print"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">لا توجد فواتير متاحة.</td>
                                        </tr>
                                    @endforelse
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
        @if($doctor->membership_status->value === 'under_approve')
        <div class="col-md-12 mb-4">
            <form action="{{ route(get_area_name().'.doctors.change-status', $doctor) }}"  method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">الموافقة</div>
                    <div class="card-body">

    
                        {{-- اختيار الحالة --}}
                        <div class="mb-3">
                            <label class="form-label">حدد الحالة</label>
                            <select name="final_status" id="final_status" class="form-control" required>
                                <option value="">اختر الحالة</option>
                                <option value="under_edit">تحويل إلى قيد التعديل</option>
                                <option value="approved">موافقة على العضوية</option>
                            </select>
                        </div>
    
                        {{-- حقل السبب --}}
                        <div id="edit_note_container" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">السبب</label>
                                <textarea name="edit_note" class="form-control" rows="2" placeholder="اكتب السبب..."></textarea>
                            </div>
                        </div>
    
                        {{-- حقول الموافقة (تظهر عند اختيار "موافقة") --}}
                        <div id="approve_fields_container" style="display: none;">
                        <div class="alert alert-info">يجب عليك التأكد من هذه البيانات</div>
                            <div class="mb-3">
                                <label class="form-label">حدد الصفة</label>
                                <select name="doctor_rank_id" class="form-control">
                                    @foreach ($doctor_ranks as $doctor_rank)
                                        <option value="{{ $doctor_rank->id }}" {{$doctor->doctor_rank_id == $doctor_rank->id ? "selected" : ""}} >{{ $doctor_rank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
    
                            <div class="mb-3">
                                <label class="form-label">حدد تخصص (ان وجد) </label>
                                <select name="specialty_1_id" class="form-control">
                                    <option value="">اختر تخصص </option>
                                    @foreach ($specialties as $item)
                                        <option value="{{ $item->id }}" {{$doctor->specialty_1_id == $item->id  ? "selected" : ""}} >{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
    


                            @if ($doctor->type->value == "libyan")
                            <div class="mb-3">
                                <label class="form-label">تاريخ الانتساب </label>
                                <input type="date" name="registered_at" value="{{$doctor->internership_complete ? Carbon\Carbon::parse($doctor->internership_complete)->addDay() : ""}}" class="form-control">
                            </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">الجهة العامة/ المستشفى</label>
                                <select name="institution_id" id="" class="form-control select2 selectize">
                                    <option value="">حدد مستشفى</option>
                                    @foreach ($institutions as $institution)
                                        <option value="{{$institution->id}}">{{$institution->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="checkbox" name="is_paid" id="is_paid">
                                <label for="is_paid" class="mr-3" value="1" style="margin-right: 10px!important;">   اعتبار الفاتورة مدفوعة </label>
                            </div>
                        </div>
    

                        

                        {{-- زر الحفظ --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">حفظ</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
    </div>

</div>
@endsection

<!-- وفي نهاية الصفحة، استبدل قسم scripts بهذا الكود -->
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إعداد المودال والـ Select2
    function initModalSelect2() {
        // تدمير أي select2 موجود مسبقاً
        if ($('#medicalFacilityModalSelect').hasClass('select2-hidden-accessible')) {
            $('#medicalFacilityModalSelect').select2('destroy');
        }
        
        // تهيئة Select2 الجديدة
        $('#medicalFacilityModalSelect').select2({
            dropdownParent: $('#addPracticeLicenseModal'),
            placeholder: "حدد منشأة طبية",
            allowClear: true,
            width: '100%',
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

    // عند فتح المودال
    $('#addPracticeLicenseModal').on('shown.bs.modal', function () {
        initModalSelect2();
    });

    // عند إغلاق المودال
    $('#addPracticeLicenseModal').on('hidden.bs.modal', function () {
        if ($('#medicalFacilityModalSelect').hasClass('select2-hidden-accessible')) {
            $('#medicalFacilityModalSelect').select2('destroy');
        }
    });

    // للنموذج الآخر في الصفحة (نموذج الموافقة)
    const statusSelect = document.getElementById('final_status');
    const noteContainer = document.getElementById('edit_note_container');
    const fieldsContainer = document.getElementById('approve_fields_container');

    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'approved') {
                fieldsContainer.style.display = '';
                noteContainer.style.display = 'none';
            } else if (this.value === 'under_edit') {
                fieldsContainer.style.display = 'none';
                noteContainer.style.display = '';
            } else {
                fieldsContainer.style.display = 'none';
                noteContainer.style.display = 'none';
            }
        });
    }
});
</script>

<!-- CSS إضافي لضمان عمل Select2 بشكل صحيح -->
<style>
.select2-container {
    z-index: 9999 !important;
}

.select2-dropdown {
    z-index: 9999 !important;
}

.select2-container--open {
    z-index: 9999 !important;
}

.select2-container--default .select2-selection--single {
    height: 38px !important;
    line-height: 36px !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    padding-left: 12px !important;
    padding-right: 20px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px !important;
}

/* تأكد من أن المودال له z-index مناسب */
.modal {
    z-index: 1050;
}

.modal-backdrop {
    z-index: 1040;
}
</style>
@endsection