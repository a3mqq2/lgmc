@extends('layouts.admin')
@section('title', 'قائمة أنواع المستندات')
@section('content')

<div class="">
    <a href="{{ route(get_area_name().'.file-types.create') }}" class="btn btn-primary mb-3 float-right">إنشاء نوع مستند جديد</a>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-light">قائمة أنواع المستندات</h5>
                </div>
                <div class="card-body">
                    @if ($fileTypes->isEmpty())
                        <p>لا توجد أنواع مستندات لعرضها.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>مخصص لـ</th>
                                        <th>الملف اجباري</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fileTypes as $fileType)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $fileType->name }}</td>
                                            <td>
                                                @if ($fileType->type == 'doctor')
                                                    طبيب
                                                @elseif ($fileType->type == 'medical_facility')
                                                    منشأة طبية
                                                @endif
                                            </td>
                                            <td>{{ $fileType->is_required ? 'نعم' : 'لا' }}</td>
                                            <td>
                                                <a href="{{ route(get_area_name().'.file-types.edit', $fileType->id) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                <form action="{{ route(get_area_name().'.file-types.destroy', $fileType->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا النوع من المستندات؟')">حذف</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
