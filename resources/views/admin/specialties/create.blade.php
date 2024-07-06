@extends('layouts.admin')
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
                            <label for="specialty_id" class="form-label">التخصص الفرعي لـ</label>
                            <select class="form-select @error('specialty_id') is-invalid @enderror" id="specialty_id" name="specialty_id">
                                <option value="">لا يوجد</option>
                                @foreach ($specialties as $specialty)
                                    <option value="{{$specialty->id}}">{{$specialty->name}}</option>
                                @endforeach
                            </select>
                            @error('specialty_id')
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
