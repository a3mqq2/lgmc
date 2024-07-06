@extends('layouts.'.get_area_name())
@section('title', 'إضافة ملف جديد للمنشأة')
@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">إضافة ملف جديد للمنشأة</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.medical-facilities.medical-facility-files.store', $medicalFacility->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="file" class="form-label">تحميل الملف</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="document" required>
                            @error('file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="file_type_id" class="form-label"> نوع المستند </label>
                            <select name="file_type_id" id="file_type_id" class="form-control">
                                <option value="">حدد نوع المستند</option>
                                @foreach ($fileTypes as $fileType)
                                    <option value="{{$fileType->id}}">{{$fileType->name}}</option>
                                @endforeach
                            </select>
                            @error('file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">إضافة</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
