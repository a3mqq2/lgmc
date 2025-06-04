@extends('layouts.'.get_area_name())
@section('title', 'تعديل مستند الطبيب')
@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">تعديل مستند الطبيب</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.doctors.files.update', ['file' => $file->id, 'doctor' => $file->doctor_id] ) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="file" class="form-label">تحميل الملف</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="document" required>
                            <span>لا تعدلها الا اذا اردت ذلك</span>
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
                                    <option value="{{$fileType->id}}" {{$fileType->id == $file->file_type_id ? "selected" : "" }} >{{$fileType->name}}</option>
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
