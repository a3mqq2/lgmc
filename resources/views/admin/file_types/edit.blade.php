@extends('layouts.'.get_area_name())
@section('title', 'تعديل نوع مستند')
@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-light">تعديل نوع مستند</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route(get_area_name().'.file-types.update', $fileType->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $fileType->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">مخصص لـ</label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="">حدد نوع</option>
                                <option value="doctor" @if(old('type', $fileType->type) == 'doctor') selected @endif>طبيب</option>
                                <option value="medical_facility" @if(old('type', $fileType->type) == 'medical_facility') selected @endif>منشأة طبية</option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Doctor Fields (Initially Hidden) -->
                        <div id="doctor-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="doctor_rank_id" class="form-label">الصفة</label>
                                <select name="doctor_rank_id" id="doctor_rank_id" class="form-control">
                                    <option value="">  الكل  </option>
                                    @foreach ($doctor_ranks as $doctor_rank)
                                        <option value="{{ $doctor_rank->id }}" @if(old('doctor_rank_id', $fileType->doctor_rank_id) == $doctor_rank->id) selected @endif>
                                            {{ $doctor_rank->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="doctor_type" class="form-label">نوع الطبيب</label>
                                <select name="doctor_type" id="doctor_type" class="form-control">
                                    <option value="">الكل</option>
                                    <option value="foreign" @if(old('doctor_type', $fileType->doctor_type) == 'foreign') selected @endif>أجنبي</option>
                                    <option value="visitor" @if(old('doctor_type', $fileType->doctor_type) == 'visitor') selected @endif>زائر</option>
                                    <option value="palestinian" @if(old('doctor_type', $fileType->doctor_type) == 'palestinian') selected @endif>فلسطيني</option>
                                    <option value="libyan" @if(old('doctor_type', $fileType->doctor_type) == 'libyan') selected @endif>ليبي</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="checkbox" name="is_required" id="is_required" class="mr-3" value="1" @if(old('is_required', $fileType->is_required)) checked @endif>
                            <label for="is_required" style="margin-right: 8px !important;">الملف اجباري</label>
                        </div>

                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Toggle the doctor-related fields based on the selected type
    document.getElementById('type').addEventListener('change', function() {
        const doctorFields = document.getElementById('doctor-fields');
        if (this.value === 'doctor') {
            doctorFields.style.display = 'block';
        } else {
            doctorFields.style.display = 'none';
        }
    });

    // Initial check for existing value (if the form is pre-filled)
    if (document.getElementById('type').value === 'doctor') {
        document.getElementById('doctor-fields').style.display = 'block';
    }
</script>
@endsection
