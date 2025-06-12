@extends('layouts.' . get_area_name())
@section('title', 'قائمة الحسابات')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">
                        <i class="fa fa-vault me-2"></i>
                        قائمة الحسابات
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">اسم الحساب</th>
                                    <th scope="col">المسؤول</th>
                                    <th scope="col">حالة العهدة</th>
                                    <th scope="col" class="text-dark">الرصيد</th>
                                    <th scope="col">التحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vaults as $index => $vault)
                                    <tr class="{{ $vault->user_id ? 'table-warning' : '' }}">
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>
                                            <strong>{{ $vault->name }}</strong>
                                            @if($vault->user_id)
                                                <span class="badge bg-info ms-2">
                                                    <i class="fa fa-user me-1"></i>
                                                    عهدة شخصية
                                                </span>
                                            @else
                                                <span class="badge bg-secondary ms-2">
                                                    <i class="fa fa-building me-1"></i>
                                                    حساب عام
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($vault->user_id && $vault->user)
                                                <div class="d-flex align-items-center justify-content-center ">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($vault->user->name) }}&background=007bff&color=fff" 
                                                         alt="Avatar" class="rounded-circle me-2" width="30" height="30">
                                                    <div>
                                                        <strong>{{ $vault->user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $vault->user->email ?? 'لا يوجد إيميل' }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fa fa-minus"></i>
                                                    غير محدد
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($vault->user_id)
                                                @if($vault->balance > 0)
                                                    <span class="badge bg-success">
                                                        <i class="fa fa-check-circle me-1"></i>
                                                        عهدة نشطة
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fa fa-exclamation-circle me-1"></i>
                                                        رصيد صفر
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fa fa-building me-1"></i>
                                                    حساب عام
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ $vault->balance > 0 ? 'text-success' : ($vault->balance < 0 ? 'text-danger' : 'text-muted') }}">
                                                {{ number_format($vault->balance, 2) }} د.ل
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- زر عرض المعاملات -->
                                                <a href="{{ route(get_area_name().'.transactions.index', ['vault_id' => $vault->id,'is_transfered' => false]) }}" 
                                                   class="btn btn-sm btn-info" title="عرض المعاملات">
                                                    <i class="fa fa-eye"></i>
                                                    عرض
                                                </a>

                                                    <!-- زر إغلاق العهدة - يظهر فقط للحسابات التي لها user_id -->
                                                    @if($vault->user_id)
                                                        <button type="button" 
                                                                class="btn btn-sm btn-warning" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#closeCustodyModal{{ $vault->id }}"
                                                                title="إغلاق العهدة">
                                                            <i class="fa fa-lock"></i>
                                                            إغلاق العهدة
                                                        </button>
                                                    @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($vaults->isEmpty())
                        <div class="text-center py-4">
                            <i class="fa fa-vault fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد حسابات</h5>
                            <p class="text-muted">لم يتم إنشاء أي حسابات بعد</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modals for closing custody and deleting vaults -->
    @foreach ($vaults as $vault)
        <!-- Modal إغلاق العهدة -->
        @if($vault->user_id)
            <div class="modal fade" id="closeCustodyModal{{ $vault->id }}" tabindex="-1" aria-labelledby="closeCustodyModalLabel{{ $vault->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title" id="closeCustodyModalLabel{{ $vault->id }}">
                                <i class="fa fa-lock me-2"></i>
                                إغلاق العهدة - {{ $vault->name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route(get_area_name().'.vaults.close-custody', $vault->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle me-2"></i>
                                    <strong>تأكيد إغلاق العهدة</strong>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>اسم الحساب:</strong>
                                        <p>{{ $vault->name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>المسؤول الحالي:</strong>
                                        <p>{{ $vault->user->name ?? 'غير محدد' }}</p>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>الرصيد الحالي:</strong>
                                        <p class="fw-bold {{ $vault->balance > 0 ? 'text-success' : ($vault->balance < 0 ? 'text-danger' : 'text-muted') }}">
                                            {{ number_format($vault->balance, 2) }} د.ل
                                        </p>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fa fa-times me-2"></i>
                                    إلغاء
                                </button>
                                <button type="submit" 
                                        class="btn btn-warning">
                                    <i class="fa fa-lock me-2"></i>
                                    إغلاق العهدة
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal حذف الحساب -->
            <div class="modal fade" id="deleteModal{{ $vault->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $vault->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="deleteModalLabel{{ $vault->id }}">
                                <i class="fa fa-trash me-2"></i>
                                حذف الحساب - {{ $vault->name }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route(get_area_name().'.vaults.destroy', $vault->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-triangle me-2"></i>
                                    <strong>تحذير:</strong> هذا الإجراء لا يمكن التراجع عنه!
                                </div>
                                
                                <p>هل أنت متأكد من حذف الحساب <strong>"{{ $vault->name }}"</strong>؟</p>
                                
                                @if($vault->balance != 0)
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-circle me-2"></i>
                                        الرصيد الحالي: <strong>{{ number_format($vault->balance, 2) }} د.ل</strong>
                                    </div>
                                @endif

                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="confirm_delete{{ $vault->id }}" 
                                           name="confirm_delete" 
                                           required>
                                    <label class="form-check-label" for="confirm_delete{{ $vault->id }}">
                                        أؤكد أنني أريد حذف هذا الحساب نهائياً
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fa fa-times me-2"></i>
                                    إلغاء
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa fa-trash me-2"></i>
                                    حذف نهائياً
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    @endforeach
@endsection

@push('styles')
<style>
/* تحسينات إضافية للواجهة */
.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.btn-group .btn {
    margin-left: 2px;
}

.btn-group .btn:first-child {
    margin-left: 0;
}

.badge {
    font-size: 0.7rem;
}

.modal-header.bg-warning {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.modal-header.bg-danger {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin-left: 0;
        margin-bottom: 2px;
    }
    
    .table-responsive {
        font-size: 0.85rem;
    }
}
</style>
@endpush