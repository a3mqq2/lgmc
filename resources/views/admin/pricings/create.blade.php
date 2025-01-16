@extends('layouts.'.get_area_name())
@section('title', 'إضافة تسعيرة جديدة')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title text-light mb-0">إضافة تسعيرة جديدة</h5>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name().'.pricings.store') }}" method="POST">
                    @csrf

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">الوصف</label>
                        <input type="text" name="name" id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Amount --}}
                    <div class="mb-3">
                        <label for="amount" class="form-label">القيمة</label>
                        <input type="number" step="0.01" name="amount" id="amount" 
                               class="form-control @error('amount') is-invalid @enderror" 
                               value="{{ old('amount') }}" required>
                        @error('amount')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div class="mb-3">
                        <label for="type" class="form-label">النوع</label>
                        <select name="type" id="type" 
                                class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">اختر النوع</option>
                            <option value="membership" {{ old('type') == 'membership' ? 'selected' : '' }}>عضوية</option>
                            <option value="license" {{ old('type') == 'license' ? 'selected' : '' }}>إذن مزاولة</option>
                            <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>خدمة</option>
                            <option value="penalty" {{ old('type') == 'penalty' ? 'selected' : '' }}> غرامة </option>
                        </select>
                        @error('type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Entity Type --}}
                    <div class="mb-3">
                        <label for="entity_type" class="form-label">الجهة المستهدفة</label>
                        <select name="entity_type" id="entity_type" 
                                class="form-select @error('entity_type') is-invalid @enderror" required>
                            <option value="">اختر الجهة</option>
                            <option value="doctor" {{ old('entity_type') == 'doctor' ? 'selected' : '' }}>طبيب</option>
                            <option value="medical_facility" {{ old('entity_type') == 'medical_facility' ? 'selected' : '' }}>منشأة طبية</option>
                        </select>
                        @error('entity_type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Doctor Type (Conditional Field) --}}
                    <div class="mb-3" id="doctorTypeContainer" style="display: none;">
                        <label for="doctor_type" class="form-label">نوع الطبيب</label>
                        <select name="doctor_type" id="doctor_type" 
                                class="form-select @error('doctor_type') is-invalid @enderror">
                            <option value="">اختر نوع الطبيب</option>
                            <option value="libyan" {{ old('doctor_type') == 'libyan' ? 'selected' : '' }}>ليبي</option>
                            <option value="foreign" {{ old('doctor_type') == 'foreign' ? 'selected' : '' }}>أجنبي</option>
                            <option value="visitor" {{ old('doctor_type') == 'visitor' ? 'selected' : '' }}>زائر</option>
                            <option value="palestinian" {{ old('doctor_type') == 'palestinian' ? 'selected' : '' }}>فلسطيني</option>
                        </select>
                        @error('doctor_type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">حفظ</button>
                        <a href="{{ route(get_area_name().'.pricings.index') }}" class="btn btn-secondary ms-2">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const entityTypeSelect = document.getElementById('entity_type');
        const doctorTypeContainer = document.getElementById('doctorTypeContainer');

        function toggleDoctorType() {
            if (entityTypeSelect.value === 'doctor') {
                doctorTypeContainer.style.display = 'block';
            } else {
                doctorTypeContainer.style.display = 'none';
            }
        }

        // Initial check on page load
        toggleDoctorType();

        // Listen for changes
        entityTypeSelect.addEventListener('change', toggleDoctorType);
    });
</script>
@endsection

@section('styles')
<style>
    .form-label {
        font-weight: bold;
    }
</style>
@endsection
