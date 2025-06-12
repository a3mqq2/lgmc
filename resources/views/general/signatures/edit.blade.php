@extends('layouts.' . get_area_name())
@section('title', 'تعديل التوقيع')

@section('content')
@php
    $currentSelected = $signature->branch_id
        ? optional($signature->branch)->signature_id === $signature->id
        : $signature->is_selected;
@endphp

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-light">تعديل التوقيع: {{ $signature->name }}</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route(get_area_name() . '.signatures.update', $signature) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">الاسم (عربي)</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name"
                                           value="{{ old('name', $signature->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name_en" class="form-label">الاسم (إنجليزي)</label>
                                    <input type="text"
                                           class="form-control @error('name_en') is-invalid @enderror"
                                           id="name_en" name="name_en"
                                           value="{{ old('name_en', $signature->name_en) }}" required>
                                    @error('name_en')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="job_title_ar" class="form-label">المسمى الوظيفي (عربي)</label>
                                    <input type="text"
                                           class="form-control @error('job_title_ar') is-invalid @enderror"
                                           id="job_title_ar" name="job_title_ar"
                                           value="{{ old('job_title_ar', $signature->job_title_ar) }}">
                                    @error('job_title_ar')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="job_title_en" class="form-label">المسمى الوظيفي (إنجليزي)</label>
                                    <input type="text"
                                           class="form-control @error('job_title_en') is-invalid @enderror"
                                           id="job_title_en" name="job_title_en"
                                           value="{{ old('job_title_en', $signature->job_title_en) }}">
                                    @error('job_title_en')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="branch_id" class="form-label">الفرع</label>
                                    <select class="form-select @error('branch_id') is-invalid @enderror"
                                            id="branch_id" name="branch_id">
                                        <option value="" {{ old('branch_id', $signature->branch_id) === null ? 'selected' : '' }}>
                                            النقابة العامة
                                        </option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branch_id', $signature->branch_id) == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <!-- hidden ensures key always present -->
                                    <input type="hidden" name="is_selected" value="0">
                                    <div class="form-check">
                                        <input class="form-check-input @error('is_selected') is-invalid @enderror"
                                               type="checkbox" id="is_selected" name="is_selected" value="1"
                                               {{ old('is_selected', $currentSelected) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_selected">
                                            توقيع مختار
                                        </label>
                                        @error('is_selected')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        عند اختيار هذا التوقيع سيتم إلغاء تمييز أي توقيع آخر لنفس الفرع (أو النقابة العامة).
                                    </small>
                                    @if($currentSelected)
                                        <div class="alert alert-info mt-2">
                                            <i class="fas fa-info-circle"></i> هذا التوقيع هو المختار حالياً
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">معلومات إضافية</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <strong>تاريخ الإنشاء:</strong>
                                                        {{ $signature->created_at->format('Y-m-d H:i:s') }}
                                                    </small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <strong>آخر تحديث:</strong>
                                                        {{ $signature->updated_at->format('Y-m-d H:i:s') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ التعديلات
                            </button>
                            <a href="{{ route(get_area_name() . '.signatures.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
