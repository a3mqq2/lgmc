@extends('layouts.' . get_area_name())
@section('title', 'إنشاء اذن مزاولة جديد')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                انشاء اذن مزاولة جديد
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name().'.licences.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        @if(request('type'))
                        <input type="hidden" id="licensable_type" name="licensable_type" value="{{ request('type') === 'doctors' ? 'App\Models\Doctor' : 'App\Models\MedicalFacility' }}">
                        @endif
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="licensable_id" class="form-label">اختر المرخص</label>
                                <select id="licensable_id" name="licensable_id" class="form-control chosen-select">
                                    <option value="">اختر المرخص</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" data-type="App\Models\Doctor">{{ $doctor->name }}</option>
                                    @endforeach
                                    @foreach ($medicalFacilities as $facility)
                                        <option value="{{ $facility->id }}" data-type="App\Models\MedicalFacility">{{ $facility->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if (request('type') == "doctors")
                            <label for="">
                                @if (request('doctor_type') == App\Enums\DoctorType::Visitor->value)
                                     الشركة المستضيفه         
                                    @else 
                                    مكان العمل
                                @endif
                            </label>
                            <select name="medical_facility_id" id="" class="chosen-select form-control mb-3">
                                <option value="-">
                                    @if (request('doctor_type') == App\Enums\DoctorType::Visitor->value)
                                        الشركة المستضيفه
                                        @else 
                                        مكان العمل 
                                    @endif
                                </option>
                                @foreach ($medicalFacilities as $facility)
                                     <option value="{{ $facility->id }}" data-type="App\Models\MedicalFacility">{{ $facility->name }}</option>
                                 @endforeach
                            </select>
                            @endif
                        </div>

                        @php
                                $expiryDate = request('doctor_type') === App\Enums\DoctorType::Visitor->value
                                    ? Carbon\Carbon::now()->addMonths(6)->toDateString()
                                    : Carbon\Carbon::now()->addYear()->toDateString();
                        @endphp
                       
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="issued_date" class="form-label">تاريخ الإصدار</label>
                                <input type="date" class="form-control" id="issued_date" value="{{date('Y-m-d')}}" name="issued_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expiry_date" class="form-label">تاريخ الانتهاء</label>
                                <input type="date" class="form-control" id="expiry_date" 
                                    value="{{ $expiryDate }}"
                                    name="expiry_date" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">إنشاء</button>
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
            const selectedType = licensableTypeInput ? licensableTypeInput.value : '';
            const options = licensableIdSelect.querySelectorAll('option');

            options.forEach(option => {
                option.style.display = option.getAttribute('data-type') === selectedType ? 'block' : 'none';
            });

            $(licensableIdSelect).val('').trigger('chosen:updated');

            if (selectedType === 'App\Models\MedicalFacility') {
                representerSelectContainer.style.display = 'block';
            } else {
                representerSelectContainer.style.display = 'none';
            }

            $('.chosen-select').trigger('chosen:updated');
        }

        if (licensableTypeInput) {
            filterLicensableOptions();
        }
    });
</script>
@endsection
