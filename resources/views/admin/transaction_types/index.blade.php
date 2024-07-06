@extends('layouts.' . get_area_name())

@section('title', 'قائمة تصنيفات الحركات ')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">قائمة تصنيفات الحركات </h4>
                </div>
                <div class="card-body">
                    <a href="{{ route(get_area_name().'.transaction-types.create') }}" class="btn btn-success mb-3">إنشاء تصنيف جديد</a>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-light">
                                    <th>#</th>
                                    <th>اسم التصنيف</th>
                                    <th>نوع التصنيف</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactionTypes as $index => $transactionType)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $transactionType->name }}</td>
                                        <td>{{ $transactionType->type === 'deposit' ? 'إيداع' : 'سحب' }}</td>
                                        <td>{{ $transactionType->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route(get_area_name().'.transaction-types.edit', $transactionType->id) }}" class="btn btn-sm btn-primary">تعديل</a>
                                            <form action="{{ route(get_area_name().'.transaction-types.destroy', $transactionType->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا التصنيف؟')">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
