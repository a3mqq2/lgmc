@extends('layouts.' . get_area_name())
@section('title', 'قائمة المنشآت الطبية')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h4 class="card-title mb-0">قائمة المنشآت الطبية</h4>
            </div>
            <div class="card-body">
                {{-- بحث --}}
                <form method="GET" action="{{ route(get_area_name() . '.medical-facilities.index') }}" class="row gy-2 gx-3 align-items-center mb-4">
                    <div class="col-auto">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="بحث بالاسم">
                    </div>
                    <div class="col-auto">
                        <select name="status" class="form-select">
                            <option value="">كل الحالات</option>
                            <option value="active"   {{ request('status')=='active'   ? 'selected' : '' }}>مفعل</option>
                            <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>غير مفعل</option>
                            <option value="pending"  {{ request('status')=='pending'  ? 'selected' : '' }}>قيد المراجعة</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search me-1"></i> بحث</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>النوع</th>
                                <th>الموقع</th>
                                <th>الهاتف</th>
                                <th>مالك النشاط</th>
                                <th>تاريخ التسجيل</th>
                                <th>الحالة</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($medicalFacilities as $facility)
                                <tr>
                                    <td>{{ $facility->code }}</td>
                                    <td>{{ $facility->name }}</td>
                                    <td>{{ $facility->type->name }}</td>
                                    <td>{{ $facility->address }}</td>
                                    <td>{{ $facility->phone_number }}</td>
                                    <td>{{ $facility->manager?->name ?? '—' }}</td>
                                    <td>{{ $facility->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @switch($facility->membership_status->value)
                                            @case('active')
                                                <span class="badge bg-success">مفعل</span>
                                                @break
                                            @case('inactive')
                                                <span class="badge bg-danger">غير مفعل</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">قيد المراجعة</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="{{ route(get_area_name().'.medical-facilities.show', $facility) }}"
                                           class="btn btn-sm btn-primary">
                                           <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route(get_area_name().'.medical-facilities.edit', $facility) }}"
                                           class="btn btn-sm btn-warning">
                                           <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route(get_area_name().'.medical-facilities.destroy', $facility) }}"
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذه المنشأة؟')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                        @if($facility->membership_status->value === 'pending')
                                            <button class="btn btn-sm btn-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#approveModal{{ $facility->id }}">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $facility->id }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $medicalFacilities->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
@foreach ($medicalFacilities as $facility)
    {{-- موافقة --}}
    <div class="modal fade" id="approveModal{{ $facility->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form action="{{ route(get_area_name() . '.medical-facilities.approve', $facility) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-header">
              <h5 class="modal-title">تأكيد الموافقة</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              هل أنت متأكد من تفعيل المنشأة: <strong>{{ $facility->name }}</strong>؟
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
              <button type="submit" class="btn btn-success">نعم، تفعيل</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- رفض --}}
    <div class="modal fade" id="rejectModal{{ $facility->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form action="{{ route(get_area_name() . '.medical-facilities.reject', $facility) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-header bg-danger text-light">
              <h5 class="modal-title">تأكيد الرفض</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="reason{{ $facility->id }}" class="form-label">سبب الرفض</label>
                <textarea name="reason" id="reason{{ $facility->id }}" rows="3"
                          class="form-control" required></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
              <button type="submit" class="btn btn-danger">رفض المنشأة</button>
            </div>
          </form>
        </div>
      </div>
    </div>
@endforeach
@endsection
