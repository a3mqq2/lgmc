@extends('layouts.' . get_area_name())

@section('title', 'تفاصيل تحويل الطبيب')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <!-- Card Header -->
            <div class="card-header bg-primary text-light d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="fa fa-info-circle"></i> تفاصيل تحويل الطبيب
                </h4>
                <a href="{{ route(get_area_name() . '.doctor-transfers.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> العودة للقائمة
                </a>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 25%">رقم الطلب</th>
                                <td>{{ $doctorTransfer->id }}</td>
                            </tr>
                            <tr>
                                <th>الطبيب</th>
                                <td>{{ $doctorTransfer->doctor->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>من الفرع</th>
                                <td>{{ $doctorTransfer->fromBranch->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>إلى الفرع</th>
                                <td>{{ $doctorTransfer->toBranch->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>المستخدم الذي أنشأ الطلب</th>
                                <td>{{ $doctorTransfer->createdBy->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>تاريخ الإنشاء</th>
                                <td>{{ $doctorTransfer->created_at }}</td>
                            </tr>
                            <tr>
                                <th>السبب</th>
                                <td>{{ $doctorTransfer->note ?? 'لا توجد ملاحظات' }}</td>
                            </tr>
                            <tr>
                                <th>الحالة</th>
                                <td>
                                    <span class="badge bg-{{ $doctorTransfer->status === 'approved' ? 'success' : ($doctorTransfer->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ $doctorTransfer->status === 'approved' ? 'موافق عليه' : ($doctorTransfer->status === 'rejected' ? 'مرفوض' : 'قيد الانتظار') }}
                                    </span>
                                </td>
                            </tr>

                            @if($doctorTransfer->isApproved())
                                <tr>
                                    <th>تمت الموافقة بواسطة</th>
                                    <td>{{ $doctorTransfer->approvedBy->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الموافقة</th>
                                    <td>{{ $doctorTransfer->approved_at}}</td>
                                </tr>
                            @endif

                            @if($doctorTransfer->isRejected())
                                <tr>
                                    <th>سبب الرفض</th>
                                    <td>{{ $doctorTransfer->rejected_reason ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>تم الرفض بواسطة</th>
                                    <td>{{ $doctorTransfer->rejectedBy->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الرفض</th>
                                    <td>{{ $doctorTransfer->rejected_at}}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card Footer with Actions -->
            <div class="card-footer d-flex justify-content-end">
                @if($doctorTransfer->status === 'pending' && auth()->user()->branch_id == $doctorTransfer->to_branch_id)
                    <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i class="fa fa-check"></i> الموافقة
                    </button>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fa fa-times"></i> رفض
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">الموافقة على طلب النقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route(get_area_name() . '.doctor-transfers.approve', $doctorTransfer) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="approve_note" class="form-label">ملاحظات</label>
                        <textarea name="approve_note" id="approve_note" class="form-control" rows="3" placeholder="أدخل ملاحظاتك (اختياري)"></textarea>
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
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">رفض طلب النقل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route(get_area_name() . '.doctor-transfers.reject', $doctorTransfer) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reject_note" class="form-label">سبب الرفض</label>
                        <textarea name="reject_note" id="reject_note" class="form-control" rows="3" placeholder="أدخل سبب الرفض (إجباري)" required></textarea>
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
@endsection
