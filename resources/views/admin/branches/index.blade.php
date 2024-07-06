@extends('layouts.admin')
@section('title', 'قائمة الفروع')
@section('content')

<div class="">
    <a href="{{route(get_area_name().'.branches.create')}}" class="btn btn-success btn-sm mb-3">اضافه فرع جديد <i class="fa fa-plus mb-2"></i></a>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">قائمة الفروع</h4>
                </div>                                                                                                                                                                       
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الهاتف</th>
                                    <th>المدينة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الاعدادات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branches as $branch)
                                <tr>
                                    <td>{{ $branch->id }}</td>
                                    <td>{{ $branch->name }}</td>
                                    <td>{{ $branch->phone }}</td>
                                    <td>{{ $branch->city }}</td>
                                    <td>{{ $branch->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{route("admin.branches.edit", $branch)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
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
