@extends('layouts.' . get_area_name())
@section('title', 'تعديل منشأة طبية')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">تعديل منشأة طبية</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.medical-facilities.update', $medicalFacility->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم المنشأة</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $medicalFacility->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label"> رقم السجل التجاري </label>
                            <input type="text" class="form-control" id="name" name="commerical_number" value="{{ $medicalFacility->commerical_number }}" required>
                        </div>

                    

                        <div class="mb-3">
                            <label for="medical_facility_type_id" class="form-label">نوع المنشأة الطبية</label>
                            <select class="form-control" id="medical_facility_type_id" name="medical_facility_type_id" required>
                                @foreach ($medicalFacilityTypes as $type)
                                    <option value="{{ $type->id }}" {{ $medicalFacility->medical_facility_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required>{{ $medicalFacility->address }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label">رقم الهاتف</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $medicalFacility->phone_number }}" maxlength="10" required>
                        </div>

                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
