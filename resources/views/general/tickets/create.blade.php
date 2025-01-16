@extends('layouts.' . get_area_name())

@section('title', 'إنشاء تذكرة جديدة')

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
                {{-- Include enctype to handle file uploads --}}
                <form action="{{ route(get_area_name() . '.tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- First Row: Title & Department --}}
                    <div class="row g-3">
                        {{-- Ticket Title --}}
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

                        {{-- Department (Example) --}}
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
                    </div> {{-- End Row --}}

                    <hr>

                    {{-- Second Row: Ticket Body & Category --}}
                    <div class="row g-3">
                        {{-- Ticket Body/Description --}}
                        <div class="col-md-12">
                            <label for="body" class="form-label">
                                <i class="fa fa-info-circle"></i> وصف التذكرة
                            </label>
                            <textarea 
                                name="body" 
                                id="body" 
                                rows="4" 
                                class="form-control @error('body') is-invalid @enderror"
                                placeholder="أدخل وصفًا مفصلًا للتذكرة"
                                required
                            >{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Category (Example) --}}
                        <div class="col-md-12">
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
                    </div> {{-- End Row --}}

                    <hr>

                    {{-- Third Row: Ticket Type (User/Doctor) & Priority --}}
                    <div class="row g-3">
                        {{-- Ticket Type (User/Doctor) --}}
                        
                        @if (auth()->user()->permissions->where('name', 'manage-all-tickets')->count())
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
                               <option value="">-- اختر نوع المنشيء --</option>
                               <option value="user" {{ old('ticket_type') === 'user' ? 'selected' : '' }}>مستخدم</option>
                               <option value="doctor" {{ old('ticket_type') === 'doctor' ? 'selected' : '' }}>طبيب</option>
                           </select>
                           @error('ticket_type')
                               <div class="invalid-feedback">{{ $message }}</div>
                           @enderror
                       </div>
                       @else 
                       <input type="hidden" name="ticket_type" value="user">
                        @endif


                        {{-- Priority (Example) --}}
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
                    </div> {{-- End Row --}}

                    <hr>

                    {{-- Fourth Row: User/Doctor Dropdowns & Attachment --}}
                    <div class="row g-3">

                        {{-- User Dropdown (shows if "user" selected) --}}
                        <div class="col-md-6" id="user_select" style="display: none;">
                            <label for="init_user_id" class="form-label">
                                <i class="fa fa-user"></i> اختر المستخدم
                            </label>
                            <select 
                                name="init_user_id" 
                                id="init_user_id" 
                                class="form-control @error('init_user_id') is-invalid @enderror"
                            >
                                <option value="">-- اختر مستخدم --</option>
                                @foreach($users as $user)
                                    <option 
                                        value="{{ $user->id }}"
                                        {{ old('init_user_id') == $user->id ? 'selected' : '' }}
                                    >
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('init_user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Doctor Dropdown (shows if "doctor" selected) --}}
                        <div class="col-md-6" id="doctor_select" style="display: none;">
                            <label for="init_doctor_id" class="form-label">
                                <i class="fa fa-stethoscope"></i> اختر الطبيب
                            </label>
                            <select 
                                name="init_doctor_id" 
                                id="init_doctor_id" 
                                class="form-control @error('init_doctor_id') is-invalid @enderror"
                            >
                                <option value="">-- اختر طبيب --</option>
                                @foreach($doctors as $doctor)
                                    <option 
                                        value="{{ $doctor->id }}"
                                        {{ old('init_doctor_id') == $doctor->id ? 'selected' : '' }}
                                    >
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('init_doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Attachment Field --}}
                        <div class="col-md-6 mt-3">
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

                    </div> {{-- End Row --}}

                    <hr>

                    {{-- Form Actions --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fa fa-check"></i> حفظ
                        </button>
                        <a href="{{ route(get_area_name() . '.tickets.index') }}" class="btn btn-secondary">
                            <i class="fa fa-ban"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle User/Doctor select fields based on ticket_type
    const ticketTypeSelect = document.getElementById('ticket_type');
    const userSelect       = document.getElementById('user_select');
    const doctorSelect     = document.getElementById('doctor_select');

    function toggleSelects() {
        if (ticketTypeSelect.value === 'doctor') {
            doctorSelect.style.display = 'block';
            userSelect.style.display   = 'none';
        } else if (ticketTypeSelect.value === 'user') {
            userSelect.style.display   = 'block';
            doctorSelect.style.display = 'none';
        } else {
            // If no selection or empty, hide both
            userSelect.style.display   = 'none';
            doctorSelect.style.display = 'none';
        }
    }

    // Run toggleSelects on page load (in case there's an old value)
    toggleSelects();

    // Run toggleSelects whenever the ticket_type changes
    ticketTypeSelect.addEventListener('change', toggleSelects);
</script>
@endsection
