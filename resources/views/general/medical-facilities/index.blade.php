@extends('layouts.' . get_area_name())

@section('title', 'قائمة المنشآت الطبية')

@section('styles')
<style>
    /* ---------- Filter Form Styling ---------- */
    .filter-form .form-label {
        font-weight: 600;
        font-size: 0.95rem;
    }
    .filter-form .form-control,
    .filter-form .form-select {
        min-width: 150px;
        border-radius: 0.5rem;
        border: 1px solid #ced4da;
    }
    .filter-form .btn {
        border-radius: 0.5rem;
    }

    /* ---------- Table Styling ---------- */
    .table thead th {
        background: #f0f2ff;
        font-weight: 600;
        color: #333;
        vertical-align: middle;
    }
    .table tbody tr:hover {
        background: #f9faff;
    }
    .table td,
    .table th {
        padding: 0.75rem;
        vertical-align: middle;
    }
    .table .badge {
        padding: 0.5em 0.75em;
        font-size: 0.9rem;
    }

    /* ---------- Card Styling ---------- */
    .card {
        border: none;
        border-radius: 0.75rem;
    }
    .card .card-header {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
    }
    .card.shadow-sm {
        box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
    }

    /* ---------- Responsive Tweaks ---------- */
    @media (max-width: 768px) {
        .col-md-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .col-md-2 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-light">
                <h4 class="card-title mb-0">قائمة المنشآت الطبية</h4>
            </div>
            <div class="card-body">
                {{-- ---------- فلترة وبحث ---------- --}}
                <form method="GET" action="{{ route(get_area_name() . '.medical-facilities.index') }}" 
                      class="row gy-2 gx-3 align-items-end mb-4 filter-form">
                    {{-- بحث بالاسم --}}
                    <div class="col-md-3">
                        <label class="form-label">بحث بالاسم</label>
                        <input type="text" name="q" value="{{ request('q') }}" 
                               class="form-control" placeholder="أدخل الاسم">
                    </div>


                    <div class="col-md-3">
                        <label class="form-label">بحث بالرقم النقابي</label>
                        <input type="text" name="code" value="{{ request('code') }}" 
                               class="form-control" placeholder="أدخل الرقم النقابي">
                    </div>


                    
                    {{-- بحث برقم الهاتف --}}
                    <div class="col-md-3">
                        <label class="form-label">بحث برقم الهاتف</label>
                        <input type="text" name="phone" value="{{ request('phone') }}" 
                               class="form-control" placeholder="أدخل رقم الهاتف">
                    </div>

                    {{-- فلتر نوع المنشأة --}}
                    <div class="col-md-3">
                        <label class="form-label">نوع المنشأة</label>
                        <select name="ownership" class="form-select">
                            <option value="">كل الأنواع</option>
                            <option value="private_clinic"  
                                {{ request('ownership') == 'private_clinic' ? 'selected' : '' }}>
                                عيادة خاصة
                            </option>
                            <option value="medical_services" 
                                {{ request('ownership') == 'medical_services' ? 'selected' : '' }}>
                                خدمات طبية
                            </option>
                        </select>
                    </div>

                    {{-- فلتر الحالة --}}
                    <div class="col-md-3">
                        <label class="form-label">حالة الاشتراك</label>
                        <select name="status" class="form-select">
                            <option value="">كل الحالات</option>
                            <option value="active"   
                                {{ request('status') == 'active'   ? 'selected' : '' }}>مفعل</option>
                            <option value="expired" 
                                {{ request('status') == 'expired' ? 'selected' : '' }}> منتهي الصلاحية  </option>
                            <option value="pending"  
                                {{ request('status') == 'pending'  ? 'selected' : '' }}>قيد المراجعة</option>
                        </select>
                    </div>


                    {{-- زر البحث --}}
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-search me-1"></i> بحث
                        </button>
                    </div>
                </form>

                {{-- ---------- جدول النتائج ---------- --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>نوع المنشأة</th>
                                <th>الفئة</th>
                                <th>الموقع</th>
                                <th>الهاتف</th>
                                <th>الطبيب</th>
                                <th>تاريخ التسجيل</th>
                                <th>الحالة</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($medicalFacilities as $facility)
                                <tr>
                                    <td>{{ $facility->code }}</td>
                                    <td>{{ $facility->name }}</td>
                                    <td>
                                        {{ $facility->type == "private_clinic" 
                                            ? "عيادة خاصة" 
                                            : "خدمات طبية" }}
                                    </td>
                                    <td>{{ $facility->type?->name ?? '—' }}</td>
                                    <td>{{ $facility->address }}</td>
                                    <td>{{ $facility->phone_number }}</td>
                                    <td>{{ $facility->manager?->name ?? '—' }}</td>
                                    <td>{{ $facility->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge {{ $facility->membership_status->badgeClass() }}">
                                            {{ $facility->membership_status->label() }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="{{ route(get_area_name().'.medical-facilities.show', $facility) }}" 
                                           class="btn btn-sm btn-primary me-1">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route(get_area_name().'.medical-facilities.edit', $facility) }}" 
                                           class="btn btn-sm btn-warning me-1">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route(get_area_name().'.medical-facilities.destroy', $facility) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذه المنشأة؟')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ---------- ربط التصفح ---------- --}}
                {{ $medicalFacilities->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
