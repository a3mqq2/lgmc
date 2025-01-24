@extends('layouts.'.get_area_name())
@section('title', 'تعديل التسعيرة')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title text-light mb-0">تعديل التسعيرة</h5>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name().'.pricings.update', $pricing) }}" method="POST">
                    @csrf
                    @method('PUT')
                    {{-- Amount --}}
                    <div class="mb-3">
                        <label for="amount" class="form-label">القيمة</label>
                        <input type="number" step="0.01" name="amount" id="amount" 
                               class="form-control @error('amount') is-invalid @enderror" 
                               value="{{ old('amount', $pricing->amount) }}" required>
                        @error('amount')
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
