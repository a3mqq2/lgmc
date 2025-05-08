@extends('layouts.' . get_area_name())
@section('title', 'إنشاء منشأة طبية جديدة')

@section('content')
<form action="{{ route(get_area_name().'.medical-facilities.store') }}" method="POST" enctype="multipart/form-data">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">إنشاء منشأة طبية جديدة</h4>
                </div>
                <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="serial_number" class="form-label">رقم المنشأة</label>
                                    <input type="text" class="form-control" id="name" name="serial_number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم المنشأة</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            @if (get_area_name() == "admin")
                            <div class="col-md-12">
                                <label for="branch">الفرع</label>
                                <select name="branch_id" id="" class="form-control select2">
                                    <option value="">حدد الفرع</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @else 
                            <input type="hidden" name="branch_id" value="{{auth()->user()->branch_id}}">
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="medical_facility_type_id" class="form-label">نوع المنشأة الطبية</label>
                                    <select class="form-control" id="medical_facility_type_id" name="medical_facility_type_id"
                                        required>
                                        @foreach ($medicalFacilityTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">الموقع</label>
                                    <input type="text" name="address" class="form-control"  id="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">رقم الهاتف</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" maxlength="10" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="">مالك النشاط</label>
                                <select name="manager_id" id="licensable_id" class="form-control" 
                                    @if (get_area_name() == "user")
                                        required
                                    @endif
                                >
                                    <option value="">حدد مالك نشاط</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{$doctor->id}}">{{$doctor->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary mb-3">إنشاء</button>
</form>

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
    setupSelect2('#licensable_id', '/search-licensables?branch_id=' + branch_id + "&justactive=1", 'ابحث عن مالك النشاط...');
});

    </script>
@endsection
