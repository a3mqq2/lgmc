@extends('layouts.' . get_area_name())
@section('title', 'تفاصيل الطبيب')
@section('content')
<div class="row">


<div class="col-md-12 mb-3">
    <h2>{{$doctor->name}}</h2>
</div>


@if ($doctor->membership_status->value == "banned")
    <div class="col-12 mb-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">
                <i class="fa fa-ban"></i> هذا الطبيب محظور
            </h5>
            @if($doctor->ban_reason)
                <p class="mb-0">
                    <strong>سبب الحظر:</strong> {{ $doctor->ban_reason }}
                </p>
            @endif
            @if($doctor->banned_at)
                <hr>
                <p class="mb-0 text-muted">
                    <small>
                        <i class="fa fa-calendar"></i> 
                        تاريخ الحظر: {{ \Carbon\Carbon::parse($doctor->banned_at)->format('Y-m-d H:i') }}
                    </small>
                </p>
            @endif
        </div>
    </div>
    @endif


    @if ($doctor->membership_status->value == "expired" &&
        $doctor->invoices()->where('category','registration')->where('status','unpaid')->count() == 0
    
    && (
        ($doctor->type->value == "libyan" && get_area_name() == "user") || 
        (get_area_name() == "admin" && in_array($doctor->type->value,['palestinian','foreign']))
    ))
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="main-content-label">الإجراءات</h4>
                
                {{-- تنبيه انتهاء العضوية --}}
                <div class="alert alert-warning mb-3">
                    <h5 class="alert-heading">
                        <i class="fa fa-exclamation-triangle"></i> عضوية منتهية الصلاحية
                    </h5>
                    <p class="mb-0">
                        <strong>تاريخ انتهاء العضوية:</strong> 
                        {{ $doctor->membership_expiration_date ? date('Y-m-d', strtotime($doctor->membership_expiration_date)) : 'غير محدد' }}
                    </p>
                </div>
                
                {{-- زر تجديد العضوية --}}
                <button type="button" class="btn btn-success text-light" data-bs-toggle="modal" data-bs-target="#renewMembershipModal">
                    <i class="fa fa-refresh"></i> تجديد العضوية
                </button>
            </div>
        </div>
    </div>
    @endif

@if ($doctor->membership_status->value != "under_approve" && $doctor->membership_status->value != "under_edit"  && $doctor->membership_status->value != "banned" && $doctor->membership_status->value != "expired")
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="main-content-label">الإجراءات</h4>

            @if ($doctor->transfers->where('status','pending')->count() == 0 && $doctor->branch_id != null && get_area_name() == "user")
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
            
                <button type="button" class="btn {{ $doctor->membership_status->value === 'banned' ? 'btn-success' : 'btn-danger' }}" data-bs-toggle="modal" data-bs-target="#toggleBanDoctorModal">
                    <i class="fa {{ $doctor->membership_status->value === 'banned' ? 'fa-lock-open' : 'fa-lock' }}"></i>
                    {{ $doctor->membership_status->value === 'banned' ? 'رفع الحظر' : 'حظر الطبيب' }}
                </button>

                
                {{-- استبدل القسم الخاص بمودال حظر الطبيب بهذا الكود --}}
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

                                    {{-- ban reason - تم تصحيح الشرط ليظهر عند الحظر وليس رفع الحظر --}}
                                    @if ($doctor->membership_status->value != 'banned')
                                        <div class="mt-3">
                                            <label for="ban_reason" class="form-label">
                                                سبب الحظر <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="ban_reason" 
                                                    id="ban_reason" 
                                                    class="form-control" 
                                                    rows="3" 
                                                    placeholder="يرجى كتابة سبب الحظر بالتفصيل..."
                                                    required>{{ old('ban_reason') }}</textarea>
                                            <div class="form-text text-muted">
                                                <i class="fa fa-info-circle"></i> 
                                                يجب توضيح سبب الحظر بشكل واضح ومفصل
                                            </div>
                                        </div>
                                    @endif
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
                

            

            @if (($doctor->type->value == "libyan" && get_area_name() == "user") || ($doctor->type->value == "foreign" || $doctor->type->value == "palestinian") && get_area_name() == "admin")
            <a href="{{ route(get_area_name().'.doctors.edit',$doctor) }}" class="btn btn-info text-light">تعديل <i class="fa fa-edit"></i></a>
            <a href="{{ route(get_area_name().'.doctors.print',$doctor) }}" class="btn btn-secondary text-light">طباعة بيانات الطبيب <i class="fa fa-print"></i></a>
            <a href="{{ route(get_area_name().'.doctors.print-id',$doctor) }}" class="btn btn-primary text-light"> طباعة  البطاقة  <i class="fa fa-code"></i></a>
            @endif

        


            @if (($doctor->type->value == "libyan" && get_area_name() == "user"))
                <a href="{{ route(get_area_name().'.doctors.print-membership-form', $doctor) }}" 
                class="btn btn-info text-light" 
                target="_blank">
                    <i class="fa fa-file-pdf"></i> طباعة نموذج الانتساب
                </a>
            @endif
        </div>
    </div>
</div>
@endif




@if ($doctor->membership_status->value == "banned")
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="main-content-label">الإجراءات</h4>

            

            @if (get_area_name() == "admin")
            
                <button type="button" class="btn {{ $doctor->membership_status->value === 'banned' ? 'btn-success' : 'btn-danger' }}" data-bs-toggle="modal" data-bs-target="#toggleBanDoctorModal">
                    <i class="fa {{ $doctor->membership_status->value === 'banned' ? 'fa-lock-open' : 'fa-lock' }}"></i>
                    {{ $doctor->membership_status->value === 'banned' ? 'رفع الحظر' : 'حظر الطبيب' }}
                </button>

                
                {{-- استبدل القسم الخاص بمودال حظر الطبيب بهذا الكود --}}
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

                                    {{-- ban reason - تم تصحيح الشرط ليظهر عند الحظر وليس رفع الحظر --}}
                                    @if ($doctor->membership_status->value != 'banned')
                                        <div class="mt-3">
                                            <label for="ban_reason" class="form-label">
                                                سبب الحظر <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="ban_reason" 
                                                    id="ban_reason" 
                                                    class="form-control" 
                                                    rows="3" 
                                                    placeholder="يرجى كتابة سبب الحظر بالتفصيل..."
                                                    required>{{ old('ban_reason') }}</textarea>
                                            <div class="form-text text-muted">
                                                <i class="fa fa-info-circle"></i> 
                                                يجب توضيح سبب الحظر بشكل واضح ومفصل
                                            </div>
                                        </div>
                                    @endif
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
                

        

        

        </div>
    </div>
