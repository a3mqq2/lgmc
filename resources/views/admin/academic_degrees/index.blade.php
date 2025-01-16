@extends('layouts.'.get_area_name())

@section('title', 'إدارة الدرجات العلمية')

@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-light">
                    <h5 class="card-title text-light">إدارة الدرجات العلمية</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-light" scope="col">#</th>
                                    <th class="bg-light" scope="col">الاسم</th>
                                    <th class="bg-light" scope="col">تاريخ الإنشاء</th>
                                    <th class="bg-light" scope="col">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($academicDegrees as $academicDegree)
                                <tr>
                                    <th scope="row">{{ $academicDegree->id }}</th>
                                    <td>{{ $academicDegree->name }}</td>
                                    <td>{{ $academicDegree->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route(get_area_name().'.academic-degrees.edit', $academicDegree->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                                        <form action="{{ route(get_area_name().'.academic-degrees.destroy', $academicDegree->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذه الدرجة العلمية؟')">حذف</button>
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
</div>

@endsection
