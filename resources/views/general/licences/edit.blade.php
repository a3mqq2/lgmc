@extends('layouts.' . get_area_name())
@section('title', 'تعديل اذن مزاولة')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                تعديل اذن مزاولة
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name().'.licences.update', $licence->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <input type="hidden" id="licensable_type" name="licensable_type" value="{{ $licence->licensable_type }}">
                        
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="licensable_id" class="form-label">اختر المرخص</label>
                                <select id="licensable_id" name="licensable_id" class="form-control chosen-select">
                                    <option value="">اختر المرخص</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" data-type="App\Models\Doctor" {{ $licence->licensable_id == $doctor->id && $licence->licensable_type == 'App\Models\Doctor' ? 'selected' : '' }}>{{ $doctor->name }}</option>
                                    @endforeach
                                    @foreach ($medicalFacilities as $facility)
                                        <option value="{{ $facility->id }}" data-type="App\Models\MedicalFacility" {{ $licence->licensable_id == $facility->id && $licence->licensable_type == 'App\Models\MedicalFacility' ? 'selected' : '' }}>{{ $facility->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- @if ($licence->licensable_type == "App\Models\MedicalFacility") --}}
                        <div class="col-md-12" id="">
                            <div class="mb-3">
                                <label for="doctor_id" class="form-label">اختر الممثل</label>
                                <select id="doctor_id" name="doctor_id" class="form-control chosen-select">
                                    <option value="">اختر الممثل</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ $licence->doctor_id == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="issued_date" class="form-label">تاريخ الإصدار</label>
                                <input type="date" class="form-control" id="issued_date" name="issued_date" value="{{ $licence->issued_date }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expiry_date" class="form-label">تاريخ الانتهاء</label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ $licence->expiry_date }}" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">تحديث</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
    <!-- Include Chosen CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.chosen-select').chosen({ width: '100%' });

        const licensableTypeInput = document.getElementById('licensable_type');
        const licensableIdSelect = document.getElementById('licensable_id');
        const representerSelectContainer = document.getElementById('representer_select');

        function filterLicensableOptions() {
            const selectedType = licensableTypeInput.value;
            const options = licensableIdSelect.querySelectorAll('option');

            options.forEach(option => {
                option.style.display = option.getAttribute('data-type') === selectedType ? 'block' : 'none';
            });

            $(licensableIdSelect).val('{{ $licence->licensable_id }}').trigger('chosen:updated');

            if (selectedType === 'App\Models\MedicalFacility') {
                representerSelectContainer.style.display = 'block';
                $('#doctor_id').val('{{ $licence->doctor_id }}').trigger('chosen:updated');
            } else {
                representerSelectContainer.style.display = 'none';
            }
        }

        filterLicensableOptions();
    });
</script>
@endsection
