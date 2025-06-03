@extends('layouts.'.get_area_name())
@section('title', 'عرض التخصصات الطبية')

@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-light">
                    <h5 class="card-title text-light">قائمة التخصصات الطبية</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-light" scope="col">#</th>
                                    <th class="bg-light" scope="col">اسم التخصص</th>
                                    <th class="bg-light" scope="col">اسم التخصص بالانجليزي</th>
                                    <th class="bg-light" scope="col">تاريخ الإنشاء</th>
                                    <th class="bg-light" scope="col">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($specialties as $specialty)
                                <tr>
                                    <th scope="row">{{ $specialty->id }}</th>
                                    <td>{{ $specialty->name }}</td>
                                    <td>{{ $specialty->name_en }}</td>
                                    <td>{{ $specialty->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route(get_area_name().'.specialties.edit', $specialty->id) }}" class="btn btn-primary btn-sm">تعديل</a>
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
