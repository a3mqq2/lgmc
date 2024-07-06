@extends('layouts.admin')
@section('title', 'تعديل نوع مستند')
@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-light">تعديل نوع مستند</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.file-types.update', $fileType->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $fileType->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">مخصص لـ</label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="">حدد نوع</option>
                                <option value="doctor" @if(old('type', $fileType->type) == 'doctor') selected @endif>طبيب</option>
                                <option value="medical_facility" @if(old('type', $fileType->type) == 'medical_facility') selected @endif>منشأة طبية</option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_required" id="is_required" class="form-check-input" @if(old('is_required', $fileType->is_required)) checked @endif value="1">
                                <label for="is_required" class="form-check-label">الملف اجباري</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
