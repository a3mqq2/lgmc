@extends('layouts.admin')
@section('title', 'إنشاء نوع  مستند جديد')
@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-light">إنشاء نوع  مستند جديد</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.file-types.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label"> مخصص لـ </label>
                            <select name="type" id="" class="form-control">
                                <option value="">حدد نوع</option>
                                <option value="doctor">طبيب</option>
                                <option value="medical_facility">منشأة طبية</option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <input type="checkbox" name="is_required" id="is_required" class="mr-3" value="1">
                            <label for="is_required" style="margin-right: 8px !important;">الملف اجباري</label>
                        </div>
                        <button type="submit" class="btn btn-primary">إنشاء</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
