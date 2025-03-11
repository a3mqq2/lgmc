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
                        <input type="hidden" id="licensable_type" name="licensable_type" 
                               value="{{ request('type') === 'doctors' ? 'App\Models\Doctor' : 'App\Models\MedicalFacility' }}">
                        @endif

                        <!-- المرخص -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="licensable_id" class="form-label">اختر المرخص</label>
                                <select id="licensable_id" name="licensable_id" class="form-control select2-search">
                                    <option value="">اختر المرخص</option>
                                </select>
                            </div>

                            @if (request('type') == "doctors")
                            <div class="mb-3">
                                <label for="medical_facility_id" class="form-label">
                                    @if (request('doctor_type') == App\Enums\DoctorType::Visitor->value)
                                        الشركة المستضيفه         
                                    @else 
                                        مكان العمل
                                    @endif
                                </label>
                                <select name="medical_facility_id" id="medical_facility_id" class="form-control select2-search">
                                    <option value="">اختر مكان العمل</option>
                                </select>
                            </div>
                            @endif
                        </div>

                        @php
                            $expiryDate = request('doctor_type') === App\Enums\DoctorType::Visitor->value
                                ? Carbon\Carbon::now()->addMonths(6)->subDay()->toDateString() // 6 أشهر - يوم
                                : Carbon\Carbon::now()->addYear()->subDay()->toDateString(); // سنة - يوم
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
                                <input type="date" class="form-control" id="expiry_date" value="{{ $expiryDate }}" name="expiry_date" required>
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
    <!-- Include Select2 CSS and JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
$(window).on('load', function() {
    console.log('Page Loaded');
    function setupSelect2(selector, url, placeholderText) {
        $(selector).select2({
            placeholder: placeholderText,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { query: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function(item) {
                            return { id: item.id, text: item.name };
                        })
                    };
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                },
                cache: true
            },
            minimumInputLength: 2
        });
    }

    let branch_id  = '{{ auth()->user()->branch_id }}';
    let licencable_type = '{{ request('type') }}';
    if(licencable_type == "facilities") {
        setupSelect2('#licensable_id', '/search-facilities?branch_id=' + branch_id, 'ابحث عن المرخص...');
    } else {
        setupSelect2('#licensable_id', '/search-licensables?branch_id=' + branch_id, 'ابحث عن المرخص...');
        setupSelect2('#medical_facility_id', '/search-facilities?branch_id=' + branch_id, 'ابحث عن مكان العمل...');
    }
});

    </script>
@endsection
