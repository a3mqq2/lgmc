@extends('layouts.admin')
@section('title', 'عرض الدول')

@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <a href="{{route("admin.countries.create")}}" class="btn btn-success mb-3 text-light"><i class="fa fa-plus"></i> اضافه دولة</a>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-light">
                    <h5 class="card-title text-light">قائمة الدول</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-light" scope="col">#</th>
                                    <th class="bg-light" scope="col">الاسم بالعربية</th>
                                    <th class="bg-light" scope="col">الاسم بالإنجليزية</th>
                                    <th class="bg-light" scope="col">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($countries as $country)
                                <tr>
                                    <th scope="row">{{ $country->id }}</th>
                                    <td>{{ $country->name }}</td>
                                    <td>{{ $country->en_name }}</td>
                                    <td>
                                        <a href="{{ route(get_area_name().'.countries.edit', $country->id) }}" class="btn btn-primary btn-sm">تعديل</a>
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
