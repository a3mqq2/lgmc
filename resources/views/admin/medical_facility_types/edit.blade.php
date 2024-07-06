@extends('layouts.admin')
@section('title', 'تعديل نوع مرفق طبي')
@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-light">تعديل نوع مرفق طبي</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.medical-facility-types.update', $medicalFacilityType->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $medicalFacilityType->name }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="en_name" class="form-label">اسم بالإنجليزية</label>
                            <input type="text" class="form-control @error('en_name') is-invalid @enderror" id="en_name" name="en_name" value="{{ $medicalFacilityType->en_name }}" required>
                            @error('en_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
