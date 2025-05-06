@extends('layouts.' . get_area_name())
@section('title', 'تفاصيل اذن المزاولة')

@section('content')
<!-- Approval Button -->
<div class="row mt-4 mb-3">
    <div class="col-12">
        @if (($licence->status == "under_approve_branch" && get_area_name() == "user" && auth()->user()->permissions->where('name', 'approve-licences-branch')->count()) || ($licence->status == "under_approve_admin" && get_area_name() == "admin"  && auth()->user()->permissions->where('name', 'approve-licences-admin')->count()))
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
            موافقة
        </button>
        @endif

        @if ($licence->status == "active")
            <a href="{{route(get_area_name().'.licences.print', $licence->id)}}" class="btn btn-dark text-light">طباعه اذن المزاولة <i class="fa fa-print"></i></a>
        @endif

        <a href="{{ route(get_area_name() . '.licences.index', ['type' => $type ]) }}" class="btn btn-secondary">العودة إلى القائمة</a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h4 class="card-title">تفاصيل اذن المزاولة</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="bg-light">تاريخ الإصدار</th>
                                <td>{{ $licence->issued_date }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">تاريخ الانتهاء</th>
                                <td>{{ $licence->expiry_date }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">الحالة</th>
                                <td>{!! $licence->status_badge !!}</td>
                            </tr>
                            @if ($licence->licensable_type == "App\Models\MedicalFacility")
                            <tr>
                                <th class="bg-light"> الممثل </th>
                                <td>{{ $licence->licensable->manager ? $licence->licensable->manager->name : "لا احد" }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if($licence->licensable_type == 'App\Models\Doctor')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">تفاصيل الطبيب</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-light">الاسم</th>
                                    <td>{{ $licence->licensable->name }}</td>
                                </tr>
                                @if (request('type') == "libyan")
                                <tr>
                                    <th class="bg-light">الرقم الوطني</th>
                                    <td>{{ $licence->licensable->national_number }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="bg-light">تاريخ الميلاد</th>
                                    <td>{{ $licence->licensable->date_of_birth ? $licence->licensable->date_of_birth->format('Y-m-d') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">الجنس</th>
                                    <td>{{ $licence->licensable->gender }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">الهاتف</th>
                                    <td>{{ $licence->licensable->phone }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">البريد الإلكتروني</th>
                                    <td>{{ $licence->licensable->email }}</td>
                                </tr>
                                <!-- Add more doctor details as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($licence->licensable_type == 'App\Models\MedicalFacility')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">تفاصيل المرفق الطبي</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-light">الاسم</th>
                                    <td>{{ $licence->licensable->name }}</td>
                                </tr>
                             
                                <tr>
                                    <th class="bg-light">الهاتف</th>
                                    <td>{{ $licence->licensable->phone_number }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">الإقامة</th>
                                    <td>{{ $licence->licensable->address }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">سجلات اذن المزاولة</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <th>#</th>
                            <th>التفاصيل</th>
                            <th>المستخدم</th>
                            <th>التاريخ</th>
                        </thead>
                        <tbody>
                            @foreach ($licence->logs as $log)
                                <tr>
                                    <td class="bg-primary text-light">{{$loop->iteration}}</td>
                                    <td>{{$log->details}}</td>
                                    <td>{{$log->user?->name}}</td>
                                    <td>
                                        {{date('Y-m-d H:i', strtotime($log->created_at))}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



@if ( ($licence->status == "under_approve_branch"  && get_area_name() == "user") || ($licence->status == "under_approve_admin" && get_area_name() == "admin") )
    <!-- Approval Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route(get_area_name() . '.licences.approve', ['licence' => $licence->id]) }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">موافقة على اذن المزاولة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
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






@endsection
