@extends('layouts.' . get_area_name())

@section('title', 'إنشاء تذكرة جديدة')

@section('styles')
    <style>
        .select2-container {
    width: 100% !important;
}

.form-label {
    font-weight: bold;
    margin-bottom: 5px;
}

#user_select_wrapper, #doctor_select_wrapper {
    display: none;
}


.selectize-control.form-control.select2.single.rtl
{
    display: none !important;
}
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h4 class="card-title">
                    <i class="fa fa-ticket"></i> إنشاء تذكرة جديدة
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name() . '.tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">
                                <i class="fa fa-heading"></i> عنوان التذكرة
                            </label>
                            <input 
                                type="text" 
                                name="title" 
                                id="title" 
                                class="form-control @error('title') is-invalid @enderror" 
                                value="{{ old('title') }}"
                                placeholder="أدخل عنوان التذكرة"
                                required
                            >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="department" class="form-label">
                                <i class="fa fa-sitemap"></i> القسم
                            </label>
                            <select 
                                name="department" 
                                id="department" 
                                class="form-control @error('department') is-invalid @enderror"
                                required
                            >
                                <option value="">-- اختر القسم --</option>
                                @foreach(App\Enums\Department::cases() as $dept)
                                    <option 
                                        value="{{ $dept->value }}" 
                                        {{ old('department') == $dept->value ? 'selected' : '' }}
                                    >
                                        {{ $dept->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="row g-3 mt-7">
                        <div class="col-md-6">
                            <label for="ticket_type" class="form-label">
                                <i class="fa fa-user-md"></i> نوع التذكرة
                            </label>
                            <select 
                                name="ticket_type" 
                                id="ticket_type" 
                                class="form-control @error('ticket_type') is-invalid @enderror"
                                required
                            >
                                <option value="-" selected>-- اختر نوع المنشيء --</option>
                                <option value="user">مستخدم</option>
                                <option value="doctor">طبيب</option>
                            </select>
                            @error('ticket_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="priority" class="form-label">
                                <i class="fa fa-exclamation-circle"></i> الأولوية
                            </label>
                            <select 
                                name="priority" 
                                id="priority" 
                                class="form-control @error('priority') is-invalid @enderror"
                                required
                            >
                                <option value="">-- اختر الأولوية --</option>
                                @foreach(App\Enums\Priority::cases() as $priority)
                                    <option 
                                        value="{{ $priority->value }}" 
                                        {{ old('priority') == $priority->value ? 'selected' : '' }}
                                    >
                                        {{ $priority->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="row g-3">
                        <div class="col-md-12" id="user_select_wrapper" style="display: none;">
                            <label for="init_user_id" class="form-label">
                                <i class="fa fa-user"></i> اختر المستخدم
                            </label>
                            <select name="init_user_id" id="init_user_id" class="form-control select2"></select>
                        </div>

                        <div class="col-md-12" id="doctor_select_wrapper" style="display: none;">
                            <label for="init_doctor_id" class="form-label">
                                <i class="fa fa-stethoscope"></i> اختر الطبيب
                            </label>
                            <select name="init_doctor_id" id="init_doctor_id" class="form-control select2"></select>
                        </div>
                    </div>


                    {{-- ticket body --}}
                    <div class="row g-3 mt-1">
                        <div class="col-md-12">
                            <label for="body" class="form-label">
                                <i class="fa fa-file
                                "></i> محتوى التذكرة
                            </label>
                            <textarea 
                                name="body" 
                                id="body" 
                                class="form-control @error('body') is-invalid @enderror" 
                                rows="5"
                                placeholder="أدخل محتوى التذكرة"
                                required
                            >{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">

                        <div class="col-md-6">
                            <label for="category" class="form-label">
                                <i class="fa fa-folder-open"></i> الفئة
                            </label>
                            <select 
                                name="category" 
                                id="category" 
                                class="form-control @error('category') is-invalid @enderror"
                                required
                            >
                                <option value="">-- اختر الفئة --</option>
                                @foreach(App\Enums\Category::cases() as $cat)
                                    <option 
                                        value="{{ $cat->value }}" 
                                        {{ old('category') == $cat->value ? 'selected' : '' }}
                                    >
                                        {{ $cat->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- for attac --}}

                        <div class="col-md-6">
                            <label for="attachment" class="form-label">
                                <i class="fa fa-paperclip"></i> المرفقات
                            </label>
                            <input 
                                type="file" 
                                name="attachment" 
                                id="attachment" 
                                class="form-control @error('attachment') is-invalid @enderror" 
                            >
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>


                    <hr>

                    <input type="hidden" id="branch_id" name="branch_id" value="{{ auth()->user()->branch_id }}">

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check"></i> حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
    $(document).ready(function() {
        console.log('Page Loaded');

        // دالة إعداد الـ Select2
        function setupSelect2(selector, url, placeholderText) {
            $(selector).select2({
                placeholder: placeholderText,
                allowClear: true,
                width: '100%',
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
                    }
                },
                minimumInputLength: 2
            });
        }

        // دالة التحكم بإظهار واختفاء الحقول حسب نوع التذكرة
        function toggleFields() {
            let ticketType = $('#ticket_type').val();
            let branch_id = $('#branch_id').val();

            if (ticketType === 'user') {
                $('#user_select_wrapper').show();
                $('#doctor_select_wrapper').hide();
                setupSelect2('#init_user_id', '/search-users?branch_id=' + branch_id, 'ابحث عن مستخدم...');
                // $('#init_doctor_id').select2('destroy').empty();
            } else if (ticketType === 'doctor') {
                $('#doctor_select_wrapper').show();
                $('#user_select_wrapper').hide();
                setupSelect2('#init_doctor_id', '/search-licensables?branch_id=' + branch_id, 'ابحث عن طبيب...');
                // $('#init_user_id').select2('destroy').empty();
            } else {
                $('#user_select_wrapper, #doctor_select_wrapper').hide();
            }
        }

        // استدعاء التحقق عند تغيير نوع التذكرة
        $('#ticket_type').on('change', function() {
            toggleFields();
        });

        // تهيئة القيم عند تحميل الصفحة
        toggleFields();
    });
    </script>
@endsection
