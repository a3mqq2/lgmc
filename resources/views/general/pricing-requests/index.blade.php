@extends('layouts.' . get_area_name())

@section('title', 'طلبات التسعير')

@section('content')
@php
use App\Enums\EntityType;
use App\Enums\LicenseType;
use App\Enums\DoctorType;
use App\Enums\ActiveStatus;

$isDoctorEntity = request()->input('entity_type') == 'doctor';
$showDoctorColumns = $isDoctorEntity && request()->input('entity_type') != 'company';
@endphp


<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-light d-flex align-items-center">
                <h4 class="card-title m-0"><i class="fas fa-list"></i> طلبات التسعير</h4>
            </div>
            <div class="card-body">
                <!-- Entity Type Tabs -->
                <ul class="nav nav-tabs mb-4" id="entityTypeTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a 
                            class="nav-link {{ $isDoctorEntity ? 'active' : '' }}" 
                            href="{{ route(get_area_name() . '.pricing-requests.index', array_merge(request()->all(), ['entity_type' => 'doctor'])) }}"
                        >طبيب</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a 
                            class="nav-link {{ request()->input('entity_type') == 'company' ? 'active' : '' }}" 
                            href="{{ route(get_area_name() . '.pricing-requests.index', array_merge(request()->all(), ['entity_type' => 'company'])) }}"
                        >شركة</a>
                    </li>
                </ul>

                <!-- Doctor Types Tabs (shown only if entity_type == doctor) -->
                @if($isDoctorEntity)
                    <ul class="nav nav-tabs mb-4" id="typeFilterTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a 
                                class="nav-link {{ request()->input('doctor_type') == '' ? 'active' : '' }}" 
                                href="{{ route(get_area_name() . '.pricing-requests.index', array_merge(request()->except('doctor_type'), ['entity_type' => 'doctor'])) }}"
                            ><i class="fas fa-globe"></i> جميع الأنواع</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a 
                                class="nav-link {{ request()->input('doctor_type') == 'libyan' ? 'active' : '' }}" 
                                href="{{ route(get_area_name() . '.pricing-requests.index', array_merge(request()->except('doctor_type'), ['entity_type' => 'doctor', 'doctor_type' => 'libyan'])) }}"
                            ><i class="fas fa-user-md"></i> ليبي</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a 
                                class="nav-link {{ request()->input('doctor_type') == 'foreign' ? 'active' : '' }}" 
                                href="{{ route(get_area_name() . '.pricing-requests.index', array_merge(request()->except('doctor_type'), ['entity_type' => 'doctor', 'doctor_type' => 'foreign'])) }}"
                            ><i class="fas fa-user-md"></i> أجنبي</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a 
                                class="nav-link {{ request()->input('doctor_type') == 'visitor' ? 'active' : '' }}" 
                                href="{{ route(get_area_name() . '.pricing-requests.index', array_merge(request()->except('doctor_type'), ['entity_type' => 'doctor', 'doctor_type' => 'visitor'])) }}"
                            ><i class="fas fa-user-md"></i> زائر</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a 
                                class="nav-link {{ request()->input('doctor_type') == 'palestinian' ? 'active' : '' }}" 
                                href="{{ route(get_area_name() . '.pricing-requests.index', array_merge(request()->except('doctor_type'), ['entity_type' => 'doctor', 'doctor_type' => 'palestinian'])) }}"
                            ><i class="fas fa-user-md"></i> فلسطيني</a>
                        </li>
                    </ul>
                @endif

                <!-- Filter Form -->
                <form action="{{ route(get_area_name() . '.pricing-requests.index') }}" method="GET" class="mb-4 p-3 bg-light rounded">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="name" class="form-label">اسم الطلب</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="name" 
                                name="name" 
                                value="{{ request()->input('name') }}"
                                placeholder="اسم الطلب"
                            >
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="active" class="form-label">الحالة</label>
                            <select class="form-control" id="active" name="active">
                                <option value="">الكل</option>
                                <option value="1" {{ request()->input('active') == '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ request()->input('active') == '0' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>اسم الطلب</th>
                                <th>نوع الكيان</th>
                                @if($showDoctorColumns)
                                    <th>نوع الطبيب</th>
                                    <th>الالصفة</th>
                                @endif
                                <th>الرسوم</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pricingRequests as $request)
                            @php
                                $entityType = $request->entity_type instanceof EntityType ? $request->entity_type : EntityType::from($request->entity_type);
                                $isActive = $request->active ? ActiveStatus::Active : ActiveStatus::Inactive;
                                $doctorType = ($request->doctor_type && DoctorType::tryFrom($request->doctor_type)) ? DoctorType::from($request->doctor_type) : null;
                            @endphp 
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $request->name }}</td>
                                    <td>{{ $request->entity_type == 'doctor' ? 'طبيب' : 'شركة' }}</td>
                                    @if($showDoctorColumns)
                                    <td>
                                            @if($doctorType)
                                                <span class="badge {{ $doctorType->badgeClass() }}">
                                                    {{ $doctorType->label() }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $request->doctorRank->name ?? '-' }}</td>
                                    @endif
                                    <td>{{ number_format($request->amount, 2) }}</td>
                                    <td>{{ $request->active ? 'نشط' : 'غير نشط' }}</td>
                                    <td>
                                        <a 
                                            href="{{ route(get_area_name() . '.pricing-requests.edit', $request) }}" 
                                            class="btn btn-info btn-sm text-light"
                                        ><i class="fas fa-edit"></i> تعديل</a>
                                        <form 
                                            action="{{ route(get_area_name() . '.pricing-requests.destroy', $request) }}" 
                                            method="POST" 
                                            class="d-inline"
                                            onsubmit="return confirm('هل أنت متأكد من الحذف؟');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm text-light">
                                                <i class="fas fa-trash-alt"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $showDoctorColumns ? '8' : '6' }}" class="text-center">لا توجد بيانات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $pricingRequests->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
