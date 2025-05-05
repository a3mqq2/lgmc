@extends('layouts.' . get_area_name())
@section('title', 'قائمة الاطباء')

@section('content')
<div class="row">
    @if (get_area_name() != "finance")
    <div class="col-md-12">
        <a href="{{ route(get_area_name().'.doctors.create', ['type' => request('type')] ) }}" class="btn btn-success mb-2"><i class="fa fa-plus"></i> إنشاء جديد </a>
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
                            <label for="registered_at">تاريخ الانتساب</label>
                            <input type="date" lang="ar" dir="rtl" class="form-control date-picker" id="registered_at" name="registered_at" value="{{ request()->input('registered_at') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="registered_at"> حالة العضوية </label>
                            <select class="form-control" name="membership_status" id="membership_status">
                                <option value="">اختر حالة العضوية</option>
                                @foreach (\App\Enums\MembershipStatus::cases() as $status)
                                    <option value="{{ $status->value }}" {{ request()->input('membership_status') == $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="">حالة اخر اذن</label>
                            <select class="form-control" name="last_license_status" id="last_license_status">
                                <option value="">الكل</option>
                                <option value="under_payment">قيد المراجعة المالية</option>
                                <option value="active">ساري</option>
                                <option value="expired">منتهي الصلاحية</option>
                                <option value="revoked">متوقف</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="">التخصص</label>
                            <select class="form-control select2" name="specialization" id="specialization">
                                <option value="">اختر التخصص</option>
                                @foreach ($specialties as $specialization)
                                    <option value="{{ $specialization->id }}" {{ request()->input('specialization') == $specialization->id ? 'selected' : '' }}>
                                        {{ $specialization->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


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

                                @if (request('type') != "visitor")
                                    <th class="bg-light">الإقامة</th>
                                @endif

                                @if (request('type') == "visitor")
                                    <th class="bg-light"> تاريخ بدء الزيارة </th>
                                    <th class="bg-light"> تاريخ انتهاء الزيارة </th>
                                @endif

                                @if (request('type') == "libyan")
                                <th class="bg-light">الرقم الوطني</th>
                                @endif
                                <th class="bg-light text-dark" >نوع الطبيب</th>
                                
                                @if (request('type') != "visitor")
                                <th class="bg-light">الدرجة العلمية</th>
                                <th class="bg-light">تاريخ الانتساب</th>
                                @endif


                                <th class="bg-light">المؤهل</th>
                                <th class="bg-light">حالة العضوية</th>
                                @if (request('init_approve') )
                                    <th class="bg-light"> تاريخ الزيارة</th>
                                @endif
                                @if (get_area_name() == "finance")
                                <th class="bg-danger text-light">القيمة المستحقة من الطبيب</th>
                                <th class="bg-success text-light">القيمة المدفوعة الكلية</th>
                                <th class="bg-warning text-light">القيمة المعفى عنه</th>
                                @endif
                                
                                @if (request('type') != "visitor")
                                    <th class="bg-light">جهة العمل</th>
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
                                    {{-- rank &  --}}
                                    @if ($doctor->doctor_rank)
                                    {{ $doctor->doctor_rank->name }} 
                                    @endif
                                   {{ $doctor->specialization  }}
                                </td>


                                @if (request('type') != "visitor")
                                    <td>{{ $doctor->address }}</td>
                                @endif

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
                                <td>{{ $doctor->academicDegree->name ?? 'N/A' }}</td>
                                

                                @if (request("type") != "visitor")
                                <td>{{ $doctor->registered_at?->format('Y-m-d') }}</td>
                                <td>{{ $doctor->certificate_of_excellence_date }}</td>
                                @endif

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
                                        {{ number_format($relief, 2) }} د.ل
                                    </td>
                               @endif
                              

                               @if (request('type') != "visitor" )
                               <td>
                                    @if ($doctor->institution)
                                    {{ $doctor->institution->name }}
                                    @endif
                                </td>
                               @endif

                                <td>



                                    @if ($doctor->code == null && $doctor->membership_status == \App\Enums\MembershipStatus::Pending)
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approve{{ $doctor->id }}"> القبول المبدئي للعضوية </button>
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="approve{{ $doctor->id }}" tabindex="-1" aria-labelledby="approveLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="approveLabel">تأكيد العضوية</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route(get_area_name().'.doctors.approve', $doctor)}}" method="POST">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="row">
                                                            <div class="col-md-12 text-right">
                                                                <label for="">حدد موعد الزيارة</label>
                                                                <input type="date" name="meet_date" value="{{Carbon\Carbon::now()->addDay()->format('Y-m-d')}}" id="" class="form-control">
                                                            </div>
                                                            <div class="row mt-3">
                                                                <div class="col">
                                                                    <button class="btn btn-primary text-light" type="submit">تأكيد</button>
                                                                {{-- close button --}}
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif






                                    @if (get_area_name() == "user" && $doctor->code == null && $doctor->membership_status == \App\Enums\MembershipStatus::InitApprove)
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approve_init{{ $doctor->id }}">القبول النهائي  العضوية</button>
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="approve_init{{ $doctor->id }}" tabindex="-1" aria-labelledby="approveLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="approveLabel">تأكيد العضوية</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route(get_area_name().'.doctors.approve', ['doctor' => $doctor, 'init' => 1])}}" method="POST">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="row">
                                                            <div class="row mt-3">
                                                                <div class="col">
                                                                    <button class="btn btn-primary text-light" type="submit">تأكيد</button>
                                                                {{-- close button --}}
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif



                                    @if (get_area_name() == "user" && $doctor->code == null && $doctor->membership_status == \App\Enums\MembershipStatus::InitApprove || get_area_name() == "user" && $doctor->code == null && $doctor->membership_status == \App\Enums\MembershipStatus::Pending)
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejected_{{ $doctor->id }}">رفض العضوية</button>
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="rejected_{{ $doctor->id }}" tabindex="-1" aria-labelledby="rejectApproval" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectApproval">تأكيد رفض العضوية</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route(get_area_name().'.doctors.reject', ['doctor' => $doctor, 'init' => 1])}}" method="POST">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="row">
                                                            <div class="row mt-3">
                                                                
                                                                <div class="col-md-12">
                                                                    <label for="">سبب الرفض</label>
                                                                    <textarea name="notes" id="" cols="10" rows="5" required class="form-control">
                                                                        
                                                                    </textarea>
                                                                </div>
                                                                <div class="col-md-12 mt-3">
                                                                    <button class="btn btn-primary text-light" type="submit">تأكيد</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif





                                    @if (get_area_name() != "finance")
                                    <a href="{{ route(get_area_name() . '.doctors.show', $doctor) }}" class="btn btn-primary btn-sm text-light">عرض <i class="fa fa-eye"></i></a>
                                    <a href="{{ route(get_area_name() . '.doctors.edit', $doctor) }}" class="btn btn-info btn-sm text-light">تعديل <i class="fa fa-edit"></i></a>
                                    <a href="{{ route(get_area_name() . '.doctors.print', $doctor) }}" class="btn btn-secondary btn-sm text-light">طباعة <i class="fa fa-print"></i></a>
                                    <button type="button" class="btn btn-danger btn-sm text-light" data-bs-toggle="modal" data-bs-target="#deleteModal" data-doctor-id="{{ $doctor->id }}">حذف <i class="fa fa-trash"></i></button>
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
@endsection

@section('scripts')
<script>
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var doctorId = button.getAttribute('data-doctor-id');
        var action = "{{ url(get_area_name() . '/doctors') }}/" + doctorId;
        var deleteForm = document.getElementById('deleteForm');
        deleteForm.setAttribute('action', action);
    });
</script>
@endsection
