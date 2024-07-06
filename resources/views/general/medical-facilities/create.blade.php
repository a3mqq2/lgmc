@extends('layouts.' . get_area_name())
@section('title', 'إنشاء منشأة طبية جديدة')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">إنشاء منشأة طبية جديدة</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.medical-facilities.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم المنشأة</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label"> السجل التجاري </label>
                            <input type="text" class="form-control" id="commerical_number" name="commerical_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="ownership" class="form-label">نوع الملكية</label>
                            <select class="form-control" id="ownership" name="ownership" required>
                                <option value="private">خاص</option>
                                <option value="public">عام</option>
                            </select>
                        </div>
                        @if (get_area_name() == "admin")
                        <div class="mb-3">
                            <label for="branch">الفرع</label>
                            <select name="branch_id" id="" class="form-control select2">
                                <option value="">حدد الفرع</option>
                                @foreach ($branches as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @else 
                        <input type="hidden" name="branch_id" value="{{auth()->user()->branch_id}}">
                        @endif
                        <div class="mb-3">
                            <label for="medical_facility_type_id" class="form-label">نوع المنشأة الطبية</label>
                            <select class="form-control" id="medical_facility_type_id" name="medical_facility_type_id"
                                required>
                                @foreach ($medicalFacilityTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">رقم الهاتف</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                        <button type="submit" class="btn btn-primary">إنشاء</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
