@extends('layouts.' . get_area_name())

@section('title', 'إضافة طلب نقل طبيب')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <!-- Card Header -->
            <div class="card-header bg-primary text-light">
                <h4 class="card-title">
                    <i class="fa fa-exchange-alt"></i> إضافة طلب نقل طبيب
                </h4>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <form action="{{ route(get_area_name() . '.doctor-transfers.store') }}" method="POST">
                    @csrf

                    <!-- First Row: Select Doctor & Select New Branch -->
                    <div class="row g-3">
                        <!-- Select Doctor -->
                        <div class="col-md-6">
                            <label for="doctor_id" class="form-label">
                                <i class="fa fa-user-md"></i> الطبيب
                            </label>
                            <select 
                                name="doctor_id" 
                                id="doctor_id" 
                                class="form-control select2 @error('doctor_id') is-invalid @enderror" 
                                required
                            >
                                <option value="">-- اختر الطبيب --</option>
                                @foreach($doctors as $doctor)
                                    <option 
                                        value="{{ $doctor->id }}" 
                                        {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}
                                    >
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Select Destination Branch -->
                        <div class="col-md-6">
                            <label for="to_branch_id" class="form-label">
                                <i class="fa fa-map-marker-alt"></i> إلى الفرع الجديد
                            </label>
                            <select 
                                name="to_branch_id" 
                                id="to_branch_id" 
                                class="form-control select2 @error('to_branch_id') is-invalid @enderror"
                                required
                            >
                                <option value="">-- اختر الفرع --</option>
                                @foreach($branches as $branch)
                                    <option 
                                        value="{{ $branch->id }}" 
                                        {{ old('to_branch_id') == $branch->id ? 'selected' : '' }}
                                    >
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('to_branch_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Second Row: Notes -->
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="note" class="form-label">
                                <i class="fa fa-info-circle"></i> ملاحظات
                            </label>
                            <textarea 
                                name="note" 
                                id="note" 
                                rows="4" 
                                class="form-control @error('note') is-invalid @enderror"
                                placeholder="أدخل ملاحظات حول النقل (اختياري)"
                            >{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fa fa-check"></i> حفظ
                        </button>
                        <a href="{{ route(get_area_name() . '.doctor-transfers.index') }}" class="btn btn-secondary">
                            <i class="fa fa-ban"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
