@extends('layouts.' . get_area_name())

@section('title', 'تعديل طلب تسعير')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-light">
                <h4 class="card-title"><i class="fas fa-edit"></i> تعديل طلب تسعير</h4>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name() . '.pricing-requests.update', $pricingRequest->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">اسم الطلب</label>
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $pricingRequest->name) }}" 
                                placeholder="أدخل اسم الطلب"
                            >
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Entity Type -->
                        <div class="col-md-6">
                            <label for="entity_type" class="form-label">نوع الكيان</label>
                            <select 
                                class="form-control @error('entity_type') is-invalid @enderror" 
                                id="entity_type" 
                                name="entity_type"
                                onchange="toggleDoctorFields(this.value)"
                            >
                                <option value="doctor" {{ old('entity_type', $pricingRequest->entity_type) == 'doctor' ? 'selected' : '' }}>طبيب</option>
                                <option value="company" {{ old('entity_type', $pricingRequest->entity_type) == 'company' ? 'selected' : '' }}>شركة</option>
                            </select>
                            @error('entity_type')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Doctor-Specific Fields -->
                    <div id="doctor-fields" style="display: {{ old('entity_type', $pricingRequest->entity_type) == 'doctor' ? 'block' : 'none' }}">
                        <div class="row mb-3">
                            <!-- Doctor Type -->
                            <div class="col-md-6">
                                <label for="doctor_type" class="form-label">نوع الطبيب</label>
                                <select 
                                    class="form-control @error('doctor_type') is-invalid @enderror" 
                                    id="doctor_type" 
                                    name="doctor_type"
                                >
                                    <option value="libyan" {{ old('doctor_type', $pricingRequest->doctor_type) == 'libyan' ? 'selected' : '' }}>ليبي</option>
                                    <option value="foreign" {{ old('doctor_type', $pricingRequest->doctor_type) == 'foreign' ? 'selected' : '' }}>أجنبي</option>
                                    <option value="visitor" {{ old('doctor_type', $pricingRequest->doctor_type) == 'visitor' ? 'selected' : '' }}>زائر</option>
                                    <option value="palestinian" {{ old('doctor_type', $pricingRequest->doctor_type) == 'palestinian' ? 'selected' : '' }}>فلسطيني</option>
                                </select>
                                @error('doctor_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Doctor Rank -->
                            <div class="col-md-6">
                                <label for="doctor_rank_id" class="form-label">الصفة الطبيب</label>
                                <select 
                                    class="form-control @error('doctor_rank_id') is-invalid @enderror" 
                                    id="doctor_rank_id" 
                                    name="doctor_rank_id"
                                >
                                    <option value="">للكل</option>
                                    @foreach($doctorRanks as $rank)
                                    <option 
                                        value="{{ $rank->id }}" 
                                        {{ old('doctor_rank_id', $pricingRequest->doctor_rank_id) == $rank->id ? 'selected' : '' }}
                                    >{{ $rank->name }}</option>
                                    @endforeach
                                </select>
                                @error('doctor_rank_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="amount" class="form-label">الرسوم</label>
                            <input 
                                type="number" 
                                step="0.01" 
                                class="form-control @error('amount') is-invalid @enderror" 
                                id="amount" 
                                name="amount" 
                                value="{{ old('amount', $pricingRequest->amount) }}" 
                                placeholder="أدخل الرسوم"
                            >
                            @error('amount')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="col-md-6">
                            <label for="active" class="form-label">الحالة</label>
                            <select 
                                class="form-control @error('active') is-invalid @enderror" 
                                id="active" 
                                name="active"
                            >
                                <option value="1" {{ old('active', $pricingRequest->active) == 1 ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ old('active', $pricingRequest->active) == 0 ? 'selected' : '' }}>غير نشط</option>
                            </select>
                            @error('active')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ التعديلات
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleDoctorFields(entityType) {
        const doctorFields = document.getElementById('doctor-fields');
        doctorFields.style.display = entityType === 'doctor' ? 'block' : 'none';
    }
</script>
@endsection
