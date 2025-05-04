@extends('layouts.'.get_area_name())

@section('title', 'إضافة طلب جديد')

@section('content')
<div class="">
    <h4 class="mb-4">إضافة طلب جديد</h4>
    <div class="card">
        <div class="card-header bg-primary text-white">📋 نموذج إضافة طلب</div>
        <div class="card-body">
            <form method="POST" action="{{ route(get_area_name().'.doctor-requests.store') }}">
                @csrf



                <input type="hidden" name="doctor_type" value="{{ $doctor_type }}">

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="doctor_id" class="form-label">اسم الطبيب <span class="text-danger">*</span></label>
                        <select name="doctor_id" id="doctor_id" class="form-control select2" required>
                            <option value="">اختر الطبيب</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="pricing_id" class="form-label">اختيار نوع الطلب <span class="text-danger">*</span></label>
                        <select name="pricing_id" id="pricing_id" class="form-control" required>
                            <option value="">اختر نوع الطلب</option>
                            @foreach($pricings as $pricing)
                                <option value="{{ $pricing->id }}" {{ old('pricing_id') == $pricing->id ? 'selected' : '' }}>
                                    {{ $pricing->name }} - {{ number_format($pricing->amount, 2) }} د.ل
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                     <label for="date" class="form-label">تاريخ الطلب <span class="text-danger">*</span></label>
                     <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d') ) }}" class="form-control" required>
                 </div>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">الملاحظات</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="أضف ملاحظات إضافية">{{ old('notes') }}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">إضافة الطلب</button>
                    <a href="{{ route(get_area_name().'.doctor-requests.index', ['doctor_type' => $doctor_type]) }}" class="btn btn-secondary ms-2">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
