@extends('layouts.' . get_area_name())
@section('title', 'عرض البريد الإلكترونية')

@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12 mb-3 text-end">
            <a href="{{ route(get_area_name().'.emails.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> إضافة بريد جديد
            </a>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-light bg-primary">
                    <h5 class="card-title text-white">قائمة البريد الإلكترونية</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-light" scope="col">#</th>
                                    <th class="bg-light" scope="col">البريد الإلكتروني</th>
                                    <th class="bg-light" scope="col">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($emails as $email)
                                    <tr>
                                        <th scope="row">{{ $email->id }}</th>
                                        <td>{{ $email->email }}</td>
                                        <td>
                                            <a href="{{ route(get_area_name().'.emails.edit', $email->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $email->id }}">حذف</button>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $email->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $email->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $email->id }}">تأكيد الحذف</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    هل أنت متأكد من رغبتك في حذف البريد <strong>{{ $email->email }}</strong>؟
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                    <form action="{{ route(get_area_name().'.emails.destroy', $email->id) }}" method="POST">
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

                        @if ($emails->isEmpty())
                            <p class="text-center text-muted">لا توجد بيانات حالياً.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
