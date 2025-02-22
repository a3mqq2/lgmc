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
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="serial_number" class="form-label">رقم المنشأة</label>
                                    <input type="text" class="form-control" id="name" name="serial_number" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم المنشأة</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="name" class="form-label"> رقم السجل التجاري </label>
                                    <input type="text" class="form-control" id="commerical_number" name="commerical_number" required>
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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="address" class="form-label">الإقامة</label>
                                    <input type="text" name="address" class="form-control"  id="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">رقم الهاتف</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" maxlength="10" required>
                                </div>
                            </div>
                        </div>
                        
                       
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="activity_start_date" class="form-label"> تاريخ بدء النشاط  </label>
                                    <input type="date" class="form-control" id="activity_start_date" name="activity_start_date"  required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="">نوع نشاط الشركة</label>
                                    <select name="activity_type" id="" class="form-control">
                                        <option value="">حدد نوع النشاط</option>
                                        <option value="commercial_record">سجل تجاري</option>
                                        <option value="negative_certificate">شهادة سلبية</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
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
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">📑 المستندات المطلوبة</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($file_types as $file_type)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="document-card shadow-sm border rounded text-center p-3 position-relative">
                                    <div class="document-icon mb-3">
                                        <i class="fas fa-file-upload fa-3x text-primary"></i>
                                    </div>
                                    <h6 class="document-title mb-2">
                                        {{ $file_type->name }}
                                        @if ($file_type->is_required)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </h6>
                                    <div class="custom-file">
                                        <input type="file" name="documents[{{ $file_type->id }}]" 
                                               class="custom-file-input"
                                               id="file_{{ $file_type->id }}"
                                               @if($file_type->is_required) required @endif>
                                        <label class="custom-file-label" for="file_{{ $file_type->id }}">
                                            اختر ملف
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        الملف يجب أن يكون بصيغة <b>PDF</b> أو صورة
                                    </small>
                                    <div id="status_{{ $file_type->id }}" class="mt-2 text-muted">
                                        🔄 لم يتم الرفع بعد
                                    </div>
                                    @if ($file_type->is_required)
                                        <div class="alert alert-warning mt-3 p-2 text-center rounded-lg shadow-sm"
                                             style="background: linear-gradient(135deg, #fff8e1, #ffe0b2); 
                                                    border-left: 5px solid #ff9800;
                                                    color: #5d4037;">
                                            <i class="fas fa-exclamation-circle"></i> 
                                            <strong>ملف إلزامي:</strong> يُرجى التأكد من رفع هذا الملف.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
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
