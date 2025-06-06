@extends('layouts.' . get_area_name())
@section('title', 'قائمة اذونات المزاولة')

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        @if (get_area_name() == "user")
            <a href="{{ route(get_area_name().'.licences.create', ['type' => request('type')]) }}" class="btn btn-success">
                <i class="fa fa-plus"></i> اضف اذن جديد 
            </a>
        @endif
    </div>
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-light">
                <h4 class="card-title">قائمة اذونات المزاولة</h4>
            </div>
            <div class="card-body">
                <!-- Advanced Filter Form -->
                <form action="{{ route(get_area_name() . '.licences.index') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="">رقم الاذن</label>
                            <input type="text" class="form-control" name="code" placeholder="ابحث برقم الاذن ..." value="{{ request()->input('code') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="status">حالة الإذن</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">-- الكل --</option>
                                <option value="under_approve_branch" {{ request('status') == 'under_approve_branch' ? 'selected' : '' }}>قيد موافقة الفرع</option>
                                <option value="under_approve_admin" {{ request('status') == 'under_approve_admin' ? 'selected' : '' }}>قيد موافقة الادارة</option>
                                <option value="under_payment" {{ request('status') == 'under_payment' ? 'selected' : '' }}>قيد المراجعة المالية</option>
                                <option value="revoked" {{ request('status') == 'revoked' ? 'selected' : '' }}>تم ايقافه</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>ساري</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search">بحث بالاسم</label>
                            <input type="text" class="form-control" id="search" name="search" placeholder="ابحث بالاسم ..." value="{{ request()->input('search') }}">
                        </div>
                       
                        <div class="col-md-4 mt-2">
                            <label for="">الفتره ما بين (تاريخ الاصدار ) </label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="issued_from_date" value="{{ request('from_date') }}">
                                <span class="input-group-text">الى</span>
                                <input type="date" class="form-control" name="issued_to_date" value="{{ request('to_date') }}">
                            </div>
                        </div>


                        <div class="col-md-4 mt-2">
                            <label for="">الفتره ما بين (تاريخ الانتهاء  ) </label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="expiry_from_date" value="{{ request('from_date') }}">
                                <span class="input-group-text">الى</span>
                                <input type="date" class="form-control" name="expiry_to_date" value="{{ request('to_date') }}">
                            </div>
                        </div>


                        <div class="col-md-4 align-self-end">
                            <button type="submit" class="btn btn-primary w-100">بحث</button>
                        </div>
                    </div>
                    <input type="hidden" name="type" value="{{request('type')}}">
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="bg-light">#</th>
                                <th class="bg-light">رقم الاذن</th>
                                <th class="bg-light">المرخص له</th>
                                <th class="bg-light">الفرع</th>
                                <th class="bg-light">تاريخ الإصدار</th>
                                <th class="bg-light">تاريخ الانتهاء</th>
                                <th class="bg-light">الحالة</th>
                                <th class="bg-light">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($licences as $licence)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$licence->code}}</td>
                                <td>{{ $licence->licensable->name }}</td>
                                <td>{{ $licence->branch->name ?? '---' }}</td>
                                <td>{{ $licence->issued_date }}</td>
                                <td>{{ $licence->expiry_date }}</td>
                                <td>{!! $licence->status_badge !!}</td>
                                <td>
                                    <a href="{{ route(get_area_name().'.licences.show', $licence) }}" class="btn btn-primary btn-sm text-light">
                                        عرض <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route(get_area_name().'.licences.print', $licence) }}" class="btn btn-secondary btn-sm text-light">
                                        طباعه <i class="fa fa-print"></i>
                                    </a>
                                    <a href="{{ route(get_area_name().'.licences.edit', ['licence' => $licence]) }}" class="btn btn-info btn-sm text-light">
                                        تعديل <i class="fa fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm text-light" data-bs-toggle="modal" data-bs-target="#deleteModal" data-licence-id="{{ $licence->id }}">
                                        حذف <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $licences->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@if (get_area_name() == "user")
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
                    هل أنت متأكد أنك تريد حذف هذا الاذن مزاولة؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">حذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var licenceId = button.getAttribute('data-licence-id');
        var action = "{{ url(get_area_name() . '/licences') }}/" + licenceId;
        var deleteForm = document.getElementById('deleteForm');
        deleteForm.setAttribute('action', action);
    });
</script>
@endsection