</div>
@endif


{{-- زر الحذف للأطباء تحت الموافقة --}}
@if ($doctor->membership_status->value == "under_approve" && ((get_area_name() == "user" && $doctor->type->value == "libyan")  || (get_area_name() == "user" && in_array($doctor->type->value,['palestinian','foreign'])) ))
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="main-content-label">الإجراءات</h4>
            
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDoctorModal{{ $doctor->id }}">
                <i class="fa fa-trash"></i> حذف الطبيب
            </button>

            <!-- مودال تأكيد الحذف -->
            <div class="modal fade" id="deleteDoctorModal{{ $doctor->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route(get_area_name().'.doctors.destroy', $doctor) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="fa fa-exclamation-triangle"></i> تأكيد حذف الطبيب
                                </h5>
                                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">
                                    <i class="fa fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                                    <h5>هل أنت متأكد من حذف هذا الطبيب؟</h5>
                                    <p class="text-muted">
                                        سيتم حذف الطبيب: <strong>{{ $doctor->name }}</strong>
                                        <br>
                                        <small class="text-warning">هذا الإجراء لا يمكن التراجع عنه!</small>
                                    </p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fa fa-times"></i> إلغاء
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa fa-trash"></i> تأكيد الحذف
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="col-12">
    <div class="card">
        <div class="card-body">
            @php  $user = auth()->user();  @endphp

            <ul class="nav nav-tabs" id="doctorTab" role="tablist">
                {{-- البيانات الشخصية --}}
                <li class="nav-item">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab"
                            data-bs-target="#personal" type="button">
                        البيانات الشخصية
                    </button>
                </li>
            
                {{-- مستندات الطبيب --}}
                @if($user->permissions()->where('name', 'doctor-finance-view')->count())
                    <li class="nav-item">
                        <button class="nav-link" id="docs-tab" data-bs-toggle="tab"
                                data-bs-target="#docs" type="button">
                            مستندات الطبيب
                        </button>
                    </li>
                @endif
            
                {{-- الملف المالي --}}
                @if(
                    $doctor->membership_status->value !== 'under_approve' &&
                    $doctor->type->value !== 'visitor' &&
                    $user->permissions()->where('name', 'doctor-finance-view')->count()
                )
                    <li class="nav-item">
                        <button class="nav-link" id="finance-tab" data-bs-toggle="tab"
                                data-bs-target="#finance" type="button">
                            الملف المالي
                        </button>
                    </li>
                @endif
            
                {{-- أذونات المزاولة --}}
                @if($user->permissions()->where('name', 'practice-permit-manage')->count())
                    <li class="nav-item">
                        <button class="nav-link" id="licenses-tab" data-bs-toggle="tab"
                                data-bs-target="#licenses" type="button">
                            أذونات المزاولة
                        </button>
                    </li>
                @endif
            
                {{-- طلبات الأوراق الخارجية --}}
                @if(
                    $doctor->membership_status->value != 'under_approve' &&
                    $doctor->type->value !== 'visitor' &&
                    $user->permissions()->where('name', 'doctor-mails')->count()
                )
                    <li class="nav-item">
                        <button class="nav-link" id="external-tab" data-bs-toggle="tab"
                                data-bs-target="#external" type="button">
                            طلبات الأوراق الخارجية
                        </button>
                    </li>
                @endif
            
                {{-- المنشأة الطبية --}}
                @if(
                    $doctor->medicalFacility &&
                    (
                    $user->permissions()->where('name', 'manage-medical-facilities-branch')->count()
                    ||
                    $user->permissions()->where('name', 'manage-medical-facilities',)->count()

                    )
                )
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#tab-medical-facility" type="button">
                            <i class="fas fa-hospital me-1"></i> المنشأة الطبية
                        </button>
                    </li>
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
                                    <tr>
                                        <th class="bg-light text-primary"> الجنسية </th>
                                        <td>
                                            {{$doctor->country?->nationality_name_ar}}
                                        </td>
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
                                            {{$doctor->marital_status->value == "signle" ? 'اعزب' : 'متزوج'}}
                                        </td>
                                    </tr>
                                    @endif

                                    @if ($doctor->gender)
                                    <tr>
                                        <th class="bg-light text-primary">  الجنس   </th>
                                        <td>
                                            {{$doctor->gender->value == "male" ? 'ذكر' : 'انثى'}}
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
                             
                                    @if ($doctor->type->value != "visitor")
                                    <tr>
                                        <th class="bg-light text-primary">رقم الهاتف</th>
                                        <td>{{ $doctor->phone }}</td>
                                    </tr>
                                    @endif

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
                            @php($img=$doctor->photo)
                            @if($img)
                                <img src="{{ $img }}" class="img-thumbnail" style="max-width:100%;max-height:300px;">
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
                                            <td>
                                                @if ($doctor->type->value == "libyan")
                                                    {{date('Y', strtotime($doctor->graduation_date))}}
                                                    @else 
                                                    {{ $doctor->graduation_date }}
                                                @endif
                                            </td>
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
                                            <td>
                                                @if ($doctor->type->value == "libyan")
                                                    {{date('Y', strtotime($doctor->internership_complete) )}}
                                                    @else 
                                                    {{date('Y-m-d', strtotime($doctor->internership_complete) )}}
                                                @endif
                                            </td>
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

                                                @if ($doctor->type->value == "libyan")
                                                {{ date('Y', strtotime($doctor->certificate_of_excellence_date) ) }}
                                                    @else 
                                                {{ $doctor->certificate_of_excellence_date }}
                                                @endif

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @endif

                    </div>


                    @if (($doctor->membership_status->value != "under_approve" && $doctor->membership_status->value != "under_edit"))
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
                

                {{-- تاب المستندات المحدث --}}
                <div class="tab-pane fade" id="docs" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">مستندات الطبيب</h5>
                    <div>
                    

                        @if ((get_area_name() == "user" && $doctor->type->value == "libyan" || get_area_name() == "admin" && in_array($doctor->type->value,['foreign','palestinian'])) && !in_array($doctor->membership_status->value,['banned']))
                        @if($doctor->files->count() > 0)
                        <a href="{{ route(get_area_name().'.doctors.download-all-files', $doctor) }}" 
                            class="btn btn-success text-light me-2"
                            id="downloadAllFilesBtn">
                            <i class="fa fa-download"></i> تحميل جميع المستندات
                        </a>
                        @endif
                        <a href="{{ route(get_area_name().'.doctors.files.create',$doctor) }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> إضافة مستند للطبيب
                        </a>
                        @endif
                        
                        
                    </div>
                </div>


                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="bg-light text-primary">#</th>
                                <th class="bg-light text-primary">العرض</th>
                                <th class="bg-light text-primary">اسم الملف</th>
                                <th class="bg-light text-primary">تحميل</th>
                                <th class="bg-light text-primary">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctor->files->sortBy('sort_order') as $file)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><img src="{{ Storage::url($file->file_path) }}" class="img-thumbnail" style="width:50px;height:50px;object-fit:cover;"></td>
                                    <td>{{ $file->file_name }}</td>
                                    <td>
                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                                        <a download href="{{ Storage::url($file->file_path) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-download"></i></a>
                                    </td>
                                    @if (get_area_name() == "user" && $doctor->type->value == "libyan" && !in_array($doctor->membership_status->value,['banned','under_approve','under_edit']))
                                        @if (get_area_name() == "admin" && $doctor->type->value != "libyan" || get_area_name() == "user" && $doctor->type->value == "libyan")
                                        <td>
                                            <a href="{{ route(get_area_name().'.doctors.files.edit',['doctor'=>$doctor->id,'file'=>$file->id]) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
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

            {{-- تاب المالي المحدث --}}
            <div class="tab-pane fade" id="finance" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"> الملف المالي للطبيب</h5>

                    @if (($doctor->type->value == "libyan" && get_area_name() == "user") || (get_area_name() == "admin" && in_array($doctor->type->value,['palestinian','foreign']) ))
                    <button type="button" class="btn btn-success text-light" data-bs-toggle="modal" data-bs-target="#addManualDuesModal">
                        <i class="fa fa-plus"></i> إضافة مستحقات يدوية
                    </button>
                    @endif

                    
                </div>

                {{-- مودال إضافة مستحقات يدوية --}}
                <div class="modal fade" id="addManualDuesModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action="{{ route(get_area_name().'.invoices.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                                
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fa fa-plus me-2"></i>
                                        إضافة مستحقات يدوية
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                
                                <div class="modal-body">
                                    {{-- وصف الفاتورة --}}
                                    <div class="mb-3">
                                        <label for="modalDescription" class="form-label">
                                            <i class="fa fa-file-text me-1"></i>
                                            وصف الفاتورة <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="description" 
                                                id="modalDescription" 
                                                class="form-control" 
                                                rows="3" 
                                                placeholder="أدخل وصف تفصيلي للفاتورة..." 
                                                required></textarea>
                                    </div>

                                    {{-- Switch لحساب اشتراكات سابقة --}}
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="modalPreviousSwitch">
                                        <label class="form-check-label" for="modalPreviousSwitch">
                                            <i class="fa fa-history me-1"></i>
                                            حساب اشتراكات سابقة
                                        </label>
                                    </div>

                                    {{-- جدول الصفات والسنوات --}}
                                    <div id="modalRankTableContainer" style="display:none" class="mb-3">
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle me-1"></i>
                                            قم بإضافة الصفات والسنوات المطلوب حساب الاشتراكات لها
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="30%">الصفة</th>
                                                        <th width="25%">من سنة</th>
                                                        <th width="25%">إلى سنة</th>
                                                        <th width="20%">إجراء</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="modalRankTableBody"></tbody>
                                            </table>
                                        </div>
                                        <button type="button" id="modalAddRowBtn" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-plus me-1"></i> إضافة بند
                                        </button>
                                    </div>

                                    {{-- قيمة الفاتورة --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="modalAmount" class="form-label">
                                                <i class="fa fa-money-bill me-1"></i>
                                                قيمة الفاتورة (د.ل) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                    step="0.01" 
                                                    name="amount" 
                                                    id="modalAmount" 
                                                    class="form-control" 
                                                    placeholder="0.00"
                                                    required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">معاينة الإجمالي</label>
                                            <div class="form-control bg-light" id="modalTotalPreview">0.00 د.ل</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fa fa-times"></i> إلغاء
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> إنشاء الفاتورة
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Template للصفوف الجديدة --}}
                <div id="modalRowTemplate" style="display: none">
                    <table>
                        <tr>
                            <td>
                                <select name="ranks[]" class="form-control form-control-sm">
                                    <option value="">-- اختر الصفة --</option>
                                    @foreach(App\Models\DoctorRank::where('doctor_type', $doctor->type->value)->get() as $rank)
                                        <option value="{{ $rank->id }}" data-price="{{ App\Models\Pricing::where('type','membership')->where('doctor_type', $doctor->type->value)->where('doctor_rank_id', $rank->id)->first()->amount }}">{{ $rank->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" 
                                        name="from_years[]" 
                                        min="1900" 
                                        max="2100" 
                                        class="form-control form-control-sm"
                                        placeholder="2020">
                            </td>
                            <td>
                                <input type="number" 
                                        name="to_years[]" 
                                        min="1900" 
                                        max="2100" 
                                        class="form-control form-control-sm"
                                        placeholder="2024">
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger modal-remove-row">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </table>
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
                                <th>اعدادات</th>
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
                                        <a href="{{ route(get_area_name().'.invoices.print', $invoice->id) }}" class="btn btn-sm btn-secondary">
                                            طباعة
                                        </a>
                                        @if ($invoice->status->value == "unpaid" && auth()->user()->vault)
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#received_{{$invoice->id}}">
                                            استلام القيمة <i class="fa fa-check"></i>
                                        </button>
                                        {{-- مودال استلام القيمة --}}
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
                                    <td colspan="8" class="text-center">لا توجد فواتير متاحة.</td>
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

                    @if (($doctor->type->value == "libyan" && get_area_name() == "user") || ($doctor->type->value == "foreign" || $doctor->type->value == "palestinian") && get_area_name() == "admin")
                    <button type="button" class="btn btn-success text-light mb-3" data-bs-toggle="modal" data-bs-target="#addPracticeLicenseModal">
                        <i class="fa fa-plus"></i> إضافة إذن مزاولة جديد
                    </button>

                    @endif

                
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
                                                    <i class="fa fa-user-tie"></i> صفة الطبيب 
                                                </label>
                                                <select name="doctor_rank_id" class="form-control" required disabled>
                                                    <option value="{{ $doctor->doctor_rank_id }}" >
                                                        {{$doctor->doctor_rank->name}}
                                                    </option>
                                                </select>

                                                <input type="hidden" name="doctor_rank_id" value="{{$doctor->doctor_rank_id}}">
                                            </div>

                                            <!-- التخصص -->
                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="fa fa-stethoscope"></i> التخصص
                                                </label>
                                                <select name="specialty_id" class="form-control" readonly disabled>
                                                    <option value="{{$doctor->specialty1?->id}}">{{$doctor->specialty1?->name}}</option>
                                                </select>

                                                <input type="hidden" name="specialty_id" value="{{$doctor->specialty1?->id}}">
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
                                                    required
                                                    readonly>
                                            </div>


                                            <!-- المنشأة الطبية -->
                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="fa fa-calendar"></i> المنشأة الطبية <span class="text-danger">*</span>
                                                </label>
                                                
                                                <select name="medical_facility_id" 
                                                        id="medicalFacilityModalSelect" 
                                                        class="form-control select2" 
                                                        required
                                                        >
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
                                            <a href="{{ route(get_area_name().'.licences.print', $license) }}" class="btn btn-secondary btn-sm text-light">
                                                طباعه <i class="fa fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">لا يوجد هناك اي اذونات </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>


                @if($doctor->medicalFacility)
                <div class="tab-pane fade" id="tab-medical-facility">
                    <div class="enhanced-card fade-in-up my-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 text-white">
                                    <i class="fas fa-hospital-alt me-2"></i>معلومات المنشأة الطبية المرتبطة
                                </h5>
                                <small class="text-white-50">تفاصيل المنشأة الطبية التي يديرها الطبيب  </small>
                            </div>
                            @if(isset($doctor->medicalFacility->membership_status))
                                <span class="badge bg-white text-primary fs-6 px-3 py-2">
                                    <i class="fas fa-circle-check me-1"></i>
                                    {{ $doctor->medicalFacility->membership_status->label() }}
                                </span>
                            @endif
                        </div>
                
                        <div class="card-body p-0">
                            {{-- قسم المعلومات الأساسية --}}
                            <div class="facility-section">
                                
                                <div class="info-grid">
                                    {{-- اسم المنشأة --}}
                                    <div class="info-card">
                                        <div class="info-icon bg-primary">
                                            <i class="fas fa-clinic-medical"></i>
                                        </div>
                                        <div class="info-content">
                                            <label class="info-label">اسم المنشأة</label>
                                            <div class="info-value">{{ $doctor->medicalFacility->name }}</div>
                                        </div>
                                    </div>
                
                                    {{-- نوع المنشأة --}}
                                    <div class="info-card">
                                        <div class="info-icon bg-info">
                                            <i class="fas fa-tags"></i>
                                        </div>
                                        <div class="info-content">
                                            <label class="info-label">نوع المنشأة</label>
                                            <div class="info-value">
                                                @if($doctor->medicalFacility->type == 'private_clinic')
                                                    <span class="facility-type-badge private-clinic">
                                                        <i class="fas fa-stethoscope me-1"></i>عيادة خاصة
                                                    </span>
                                                @elseif($doctor->medicalFacility->type == 'medical_services')
                                                    <span class="facility-type-badge medical-services">
                                                        <i class="fas fa-briefcase-medical me-1"></i>خدمات طبية
                                                    </span>
                                                @else
                                                    {{ $doctor->medicalFacility->type }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            {{-- قسم المعلومات التجارية --}}
                            @if($doctor->medicalFacility->commercial_number)
                            <div class="facility-section">
                                
                                <div class="info-grid">
                                    <div class="info-card">
                                        <div class="info-icon bg-success">
                                            <i class="fas fa-file-contract"></i>
                                        </div>
                                        <div class="info-content">
                                            <label class="info-label">السجل التجاري</label>
                                            <div class="info-value">{{ $doctor->medicalFacility->commercial_number }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                
                            {{-- قسم معلومات الاتصال --}}
                            @if($doctor->medicalFacility->address || $doctor->medicalFacility->phone_number)
                            <div class="facility-section">
                                <div class="info-grid">
                                    @if($doctor->medicalFacility->address)
                                    <div class="info-card">
                                        <div class="info-icon bg-danger">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="info-content">
                                            <label class="info-label">الموقع</label>
                                            <div class="info-value">{{ $doctor->medicalFacility->address }}</div>
                                        </div>
                                    </div>
                                    @endif
                
                                    @if($doctor->medicalFacility->phone_number)
                                    <div class="info-card">
                                        <div class="info-icon bg-success">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <div class="info-content">
                                            <label class="info-label">رقم الهاتف</label>
                                            <div class="info-value">
                                                <a href="tel:{{ $doctor->medicalFacility->phone_number }}" 
                                                    class="contact-link">
                                                    {{ $doctor->medicalFacility->phone_number }}
                                                    <i class="fas fa-external-link-alt ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                
                        </div>
                    </div>
                </div>
                @endif
                @if(auth()->user()->permissions()->where('name','doctor-mails')->count())
                    <div class="tab-pane fade" id="external" role="tabpanel">
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th><th>الطبيب</th>
                                        <th>الإجمالي</th><th>الحالة</th><th>تاريخ الإنشاء</th><th>إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($doctor->doctor_mails as $mail)
                                        <tr>
                                            <td>{{ $mail->id }}</td>
                                            <td><strong>{{ $mail->doctor->name }}</strong><br><small>{{ $mail->doctor->code }}</small></td>
                                            <td>{{ number_format($mail->grand_total,2) }} د.ل</td>
                                            <td><span class="badge {{ ['under_approve'=>'bg-warning','under_payment'=>'bg-info','under_proccess'=>'bg-primary','done'=>'bg-success','failed'=>'bg-danger','under_edit'=>'bg-secondary'][$mail->status] ?? 'bg-secondary' }}">{{ ['under_approve'=>'قيد الموافقة','under_payment'=>'قيد الدفع','under_proccess'=>'قيد التجهيز','done'=>'مكتمل','failed'=>'فشل','under_edit'=>'قيد التعديل'][$mail->status] ?? 'غير معروف' }}</span></td>
                                            <td>{{ $mail->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route(get_area_name().'.doctor-mails.show',$mail) }}" class="btn btn-outline-info"><i class="fa fa-eye"></i></a>
                                                </div>
                                                
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
        <form action="{{ route(get_area_name().'.doctors.change-status', $doctor) }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        <i class="fa fa-check-circle me-2"></i>الموافقة على الطبيب
                    </h5>
                </div>
                <div class="card-body">
                    {{-- اختيار الحالة --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fa fa-flag me-1"></i>حدد الحالة <span class="text-danger">*</span>
                        </label>
                        <select name="final_status" id="final_status" class="form-control form-control-lg" required>
                            <option value="">-- اختر الحالة --</option>
                            <option value="under_edit">تحويل إلى قيد التعديل</option>
                            <option value="approved">موافقة على العضوية</option>
                        </select>
                    </div>
    
                    {{-- حقل السبب (للتعديل) --}}
                    <div id="edit_note_container" class="animate-fade" style="display: none;">
                        <div class="alert alert-warning">
                            <i class="fa fa-info-circle me-1"></i>
                            يرجى كتابة سبب التحويل إلى قيد التعديل
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fa fa-comment me-1"></i>السبب <span class="text-danger">*</span>
                            </label>
                            <textarea name="edit_note" 
                                        class="form-control" 
                                        rows="3" 
                                        placeholder="اكتب السبب بالتفصيل..."></textarea>
                        </div>
                    </div>
    
                    {{-- حقول الموافقة --}}
                    <div id="approve_fields_container" class="animate-fade" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-1"></i>
                            يجب عليك التأكد من هذه البيانات قبل الموافقة
                        </div>
                        
                        <div class="row g-3">
                            {{-- حدد الصفة --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fa fa-user-tie me-1"></i>حدد الصفة <span class="text-danger">*</span>
                                </label>
                                <select name="doctor_rank_id" class="form-control select2 selectize">
                                    <option value="">-- اختر الصفة --</option>
                                    @foreach ($doctor_ranks as $doctor_rank)
                                        <option value="{{ $doctor_rank->id }}" 
                                                {{ $doctor->doctor_rank_id == $doctor_rank->id ? 'selected' : '' }}>
                                            {{ $doctor_rank->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
    
                            {{-- حدد تخصص --}}
                            <div class="{{$doctor->type->value == "visitor" ? "col-md-3" : "col-md-6"}}">
                                <label class="form-label fw-bold">
                                    <i class="fa fa-stethoscope me-1"></i>حدد تخصص 
                                    @if ($doctor->type->value != "visitor")
                                    (ان وجد)
                                    @endif
                                </label>
                                <select name="specialty_1_id" class="form-control select2 selectize" 
                                    @if ($doctor->type->value == "visitor")
                                        required
                                    @endif
                                >
                                    <option value="">-- اختر تخصص --</option>
                                    @foreach ($specialties as $item)
                                        <option value="{{ $item->id }}" 
                                                {{ $doctor->specialty_1_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($doctor->type->value == "visitor")
                            <div class="col-md-3 mb-3">
                                <label>تخصص دقيق (إن وجد)</label>
                                <input type="text" name="specialty_2" value="{{ old('specialty_2', $doctor->specialty_2) }}" class="form-control" autocomplete="off">
                            </div>
                            @endif
    
                            {{-- تاريخ الانتساب --}}
                            @if ($doctor->type->value == "libyan")
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fa fa-calendar me-1"></i>تاريخ الانتساب
                                </label>
                                <input type="date" 
                                        name="registered_at" 
                                        value="{{ $doctor->internership_complete ? Carbon\Carbon::parse($doctor->internership_complete)->addDay()->format('Y-m-d') : '' }}" 
                                        class="form-control">
                            </div>
                            @endif
    
                            {{-- الجهة العامة/المستشفى --}}
                            
                            @if ($doctor->type->value != "visitor")
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fa fa-hospital me-1"></i>الجهة العامة/المستشفى
                                </label>
                                <select name="institution_id" class="form-control select2">
                                    <option value="">-- حدد مستشفى --</option>
                                    @foreach ($institutions as $institution)
                                        <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif


                            @if ($doctor->type->value == "visitor")
                            <div class="col-md-6 mb-3">
                                <label class="required-field">تاريخ الزيارة من</label>
                                <input type="date" required name="visit_from" value="{{old('visit_from', $doctor->visit_from )}}" class="form-control">
                            </div>
    
                            <div class="col-md-6 mb-3">
                                <label class="required-field">تاريخ الزيارة إلى</label>
                                <input type="date" required name="visit_to" value="{{old('visit_to', $doctor->visit_to)}}" class="form-control">
                            </div>
                            @endif

    
                            {{-- اعتبار الفاتورة مدفوعة --}}
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" 
                                            class="form-check-input" 
                                            name="is_paid" 
                                            id="is_paid" 
                                            value="1">
                                    <label class="form-check-label" for="is_paid">
                                        <i class="fa fa-money-bill me-1"></i>
                                        اعتبار الفاتورة مدفوعة
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    {{-- زر الحفظ --}}
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-4" id="submitBtn" disabled>
                            <i class="fa fa-save me-2"></i>حفظ
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Add this CSS to your styles section -->
    <style>
    .animate-fade {
        transition: all 0.3s ease-in-out;
    }
    
    #approve_fields_container,
    #edit_note_container {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin-top: 15px;
    }
    
    .form-control-lg {
        font-size: 1.1rem;
        padding: 10px 15px;
    }
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    </style>
    
    <!-- Add this script at the end of your scripts section -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('final_status');
        const noteContainer = document.getElementById('edit_note_container');
        const fieldsContainer = document.getElementById('approve_fields_container');
        const submitBtn = document.getElementById('submitBtn');
        const editNoteTextarea = document.querySelector('textarea[name="edit_note"]');
    
        function handleStatusChange() {
            const selectedValue = statusSelect.value;
            
            // Enable submit button if a value is selected
            submitBtn.disabled = !selectedValue;
    
            if (selectedValue === 'approved') {
                // Show approval fields
                fieldsContainer.style.display = 'block';
                noteContainer.style.display = 'none';
                
                // Remove required from edit note
                if (editNoteTextarea) {
                    editNoteTextarea.removeAttribute('required');
                }
                
                // Add required to important approval fields
                const rankSelect = document.querySelector('select[name="doctor_rank_id"]');
                if (rankSelect) {
                    rankSelect.setAttribute('required', 'required');
                }
            } else if (selectedValue === 'under_edit') {
                // Show edit note
                fieldsContainer.style.display = 'none';
                noteContainer.style.display = 'block';
                
                // Add required to edit note
                if (editNoteTextarea) {
                    editNoteTextarea.setAttribute('required', 'required');
                }
                
                // Remove required from approval fields
                const approvalFields = fieldsContainer.querySelectorAll('[required]');
                approvalFields.forEach(field => {
                    field.removeAttribute('required');
                });
            } else {
                // Hide both
                fieldsContainer.style.display = 'none';
                noteContainer.style.display = 'none';
            }
        }
    
        // Initialize
        if (statusSelect) {
            statusSelect.addEventListener('change', handleStatusChange);
            handleStatusChange(); // Set initial state
        }
    
        // Initialize Select2 for institution select
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2-approval').select2({
                placeholder: "حدد مستشفى",
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
    });
    </script>
    @endif
</div>

</div>
{{-- مودال تجديد العضوية - يوضع في نهاية الملف قبل @endsection --}}
<div class="modal fade" id="renewMembershipModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form action="{{ route(get_area_name().'.doctors.renew-membership', $doctor->id) }}" method="POST">
            @csrf
            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
            
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-center text-light">
                    <i class="fa fa-refresh me-2"></i>
                    تجديد عضوية الطبيب
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                {{-- معلومات الطبيب --}}
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-8">
                            <strong>الطبيب:</strong> {{ $doctor->name }}<br>
                            <strong>الكود:</strong> {{ $doctor->code }}<br>
                            <strong>الصفة الحالية:</strong> {{ $doctor->doctor_rank?->name ?? 'غير محددة' }}
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge bg-danger fs-6">
                                <i class="fa fa-clock"></i> منتهية الصلاحية
                            </span>
                        </div>
                    </div>
                </div>

                <p class="h3 text-center">هل انت متأكد من انك تريد التجديد ؟</p>
                <p class="text-center">سيتم انشاء فاتورة للطبيب بقيمة التجديد بشكل فوري </p>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> إلغاء
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-refresh"></i> تجديد العضوية وإنشاء الفاتورة
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

<!-- وفي نهاية الصفحة، استبدل قسم scripts بهذا الكود -->
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
// ***** كود إعادة ترتيب المستندات *****
let sortable;

// تفعيل إعادة الترتيب عند فتح المودال
document.getElementById('reorderFilesModal').addEventListener('shown.bs.modal', function () {
    const sortableList = document.getElementById('sortableFilesList');
    if (sortableList) {
        sortable = Sortable.create(sortableList, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            handle: '.drag-handle',
            onEnd: function (evt) {
                // تحديث أرقام الترتيب
                updateOrderNumbers();
            }
        });
    }
});

// تحديث أرقام الترتيب
function updateOrderNumbers() {
    const items = document.querySelectorAll('#sortableFilesList .file-item');
    items.forEach((item, index) => {
        const orderNumber = item.querySelector('.order-number');
        if (orderNumber) {
            orderNumber.textContent = index + 1;
        }
    });
}


// ***** كود مودال المستحقات اليدوية *****
const modalSwitchEl = document.getElementById('modalPreviousSwitch');
const modalContainer = document.getElementById('modalRankTableContainer');
const modalTbody = document.getElementById('modalRankTableBody');
const modalAddRowBtn = document.getElementById('modalAddRowBtn');
const modalRowTemplate = document.querySelector('#modalRowTemplate table tr');
const modalAmountInput = document.getElementById('modalAmount');
const modalTotalPreview = document.getElementById('modalTotalPreview');

function toggleModalContainer() {
    console.log('Switch toggled:', modalSwitchEl.checked); // سطر مهم
    modalContainer.style.display = modalSwitchEl.checked ? 'block' : 'none';
    modalAmountInput.readOnly = modalSwitchEl.checked;
    if (modalSwitchEl.checked) {
        calculateModalTotal();
    } else {
        modalTotalPreview.textContent = '0.00 د.ل';
    }
}

function calculateModalTotal() {
    let total = 0;
    modalTbody.querySelectorAll('tr').forEach(function (row) {
        const fromYear = parseInt(row.querySelector('input[name="from_years[]"]').value) || 0;
        const toYear = parseInt(row.querySelector('input[name="to_years[]"]').value) || 0;
        const rankSelect = row.querySelector('select[name="ranks[]"]');
        const price = parseFloat(rankSelect?.selectedOptions[0]?.dataset?.price || 0);
        const years = toYear >= fromYear ? (toYear - fromYear + 1) : 0;
        total += years * price;
    });
    modalAmountInput.value = total.toFixed(2);
    modalTotalPreview.textContent = total.toFixed(2) + ' د.ل';
}

if (modalSwitchEl) {
    modalSwitchEl.addEventListener('change', toggleModalContainer);
}

if (modalAddRowBtn && modalRowTemplate) {
    modalAddRowBtn.addEventListener('click', function () {
        const newRow = modalRowTemplate.cloneNode(true);
        modalTbody.appendChild(newRow);
    });
}

modalTbody.addEventListener('click', function (e) {
    if (e.target.classList.contains('modal-remove-row') || e.target.closest('.modal-remove-row')) {
        e.target.closest('tr').remove();
        calculateModalTotal();
    }
});

document.addEventListener('input', function (e) {
    if (
        e.target.matches('input[name="from_years[]"]') ||
        e.target.matches('input[name="to_years[]"]') ||
        e.target.matches('select[name="ranks[]"]')
    ) {
        calculateModalTotal();
    }
});

// تطبيق الإعدادات الأولية
toggleModalContainer();

// ***** باقي الكود الأصلي *****
function initModalSelect2() {
    if ($('#medicalFacilityModalSelect').hasClass('select2-hidden-accessible')) {
        $('#medicalFacilityModalSelect').select2('destroy');
    }
    
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

$('#addPracticeLicenseModal').on('shown.bs.modal', function () {
    initModalSelect2();
});

$('#addPracticeLicenseModal').on('hidden.bs.modal', function () {
    if ($('#medicalFacilityModalSelect').hasClass('select2-hidden-accessible')) {
        $('#medicalFacilityModalSelect').select2('destroy');
    }
});

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

// دوال إظهار الرسائل
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="fa fa-check-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function showErrorMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="fa fa-exclamation-triangle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
});


// زر تحميل جميع المستندات
document.addEventListener('DOMContentLoaded', function() {
const downloadAllBtn = document.getElementById('downloadAllFilesBtn');
if (downloadAllBtn) {
    downloadAllBtn.addEventListener('click', function(e) {
        // تغيير النص أثناء التحميل
        const originalHTML = this.innerHTML;
        this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> جاري تجهيز الملفات...';
        this.classList.add('disabled');
        
        // إعادة النص الأصلي بعد 3 ثواني
        setTimeout(() => {
            this.innerHTML = originalHTML;
            this.classList.remove('disabled');
        }, 3000);
    });
}
});

// JavaScript لمودال تجديد العضوية - يضاف في قسم scripts
document.addEventListener('DOMContentLoaded', function() {
// عناصر مودال التجديد
const renewalModal = document.getElementById('renewMembershipModal');
const rankSelect = document.getElementById('renewalRankSelect');
const periodSelect = document.getElementById('renewalPeriodSelect');
const startDateInput = document.getElementById('renewalStartDate');
const endDateInput = document.getElementById('renewalEndDate');
const amountInput = document.getElementById('renewalAmount');
const totalAmountDiv = document.getElementById('renewalTotalAmount');
const submitBtn = document.getElementById('renewalSubmitBtn');

// حساب تاريخ انتهاء التجديد
function calculateEndDate() {
    const startDate = startDateInput.value;
    const period = parseInt(periodSelect.value);
    
    if (startDate && period) {
        const start = new Date(startDate);
        const end = new Date(start);
        end.setFullYear(start.getFullYear() + period);
        
        // تقليل يوم واحد للحصول على تاريخ الانتهاء الصحيح
        end.setDate(end.getDate() - 1);
        
        endDateInput.value = end.toISOString().split('T')[0];
    } else {
        endDateInput.value = '';
    }
}

// حساب قيمة التجديد
function calculateRenewalAmount() {
    const rankOption = rankSelect.options[rankSelect.selectedIndex];
    const period = parseInt(periodSelect.value);
    
    if (rankOption && rankOption.dataset.price && period) {
        const pricePerYear = parseFloat(rankOption.dataset.price);
        const totalAmount = pricePerYear * period;
        
        amountInput.value = totalAmount.toFixed(2);
        totalAmountDiv.textContent = totalAmount.toFixed(2) + ' د.ل';
        
        // تمكين زر الإرسال
        submitBtn.disabled = false;
    } else {
        amountInput.value = '';
        totalAmountDiv.textContent = '0.00 د.ل';
        submitBtn.disabled = true;
    }
}

// أحداث تغيير القيم
if (rankSelect) {
    rankSelect.addEventListener('change', calculateRenewalAmount);
}

if (periodSelect) {
    periodSelect.addEventListener('change', function() {
        calculateEndDate();
        calculateRenewalAmount();
    });
}

if (startDateInput) {
    startDateInput.addEventListener('change', calculateEndDate);
}

// إعادة تعيين المودال عند فتحه
if (renewalModal) {
    renewalModal.addEventListener('show.bs.modal', function() {
        // إعادة تعيين القيم
        if (periodSelect) periodSelect.value = '';
        if (startDateInput) startDateInput.value = new Date().toISOString().split('T')[0];
        if (endDateInput) endDateInput.value = '';
        if (amountInput) amountInput.value = '';
        if (totalAmountDiv) totalAmountDiv.textContent = '0.00 د.ل';
        if (submitBtn) submitBtn.disabled = true;
        
        // تنظيف الملاحظات والخيارات
        const notesTextarea = renewalModal.querySelector('textarea[name="renewal_notes"]');
        const paidCheckbox = renewalModal.querySelector('input[name="mark_as_paid"]');
        
        if (notesTextarea) notesTextarea.value = '';
        if (paidCheckbox) paidCheckbox.checked = false;
    });
}

// تأكيد الإرسال
if (submitBtn) {
    submitBtn.addEventListener('click', function(e) {
        const form = this.closest('form');
        const rankValue = rankSelect.value;
        const periodValue = periodSelect.value;
        const amountValue = amountInput.value;

        if (!rankValue || !periodValue || !amountValue) {
            e.preventDefault();
            alert('يرجى تعبئة جميع الحقول المطلوبة');
            return false;
        }

        // تأكيد العملية
        const confirmMessage = `هل أنت متأكد من تجديد عضوية الطبيب لمدة ${periodValue} سنة/سنوات بمبلغ ${amountValue} د.ل؟`;
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
            return false;
        }

        // تغيير نص الزر أثناء الإرسال
        this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> جاري التجديد...';
        this.disabled = true;
    });
}
});
</script>

<!-- CSS الأنماط -->
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

/* أنماط المودال */
.modal {
z-index: 1050;
}

.modal-backdrop {
z-index: 1040;
}

/* أنماط إعادة ترتيب المستندات */
.sortable-list {
min-height: 200px;
padding: 10px;
}

.file-item {
background: #fff;
border: 2px solid #e9ecef;
border-radius: 8px;
padding: 15px;
margin-bottom: 10px;
transition: all 0.3s ease;
cursor: grab;
position: relative;
box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.file-item:hover {
border-color: #007bff;
box-shadow: 0 4px 12px rgba(0,123,255,0.15);
transform: translateY(-2px);
}

.file-item.sortable-chosen {
transform: rotate(2deg);
box-shadow: 0 8px 25px rgba(0,0,0,0.2);
border-color: #28a745;
background: #f8fff9;
cursor: grabbing;
}

.file-item.sortable-ghost {
opacity: 0.3;
background: #007bff;
border-color: #007bff;
}

.file-item.sortable-drag {
transform: rotate(5deg);
box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.drag-handle {
position: absolute;
left: 10px;
top: 50%;
transform: translateY(-50%);
color: #6c757d;
font-size: 18px;
cursor: grab;
padding: 5px;
}

.drag-handle:hover {
color: #007bff;
}

.file-thumbnail {
width: 50px;
height: 50px;
object-fit: cover;
border-radius: 6px;
border: 2px solid #e9ecef;
flex-shrink: 0;
}

.file-info {
flex: 1;
margin-right: 15px;
}

.file-name {
font-weight: 600;
color: #2c3e50;
margin-bottom: 5px;
font-size: 0.95em;
}

.file-type {
color: #6c757d;
font-size: 0.85em;
}

.order-number {
position: absolute;
top: -8px;
right: -8px;
background: #007bff;
color: white;
width: 24px;
height: 24px;
border-radius: 50%;
display: flex;
align-items: center;
justify-content: center;
font-size: 12px;
font-weight: bold;
box-shadow: 0 2px 4px rgba(0,0,0,0.2);
z-index: 1;
}

.sortable-item {
padding-right: 45px;
}

.sortable-content {
padding-right: 10px;
}

/* تحسينات للمودال المالي */
#modalRankTableContainer .table {
margin-bottom: 0;
}

#modalRankTableContainer .table th {
background-color: #f8f9fa;
border-color: #dee2e6;
font-weight: 600;
font-size: 0.9em;
}

#modalRankTableContainer .table td {
vertical-align: middle;
padding: 8px;
}

#modalTotalPreview {
font-weight: bold;
color: #28a745;
text-align: center;
}

.form-control-sm {
font-size: 0.875rem;
}

/* تحسينات الاستجابة */
@media (max-width: 768px) {
.file-item {
    padding: 10px;
}

.file-thumbnail {
    width: 40px;
    height: 40px;
}

.drag-handle {
    font-size: 16px;
    left: 5px;
}

.order-number {
    width: 20px;
    height: 20px;
    font-size: 11px;
    top: -6px;
    right: -6px;
}

.sortable-item {
    padding-right: 35px;
}

.file-name {
    font-size: 0.9em;
}

.file-type {
    font-size: 0.8em;
}
}

/* تحسينات إضافية للمودال */
.modal-lg {
max-width: 900px;
}

.alert-info {
border-left: 4px solid #17a2b8;
}

.btn-outline-primary:hover {
background-color: #007bff;
border-color: #007bff;
}

/* تحسين مظهر الأزرار */
.btn-warning.text-light:hover {
background-color: #e0a800;
border-color: #d39e00;
}

.btn-success.text-light:hover {
background-color: #218838;
border-color: #1e7e34;
}
</style>
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

/* تحسين زر التحميل */
.btn-success.text-light {
background-color: #28a745;
border-color: #28a745;
transition: all 0.3s ease;
}

.btn-success.text-light:hover {
background-color: #218838;
border-color: #1e7e34;
transform: translateY(-1px);
box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-success.text-light:active {
transform: translateY(0);
box-shadow: none;
}

.btn-success.text-light.disabled {
opacity: 0.7;
cursor: not-allowed;
}

/* تحسين مظهر الأزرار في قسم المستندات */
.tab-pane#docs .btn {
margin-left: 5px;
}

@media (max-width: 768px) {
.tab-pane#docs .d-flex {
    flex-direction: column;
    align-items: stretch !important;
}

.tab-pane#docs .btn {
    margin: 5px 0;
    width: 100%;
}
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalSwitchEl = document.getElementById('modalPreviousSwitch');
    const modalContainer = document.getElementById('modalRankTableContainer');
    const modalAmountInput = document.getElementById('modalAmount');
    const modalTotalPreview = document.getElementById('modalTotalPreview');
    const modalRowTemplate = document.getElementById('modalRowTemplate');
    const modalAddRowBtn = document.getElementById('modalAddRowBtn');
    const modalRankTableBody = document.getElementById('modalRankTableBody');

    function toggleModalContainer() {
        modalContainer.style.display = modalSwitchEl.checked ? 'block' : 'none';
        modalAmountInput.readOnly = modalSwitchEl.checked;
        if (modalSwitchEl.checked) {
            calculateModalTotal();
        } else {
            modalTotalPreview.textContent = '0.00 د.ل';
        }
    }

    function calculateModalTotal() {
        let total = 0;
        document.querySelectorAll('#modalRankTableContainer tbody tr').forEach(row => {
            const fromYear = parseInt(row.querySelector('input[name="from_years[]"]').value) || 0;
            const toYear = parseInt(row.querySelector('input[name="to_years[]"]').value) || 0;
            const rankSelect = row.querySelector('select[name="ranks[]"]');
            const price = parseFloat(rankSelect.options[rankSelect.selectedIndex]?.dataset.price || 0);
            const years = toYear - fromYear + 1;
            total += price * (years > 0 ? years : 0);
        });

        modalTotalPreview.textContent = total.toFixed(2) + ' د.ل';
    }

    if (modalSwitchEl) {
        modalSwitchEl.addEventListener('change', toggleModalContainer);
    }

    modalAddRowBtn.addEventListener('click', function () {
        const clone = modalRowTemplate.querySelector('tr').cloneNode(true);
        modalRankTableBody.appendChild(clone);
        calculateModalTotal();
    });

    document.addEventListener('input', function (e) {
        if (
            e.target.matches('input[name="from_years[]"]') ||
            e.target.matches('input[name="to_years[]"]') ||
            e.target.matches('select[name="ranks[]"]')
        ) {
            calculateModalTotal();
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.modal-remove-row')) {
            e.target.closest('tr').remove();
            calculateModalTotal();
        }
    });

    // Reset modal when opened
    const modal = document.getElementById('addManualDuesModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function () {
            modalSwitchEl.checked = false;
            toggleModalContainer();
            modalRankTableBody.innerHTML = '';
            modalTotalPreview.textContent = '0.00 د.ل';
            modalAmountInput.readOnly = false;
        });
    }
});
</script>
<style>
    /* تحسين أنماط المنشأة الطبية */
    .facility-section {
        border-bottom: 1px solid #f1f5f9;
        padding: 2rem 1.5rem;
    }
    
    .facility-section:last-child {
        border-bottom: none;
    }
    
    .section-header {
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        color: #1f2937;
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .info-card {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.25rem;
        background: linear-gradient(135deg, #fefefe 0%, #f8fafc 100%);
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
    }
    
    .info-card:hover::before {
        opacity: 1;
    }
    
    .info-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .info-icon.bg-purple {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }
    
    .info-content {
        flex: 1;
        min-width: 0;
    }
    
    .info-label {
        display: block;
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    
    .info-value {
        color: #1e293b;
        font-weight: 600;
        font-size: 1rem;
        line-height: 1.4;
    }
    
    .facility-type-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .facility-type-badge.private-clinic {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }
    
    .facility-type-badge.medical-services {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
    }
    
    .contact-link {
        color: #059669;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .contact-link:hover {
        color: #047857;
        text-decoration: underline;
    }
    
    .manager-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .manager-name {
        font-weight: 600;
        color: #1e293b;
    }
    
    .manager-code {
        color: #64748b;
        font-weight: 500;
        font-size: 0.8rem;
        background: #f1f5f9;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }
    
    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .action-btn.primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }
    
    .action-btn.primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        color: white;
    }
    
    .action-btn.warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .action-btn.warning:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        color: white;
    }
    
    .action-btn.success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .action-btn.success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        color: white;
    }
    
    /* تحسينات متجاوبة */
    @media (max-width: 768px) {
        .facility-section {
            padding: 1.5rem 1rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .info-card {
            padding: 1rem;
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }
        
        .quick-actions {
            flex-direction: column;
        }
        
        .action-btn {
            justify-content: center;
            padding: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .section-title {
            font-size: 1rem;
        }
        
        .info-value {
            font-size: 0.9rem;
        }
    }
    </style>
@endsection