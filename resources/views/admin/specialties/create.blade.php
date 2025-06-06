@extends('layouts.'.get_area_name())
@section('title', 'إنشاء تخصص طبي جديد')
@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-light">إنشاء تخصص طبي جديد</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.specialties.store') }}" method="POST">
                        @csrf


                        
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم التخصص</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>




                        <div class="mb-3">
                            <label for="name_en" class="form-label">اسم التخصص</label>
                            <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en') }}" required>
                            @error('name_en')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>




                        <button type="submit" class="btn btn-primary">إنشاء</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
