@extends('layouts.'.get_area_name())
@section('title', 'عرض الجامعات')

@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-light">
                    <h5 class="card-title text-light">قائمة الجامعات</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-light" scope="col">#</th>
                                    <th class="bg-light" scope="col">اسم الجامعة</th>
                                    <th class="bg-light" scope="col">اسم الجامعة بالانجليزية</th>
                                    <th class="bg-light" scope="col">تاريخ الإنشاء</th>
                                    <th class="bg-light" scope="col">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($universities as $university)
                                <tr>
                                    <th scope="row">{{ $university->id }}</th>
                                    <td>{{ $university->name }}</td>
                                    <td>{{ $university->name_en }}</td>
                                    <td>{{ $university->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route(get_area_name().'.universities.edit', $university->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $university->id }}">حذف</button>
                                    </td>
                                </tr>
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $university->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $university->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $university->id }}">تأكيد الحذف</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                هل أنت متأكد من رغبتك في حذف الجامعة <strong>{{ $university->name }}</strong>؟
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                <form action="{{ route(get_area_name().'.universities.destroy', $university->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">حذف</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
