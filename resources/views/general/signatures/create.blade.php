@extends('layouts.'.get_area_name())
@section('title', 'إنشاء توقيع جديد')
@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-light">إنشاء توقيع جديد</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.signatures.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">الاسم (عربي)</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name_en" class="form-label">الاسم (انجليزي)</label>
                                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en') }}" required>
                                    @error('name_en')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="job_title_ar" class="form-label">المسمى الوظيفي (عربي)</label>
                                    <input type="text" class="form-control @error('job_title_ar') is-invalid @enderror" id="job_title_ar" name="job_title_ar" value="{{ old('job_title_ar') }}">
                                    @error('job_title_ar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="job_title_en" class="form-label">المسمى الوظيفي (انجليزي)</label>
                                    <input type="text" class="form-control @error('job_title_en') is-invalid @enderror" id="job_title_en" name="job_title_en" value="{{ old('job_title_en') }}">
                                    @error('job_title_en')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            

                           @if (get_area_name() == "user")
                              <input type="hidden" name="branch_id" value="{{auth()->user()->branch_id}}">
                           @endif


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input @error('is_selected') is-invalid @enderror" type="checkbox" id="is_selected" name="is_selected" value="1" {{ old('is_selected') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_selected">
                                            توقيع مختار
                                        </label>
                                        @error('is_selected')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">إذا تم تحديد هذا الخيار، سيتم إلغاء تحديد جميع التوقيعات الأخرى</small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">إنشاء التوقيع</button>
                        <a href="{{ route(get_area_name().'.signatures.index') }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection