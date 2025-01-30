@extends('layouts.'.get_area_name())

@section('title', 'إضافة إلى البلاك ليست')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">إضافة شخص إلى البلاك ليست</h4>


    <div class="card">
        <div class="card-header bg-primary text-white">📋 نموذج الإضافة</div>
        <div class="card-body">
            <form method="POST" action="{{ route(get_area_name().'.blacklists.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="اسم الشخص" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="number_phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                        <input type="text" name="number_phone" id="number_phone" value="{{ old('number_phone') }}" class="form-control" placeholder="رقم الهاتف" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="passport_number" class="form-label">رقم الجواز (اجباري في حال طبيب غير ليبي) </label>
                        <input type="text" pattern="[A-Z0-9]+"  name="passport_number" id="passport_number" value="{{ old('passport_number') }}" class="form-control" placeholder="رقم الجواز">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="id_number" class="form-label">الرقم الوطني (اجباري في حال طبيب ليبي ) </label>
                        <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}" class="form-control" placeholder="رقم الهوية">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">السبب</label>
                    <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="سبب إدراج الشخص في البلاك ليست">{{ old('reason') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="doctor_type" class="form-label">نوع الطبيب</label>
                    <select name="doctor_type" id="doctor_type" class="form-control">
                        <option value="">اختر نوع الطبيب</option>
                        <option value="libyan">ليبي</option>
                        <option value="foreign">اجنبي</option>
                        <option value="palestinian">فلسطيني</option>
                        <option value="visitor">زائر</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">إضافة إلى البلاك ليست</button>
                    <a href="{{ route(get_area_name().'.blacklists.index') }}" class="btn btn-secondary ms-2">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
