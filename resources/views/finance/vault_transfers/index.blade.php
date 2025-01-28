@extends('layouts.' . get_area_name())

@section('title', 'قائمة تحويلات الخزينة')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">
                    <i class="fa fa-exchange-alt"></i> تحويلات الخزينة
                </h4>
                <a href="{{ route(get_area_name() . '.vault-transfers.create') }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> إضافة تحويل جديد
                </a>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>من الخزينة</th>
                                <th>إلى الخزينة</th>
                                <th>القيمة</th>
                                <th>الوصف</th>
                                <th>المستخدم</th>
                                <th>الفرع</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vaultTransfers as $transfer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transfer->fromVault->name ?? '-' }}</td>
                                    <td>{{ $transfer->toVault->name ?? '-' }}</td>
                                    <td>{{ number_format($transfer->amount, 2) }}</td>
                                    <td>{{ $transfer->description ?? 'لا يوجد' }}</td>
                                    <td>{{ $transfer->user->name ?? '-' }}</td>
                                    <td>{{ $transfer->branch?->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transfer->status === 'approved' ? 'success' : ($transfer->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ $transfer->status === 'approved' ? 'موافق عليه' : ($transfer->status === 'rejected' ? 'مرفوض' : 'قيد الانتظار') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($transfer->status === 'pending')
                                            @if ($transfer->status == "pending" && $transfer->fromVault->branch_id && ($transfer->fromVault->branch_id == auth()->user()->branch_id))
                                            <a href="{{ route(get_area_name() . '.vault-transfers.edit', $transfer) }}" class="btn btn-warning btn-sm">
                                             <i class="fa fa-edit"></i>
                                         </a>
                                         <form action="{{ route(get_area_name() . '.vault-transfers.destroy', $transfer) }}" method="POST" style="display: inline-block;">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟');">
                                                 <i class="fa fa-trash"></i>
                                             </button>
                                         </form>
                                            @endif
                                            @if ($transfer->status === 'pending' && ( ($transfer->toVault->branch_id && ($transfer->toVault->branch_id == auth()->user()->branch_id)) || (!$transfer->toVault->branch_id && !auth()->user()->branch_id )  ) )
                                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $transfer->id }}">
                                             <i class="fa fa-check"></i> 
                                         </button>
                                         <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $transfer->id }}">
                                             <i class="fa fa-times"></i> 
                                         </button>

                                         <!-- Approve Modal -->
                                         <div class="modal fade" id="approveModal{{ $transfer->id }}" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                                             <div class="modal-dialog">
                                                 <div class="modal-content">
                                                     <div class="modal-header">
                                                         <h5 class="modal-title" id="approveModalLabel">الموافقة على التحويل</h5>
                                                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                     </div>
                                                     <form action="{{ route(get_area_name() . '.vault-transfers.approve', $transfer) }}" method="POST">
                                                         @csrf
                                                         @method('PATCH')
                                                         <div class="modal-body">
                                                             <div class="mb-3">
                                                                 <label for="approve_note{{ $transfer->id }}" class="form-label">ملاحظات</label>
                                                                 <textarea name="approve_note" id="approve_note{{ $transfer->id }}" class="form-control" rows="3" placeholder="أدخل ملاحظاتك (اختياري)"></textarea>
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
                                         <div class="modal fade" id="rejectModal{{ $transfer->id }}" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                             <div class="modal-dialog">
                                                 <div class="modal-content">
                                                     <div class="modal-header">
                                                         <h5 class="modal-title" id="rejectModalLabel">رفض التحويل</h5>
                                                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                     </div>
                                                     <form action="{{ route(get_area_name() . '.vault-transfers.reject', $transfer) }}" method="POST">
                                                         @csrf
                                                         @method('PATCH')
                                                         <div class="modal-body">
                                                             <div class="mb-3">
                                                                 <label for="reject_note{{ $transfer->id }}" class="form-label">سبب الرفض</label>
                                                                 <textarea name="reject_note" id="reject_note{{ $transfer->id }}" class="form-control" rows="3" placeholder="أدخل سبب الرفض (إجباري)" required></textarea>
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
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">لا توجد تحويلات خزينة مسجلة.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
