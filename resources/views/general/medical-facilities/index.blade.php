@extends('layouts.' . get_area_name())
@section('title', 'قائمة المنشآت الطبية')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">قائمة المنشآت الطبية</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.medical-facilities.index') }}" method="GET">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="name" placeholder="اسم المنشأة"
                                    value="{{ request()->input('name') }}">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="medical_facility_type_id">
                                    <option value="">اختر نوع المنشأة الطبية</option>
                                    @foreach ($medicalFacilityTypes as $type)
                                        <option value="{{ $type->id }}"
                                            @if (request()->input('medical_facility_type_id') == $type->id) selected
                                    @endif>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">بحث</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الملكية</th>
                                    <th>رقم السجل التجاري</th>
                                    <th>النوع</th>
                                    <th>العنوان</th>
                                    <th>رقم الهاتف</th>
                                    <th>مالك النشاط</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medicalFacilities as $facility)
                                    <tr>
                                        <td>{{ $facility->id }}</td>
                                        <td>{{ $facility->name }}</td>
                                        <td>{{ $facility->ownership == "private" ? "خاص" : "عام" }}</td>
                                        <td>{{$facility->commerical_number}}</td>
                                        <td>{{ $facility->type->name }}</td>
                                        <td>{{ $facility->address }}</td>
                                        <td>{{ $facility->phone_number }}</td>
                                        <td>{{$facility->manager ? $facility->manager->name : "لا آحد"}}</td>
                                        <td>

                                            <a href="{{ route(get_area_name().'.medical-facilities.show', $facility->id) }}"
                                                class="btn btn-primary btn-sm">عرض <i class="fa fa-eye"></i> </a>

                                            <a href="{{ route(get_area_name().'.medical-facilities.edit', $facility->id) }}"
                                                class="btn btn-warning btn-sm">تعديل <i class="fa fa-edit"></i></a>
                                            <form action="{{ route(get_area_name().'.medical-facilities.destroy', $facility->id) }}"
                                                method="POST" style="display: inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذه المنشأة الطبية؟')">حذف <i class="fa fa-trash"></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $medicalFacilities->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
