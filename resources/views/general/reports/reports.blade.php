<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
            direction: rtl;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            min-height: 100vh;
        }

        /* Header */
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #dee2e6;
        }

        .report-title {
            font-size: 28px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .report-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 15px;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #6b7280;
        }

        /* Filters Summary */
        .filters-summary {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-right: 4px solid #3b82f6;
        }

        .filters-title {
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .filter-item {
            display: inline-block;
            background: white;
            padding: 5px 12px;
            margin: 3px;
            border-radius: 15px;
            font-size: 12px;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* Table */
        .table-container {
            margin-top: 20px;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            font-size: 12px;
        }

        .data-table th,
        .data-table td {
            padding: 8px 12px;
            text-align: right;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .data-table th {
            background: #f1f5f9;
            font-weight: bold;
            color: #1e293b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .data-table tbody tr:hover {
            background: #e2e8f0;
        }

        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-expired {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-suspended {
            background: #fef3c7;
            color: #92400e;
        }

        .status-banned {
            background: #fecaca;
            color: #7f1d1d;
        }

        /* Action buttons */
        .action-buttons {
            position: fixed;
            top: 20px;
            left: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        /* Print styles */
        @media print {
            body {
                background: white;
            }

            .container {
                max-width: none;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }

            .action-buttons {
                display: none;
            }

            .data-table {
                font-size: 10px;
            }

            .data-table th,
            .data-table td {
                padding: 4px 6px;
            }

            .report-title {
                font-size: 24px;
            }

            .filters-summary {
                background: #f9f9f9;
                break-inside: avoid;
            }

            .table-container {
                break-inside: auto;
            }

            .data-table thead {
                display: table-header-group;
            }

            .data-table tbody tr {
                break-inside: avoid;
            }
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .report-info {
                flex-direction: column;
                gap: 10px;
            }

            .data-table {
                font-size: 10px;
            }

            .data-table th,
            .data-table td {
                padding: 6px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> طباعة
        </button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة
        </a>
    </div>

    <div class="container">
        <!-- Report Header -->
        <div class="report-header">
            <h1 class="report-title">{{ $title }}</h1>
            <p class="report-subtitle">تم إنشاؤه في {{ $generated_at->format('Y/m/d - H:i') }}</p>
            
            <div class="report-info">
                <div>إجمالي النتائج: <strong>{{ $total_count }}</strong></div>
                <div>{{ config('app.name', 'نظام إدارة التقارير الطبية') }}</div>
            </div>
        </div>

        <!-- Filters Summary -->
        @if(!empty($filter_summary))
        <div class="filters-summary">
            <div class="filters-title">الفلاتر المطبقة:</div>
            @foreach($filter_summary as $key => $value)
                <span class="filter-item">{{ $key }}: {{ $value }}</span>
            @endforeach
        </div>
        @endif

        <!-- Data Table -->
        <div class="table-container">
            @if(isset($doctors))
                {{-- تقرير الأطباء --}}
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الرمز</th>
                            <th>الاسم</th>
                            <th>الرقم الوطني</th>
                            <th>الصفة</th>
                            <th>التخصص</th>
                            <th>نوع الطبيب</th>
                            <th>مكان العمل</th>
                            <th> المنشات الطبية </th>
                            <th>حالة العضوية</th>
                            <th>تاريخ التسجيل</th>
                            <th>الفرع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctors as $index => $doctor)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $doctor->code }}</td>
                            <td>{{ $doctor->name }}</td>
                            <td>{{ $doctor->national_number }}</td>
                            <td>{{ $doctor->doctorRank->name ?? '-' }}</td>
                            <td>{{ $doctor->specialty1->name ?? '-' }}</td>
                            <td>
                                @php
                                    $types = [
                                        'libyan' => 'ليبي',
                                        'foreign' => 'أجنبي',
                                        'palestinian' => 'فلسطيني',
                                        'visitor' => 'زائر'
                                    ];
                                    $doctorType = is_string($doctor->type->value) ? $doctor->type->value : (string) $doctor->type->value;
                                @endphp
                                {{ $types[$doctorType] ?? $doctorType }}
                            </td>
                            <td>
                                @php
                                    // جلب التراخيص النشطة المحملة مسبقاً
                                    $workPlaces = [];
                                    
                                    foreach($doctor->licenses as $license) {
                                        // جهات العمل (المؤسسات)
                                        if($license->institution) {
                                            $workPlaces[] = $license->institution->name;
                                        }
                                        // المنشآت الطبية التي يعمل بها
                                        if($license->workIn) {
                                            $workPlaces[] = $license->workIn->name;
                                        }
                                    }
                                    
                                    $workPlacesList = array_unique($workPlaces);
                                @endphp
                                {{ !empty($workPlacesList) ? implode(', ', $workPlacesList) : '-' }}
                            </td>
                            <td>
                                @php
                                    $medicalFacilities = [];
                                    
                                    foreach($doctor->licenses as $license) {
                                        if($license->medicalFacility) {
                                            $medicalFacilities[] = $license->medicalFacility->name;
                                        }
                                    }
                                    
                                    $medicalFacilitiesList = array_unique($medicalFacilities);
                                @endphp
                                {{ !empty($medicalFacilitiesList) ? implode(', ', $medicalFacilitiesList) : '-' }}
                            </td>
                             

                            <td>
                                @php
                                    $statusClasses = [
                                        'active' => 'status-active',
                                        'expired' => 'status-expired',
                                        'suspended' => 'status-suspended',
                                        'banned' => 'status-banned'
                                    ];
                                    $statusLabels = [
                                        'active' => 'نشط',
                                        'expired' => 'منتهي',
                                        'suspended' => 'معلق',
                                        'banned' => 'محظور'
                                    ];
                                    $membershipStatus = is_string($doctor->membership_status->value) ? $doctor->membership_status->value : (string) $doctor->membership_status->value;
                                @endphp
                                <span class="status-badge {{ $statusClasses[$membershipStatus] ?? '' }}">
                                    {{ $statusLabels[$membershipStatus] ?? $membershipStatus }}
                                </span>
                            </td>

                            <td>{{ $doctor->registered_at ? $doctor->registered_at : '-' }}</td>
                            <td>{{ $doctor->branch->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


            @elseif(isset($licenses) && $licenses->first() && $licenses->first()->doctor)
                {{-- تقرير تراخيص الأطباء --}}
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>رمز الترخيص</th>
                            <th>اسم الطبيب</th>
                            <th>رمز الطبيب</th>
                            <th>الصفة</th>
                            <th>التخصص</th>
                            <th>نوع الطبيب</th>
                            <th>تاريخ الإصدار</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الحالة</th>
                            <th>الفرع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($licenses as $index => $license)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $license->code }}</td>
                            <td>{{ $license->doctor->name ?? '-' }}</td>
                            <td>{{ $license->doctor->code ?? '-' }}</td>
                            <td>{{ $license->doctor->doctorRank->name ?? '-' }}</td>
                            <td>{{ $license->doctor->specialty1->name ?? '-' }}</td>
                            <td>
                                @php
                                    $types = [
                                        'libyan' => 'ليبي',
                                        'foreign' => 'أجنبي',
                                        'palestinian' => 'فلسطيني',
                                        'visitor' => 'زائر'
                                    ];
                                    $licenseType = is_string($license->doctor_type) ? $license->doctor_type : (string) $license->doctor_type;
                                @endphp
                                {{ $types[$licenseType] ?? $licenseType }}
                            </td>
                            <td>{{ $license->issued_date ? $license->issued_date : '-' }}</td>
                            <td>{{ $license->expiry_date ? $license->expiry_date : '-' }}</td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'active' => 'status-active',
                                        'expired' => 'status-expired',
                                        'suspended' => 'status-suspended'
                                    ];
                                    $statusLabels = [
                                        'active' => 'نشط',
                                        'expired' => 'منتهي',
                                        'suspended' => 'معلق'
                                    ];
                                    $licenseStatus = is_string($license->status->label()) ? $license->status->label() : (string) $license->status->label();
                                @endphp
                                <span class="status-badge {{$license->status->badgeClass()}} ">
                                    {{$license->status->label()}}
                                </span>
                            </td>
                            <td>{{ $license->doctor->branch->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            @elseif(isset($facilities))
                {{-- تقرير المنشآت الطبية --}}
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الرمز</th>
                            <th>اسم المنشأة</th>
                            <th>نوع المنشأة</th>
                            <th>المدير</th>
                            <th>العنوان</th>
                            <th>الهاتف</th>
                            <th>حالة العضوية</th>
                            <th>تاريخ التسجيل</th>
                            <th>الفرع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facilities as $index => $facility)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $facility->code }}</td>
                            <td>{{ $facility->name }}</td>
                            <td>{{ $facility->type->name ?? '-' }}</td>
                            <td>{{ $facility->manager->name ?? '-' }}</td>
                            <td>{{ $facility->address ?? '-' }}</td>
                            <td>{{ $facility->phone ?? '-' }}</td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'active' => 'status-active',
                                        'expired' => 'status-expired',
                                        'suspended' => 'status-suspended',
                                        'under_approve' => 'status-suspended'
                                    ];
                                    $statusLabels = [
                                        'active' => 'نشطة',
                                        'expired' => 'منتهية',
                                        'suspended' => 'معلقة',
                                        'under_approve' => 'قيد الموافقة'
                                    ];
                                    $facilityStatus = is_string($facility->membership_status->value) ? $facility->membership_status->value : (string) $facility->membership_status->value;
                                @endphp
                                <span class="status-badge {{ $statusClasses[$facilityStatus] ?? '' }}">
                                    {{ $statusLabels[$facilityStatus] ?? $facilityStatus }}
                                </span>
                            </td>
                            <td>{{ $facility->created_at ? $facility->created_at : '-' }}</td>
                            <td>{{ $facility->branch->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            @elseif(isset($licenses))
                {{-- تقرير تراخيص المنشآت الطبية --}}
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>رمز الترخيص</th>
                            <th>اسم المنشأة</th>
                            <th>نوع المنشأة</th>
                            <th>تاريخ الإصدار</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الحالة</th>
                            <th>الفرع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($licenses as $index => $license)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $license->code }}</td>
                            <td>{{ $license->medicalFacility->name ?? '-' }}</td>
                            <td>{{ $license->medicalFacility->type->name ?? '-' }}</td>
                            <td>{{ $license->issued_date ? $license->issued_date : '-' }}</td>
                            <td>{{ $license->expiry_date ? $license->expiry_date : '-' }}</td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'active' => 'status-active',
                                        'expired' => 'status-expired',
                                        'suspended' => 'status-suspended'
                                    ];
                                    $statusLabels = [
                                        'active' => 'نشط',
                                        'expired' => 'منتهي',
                                        'suspended' => 'معلق'
                                    ];
                                @endphp
                                <span class="status-badge {{$license->status->badgeClass()}} ">
                                    {{$license->status->label()}}
                                </span>
                            </td>
                            <td>{{ $license->medicalFacility->branch->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
</body>
</html>