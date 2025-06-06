@extends('layouts.'.get_area_name())

@section('title', 'إدارة  صفات الاطباء')

@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-light">
                    <h5 class="card-title text-light">إدارة  صفات الاطباء</h5>
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
                                @foreach($doctor_ranks as $doctor_rank)
                                <tr>
                                    <th scope="row">{{ $doctor_rank->id }}</th>
                                    <td>{{ $doctor_rank->name }}</td>
                                    <td>{{ $doctor_rank->created_at }}</td>
                                    <td>
                                        <a href="{{ route(get_area_name().'.doctor_ranks.edit', $doctor_rank->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                                        <form action="{{ route(get_area_name().'.doctor_ranks.destroy', $doctor_rank->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذه الصفه')">حذف</button>
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
