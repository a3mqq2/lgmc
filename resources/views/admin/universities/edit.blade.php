@extends('layouts.'.get_area_name())
@section('title', 'تعديل بيانات جامعة')
@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-light">تعديل بيانات جامعة</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.universities.update', $university->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الجامعة</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $university->name }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الجامعة بالانجليزية</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="en_name" value="{{ old('en_name', $university->nameـen) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>



                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
