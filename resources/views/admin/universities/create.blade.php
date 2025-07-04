@extends('layouts.'.get_area_name())
@section('title', 'إنشاء جامعة جديدة')
@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-light">إنشاء جامعة جديدة</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.universities.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الجامعة</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الجامعة بالانجليزية</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="en_name" value="{{ old('en_name') }}" required>
                            @error('name')
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
