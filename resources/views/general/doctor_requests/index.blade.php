@extends('layouts.'.get_area_name())

@section('title', 'طلبات الأطباء')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">طلبات الأطباء</h4>

    <a href="{{ route(get_area_name().'.doctor-requests.create', ['doctor_type' => request('doctor_type')]) }}" class="btn btn-success mb-3"><i class="fa fa-plus"></i> آضف طلب جديد </a>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">🔍 تصفية الطلبات</div>
        <div class="card-body">
            <form method="GET" action="{{ route(get_area_name().'.doctor-requests.index') }}">
                <div class="row">
                    <div class="col-md-6">
                        <label for="status">الحالة</label>
                        <select name="status" class="form-control">
                            <option value="">الكل</option>
                            @foreach(App\Enums\RequestStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date">التاريخ</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">بحث</button>
                        <a href="{{ route(get_area_name().'.doctor-requests.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">📋 قائمة الطلبات</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الطبيب</th>
                            <th>الفرع</th>
                            <th>نوع الطلب</th>
                            <th>السعر</th>
                            <th>تاريخ الطلب</th>
                            <th>الحالة</th>
                            <th>الملاحظات</th>
                            <th>الموافقة بواسطة</th>
                            <th>تاريخ الموافقة</th>
                            <th>الرفض بواسطة</th>
                            <th>تاريخ الرفض</th>
                            <th>الإكمال بواسطة</th>
                            <th>تاريخ الإكمال</th>
                            <th>تاريخ الإلغاء</th>
                            <th>حالة الفاتورة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctorRequests as $request)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $request->doctor->name }}</td>
                                <td>{{ $request->branch->name }}</td>
                                <td>{{ $request->pricing->name }}</td>
                                <td>{{ number_format($request->pricing->amount, 2) }} د.ل</td>
                                <td>{{ $request->date->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge {{ $request->status->badgeClass() }}">{{ $request->status->label() }}</span>
                                </td>
                                <td>{{ $request->notes ?? '-' }}</td>
                                <td>{{ $request->approvedBy->name ?? '-' }}</td>
                                <td>{{ $request->approved_at ? $request->approved_at->format('Y-m-d H:i') : '-' }}</td>
                                <td>{{ $request->rejectedBy->name ?? '-' }}</td>
                                <td>{{ $request->rejected_at ? $request->rejected_at->format('Y-m-d H:i') : '-' }}</td>
                                <td>{{ $request->doneBy->name ?? '-' }}</td>
                                <td>{{ $request->done_at ? $request->done_at->format('Y-m-d H:i') : '-' }}</td>
                                <td>{{ $request->canceled_at ? $request->canceled_at->format('Y-m-d H:i') : '-' }}</td>
                                <td>
                                    <span class="badge  {{$request->invoice ? $request->invoice->status->badgeClass() : ""}} ">
                                        {{ $request->invoice ? $request->invoice->status->label() : "لا توجد فاتورة" }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{route(get_area_name().'.doctor-requests.print', $request->id)}}" class="btn btn-primary text-light btn-sm"><i class="fa fa-print"></i> طباعه </a>
                                    @if ($request->status->value == \App\Enums\RequestStatus::pending->value)
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $request->id }}">موافقة <i class="fa fa-check"></i></button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">رفض <i class="fa fa-times"></i></button>
                                    @elseif ($request->status->value == \App\Enums\RequestStatus::under_process->value)
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#doneModal{{ $request->id }}">إكمال <i class="fa fa-check"></i></button>
                                    @endif
                                </td>
                                
                            </tr>

                            @include('general.doctor_requests.partials.modals', ['request' => $request])
                        @empty
                            <tr>
                                <td colspan="16" class="text-center">لا توجد طلبات مسجلة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $doctorRequests->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
