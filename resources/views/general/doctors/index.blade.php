@extends('layouts.' . get_area_name())
@section('title', 'قائمة الاطباء')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h4 class="card-title">قائمة الاطباء</h4>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name().'.doctors.index') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="name">اسم الطبيب</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="اسم الطبيب" value="{{ request()->input('name') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="phone">رقم الهاتف</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="رقم الهاتف" maxlength="10" value="{{ request()->input('phone') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="email">البريد الالكتروني</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="البريد الالكتروني" value="{{ request()->input('email') }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="academic_degree">الدرجة العلمية</label>
                            <select class="form-control" id="academic_degree" name="academic_degree">
                                <option value="">اختر الدرجة العلمية</option>
                                @foreach($academicDegrees as $degree)
                                <option value="{{ $degree->id }}" {{ request()->input('academic_degree') == $degree->id ? 'selected' : '' }}>
                                    {{ $degree->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="created_at">تاريخ الاضافة</label>
                            <input type="date" class="form-control" id="created_at" name="created_at" value="{{ request()->input('created_at') }}">
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
                                <th class="bg-light">الاسم</th>
                                <th class="bg-light">رقم الهاتف</th>
                                <th class="bg-light">البريد الالكتروني</th>
                                <th class="bg-light">العنوان</th>
                                <th class="bg-light">الرقم الوطني</th>
                                <th class="bg-light">الدرجة العلمية</th>
                                <th class="bg-light">تاريخ الاضافة</th>
                                <th class="bg-light">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doctors as $doctor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $doctor->code }}</td>
                                <td>{{ $doctor->name }}</td>
                                <td>{{ $doctor->phone }}</td>
                                <td>{{ $doctor->email }}</td>
                                <td>{{ $doctor->address }}</td>
                                <td>{{ $doctor->national_number }}</td>
                                <td>{{ $doctor->academicDegree->name ?? 'N/A' }}</td>
                                <td>{{ $doctor->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route(get_area_name() . '.doctors.show', $doctor) }}" class="btn btn-primary btn-sm text-light">عرض <i class="fa fa-eye"></i></a>
                                    <a href="{{ route(get_area_name() . '.doctors.edit', $doctor) }}" class="btn btn-info btn-sm text-light">تعديل <i class="fa fa-edit"></i></a>
                                    <a href="{{ route(get_area_name() . '.doctors.print', $doctor) }}" class="btn btn-secondary btn-sm text-light">طباعة <i class="fa fa-print"></i></a>
                                    <button type="button" class="btn btn-danger btn-sm text-light" data-bs-toggle="modal" data-bs-target="#deleteModal" data-doctor-id="{{ $doctor->id }}">حذف <i class="fa fa-trash"></i></button>
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
