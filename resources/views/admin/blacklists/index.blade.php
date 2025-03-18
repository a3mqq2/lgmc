@extends('layouts.'.get_area_name())

@section('title', 'البلاك ليست')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">البلاك ليست</h4>
    <div class="row mt-2">
      <div class="col-md-12 mb-2">
         <a href="{{route(get_area_name().'.blacklists.create')}}" class="btn btn-success text-light">اضف جديد</a>
      </div>
    </div>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">🔍 تصفية البلاك ليست</div>
        <div class="card-body">
            <form method="GET" action="{{ route(get_area_name().'.blacklists.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="name">الاسم</label>
                        <input type="text" name="name" id="name" value="{{ request('name') }}" class="form-control" placeholder="اسم الشخص">
                    </div>
                    <div class="col-md-3">
                        <label for="number_phone">رقم الهاتف</label>
                        <input type="text" name="number_phone" id="number_phone" value="{{ request('number_phone') }}" class="form-control" placeholder="رقم الهاتف">
                    </div>
                    <div class="col-md-3">
                        <label for="doctor_type">نوع الطبيب</label>
                        <select name="doctor_type" id="doctor_type" class="form-control">
                            <option value="">حدد نوع الطبيب</option>
                            <option value="libyan">ليبي</option>
                            <option value="foreign">اجنبي</option>
                            <option value="palestinian">فلسطيني</option>
                            <option value="visitor">زائر</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search">بحث</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="اسم أو رقم الهاتف">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="reason"> السبب </label>
                        <input type="text" name="reason" id="reason" value="{{ request('reason') }}" class="form-control" placeholder="فلتر إضافي إذا لزم الأمر">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">بحث</button>
                        <a href="{{ route(get_area_name().'.blacklists.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <div class="card">
        <div class="card-header bg-primary text-white">جدول البلاك ليست</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>رقم الهاتف</th>
                            <th>رقم الجواز</th>
                            <th>رقم الهوية</th>
                            <th>السبب</th>
                            <th>نوع الطبيب</th>
                            <th>تاريخ الإنشاء</th>
                            <th>تاريخ التحديث</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blacklists as $blacklist)
                            <tr>
                                <td>{{ $blacklist->id }}</td>
                                <td>{{ $blacklist->name }}</td>
                                <td>{{ $blacklist->number_phone }}</td>
                                <td>{{ $blacklist->passport_number ?? '-' }}</td>
                                <td>{{ $blacklist->id_number ?? '-' }}</td>
                                <td>{{ $blacklist->reason ?? '-' }}</td>
                                <td>{{  $blacklist->doctor_type->label() }}</td>
                                <td>{{ $blacklist->created_at->format('Y-m-d') }}</td>
                                <td>{{ $blacklist->updated_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route(get_area_name().'.blacklists.edit', $blacklist->id) }}" class="btn btn-sm btn-warning">تعديل <i class="fa fa-edit"></i></a>
                                    
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete_{{$blacklist->id}}">
                                        حذف <i class="fa fa-trash"></i>
                                    </button>

                                    <div class="modal fade" id="delete_{{$blacklist->id}}" tabindex="-1" aria-labelledby="delete_{{$blacklist->id}}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route(get_area_name() . '.blacklists.destroy', $blacklist->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="delete_{{$blacklist->id}}Label">تأكيد حذف</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        هل أنت متأكد من حذف هذا الشخص من البلاك ليست؟
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                        <button type="submit" class="btn btn-danger">حذف</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">لا توجد سجلات في البلاك ليست.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $blacklists->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
