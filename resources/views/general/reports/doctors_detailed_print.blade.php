<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير تفصيلي - الأطباء</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            background: white;
            color: #333;
            font-size: 14px;
        }
        
        .report-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header */
        .report-header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }
        
        .report-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 10px 0;
        }
        
        .report-date {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        /* Summary Box */
        .summary-box {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }
        
        .summary-item {
            flex: 1;
        }
        
        .summary-value {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .summary-label {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        /* Doctors Table */
        .doctors-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        
        .doctors-table th {
            background: #34495e;
            color: white;
            padding: 10px 8px;
            text-align: right;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .doctors-table td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        
        .doctors-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .doctors-table tbody tr:hover {
            background: #e3f2fd;
        }
        
        /* Doctor Photo */
        .doctor-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Status Badges */
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-active {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-expired {
            background: #f8d7da;
            color: #721c24;
        }
        
        .badge-suspended {
            background: #fff3cd;
            color: #856404;
        }
        
        /* Financial Info */
        .financial-info {
            font-size: 11px;
            white-space: nowrap;
        }
        
        .text-danger { color: #e74c3c; }
        .text-success { color: #27ae60; }
        
        /* Pagination Info */
        .pagination-info {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        /* Footer */
        .report-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #7f8c8d;
            font-size: 12px;
        }
        
        /* Print Optimization */
        @media print {
            body {
                font-size: 10px;
            }
            
            .report-container {
                padding: 10px;
                max-width: 100%;
            }
            
            .doctors-table {
                font-size: 9px;
            }
            
            .doctors-table th,
            .doctors-table td {
                padding: 4px;
            }
            
            .no-print {
                display: none !important;
            }
            
            .doctor-photo {
                width: 30px;
                height: 30px;
            }
            
            @page {
                size: A4 landscape;
                margin: 10mm;
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .doctors-table {
                font-size: 10px;
            }
            
            .doctors-table th,
            .doctors-table td {
                padding: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Header -->
        <div class="report-header">
            <img src="{{ asset('/assets/images/lgmc-dark.png') }}" alt="Logo" class="logo">
            <h1 class="report-title">التقرير التفصيلي للأطباء</h1>
            <p class="report-date">تاريخ التقرير: {{ $report_date }}</p>
        </div>
        
        <!-- Summary -->
        <div class="summary-box">
            <div class="summary-item">
                <div class="summary-value">{{ number_format($summary['total']) }}</div>
                <div class="summary-label">إجمالي النتائج</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ $summary['current_page'] }} / {{ $summary['last_page'] }}</div>
                <div class="summary-label">الصفحة الحالية</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ number_format($summary['per_page']) }}</div>
                <div class="summary-label">نتائج بالصفحة</div>
            </div>
        </div>
        
        <!-- Doctors Table -->
        <table class="doctors-table">
            <thead>
                <tr>
                    <th width="40">#</th>
                    @if($include_photo)
                    <th width="50">الصورة</th>
                    @endif
                    <th width="80">الكود</th>
                    <th width="80">الرقم النقابي</th>
                    <th>الاسم</th>
                    <th width="100">الصفة</th>
                    <th width="120">التخصص</th>
                    @if($include_contact)
                    <th width="100">الهاتف</th>
                    <th width="150">البريد الإلكتروني</th>
                    @endif
                    <th width="80">النوع</th>
                    <th width="100">حالة العضوية</th>
                    <th width="90">تاريخ الانتساب</th>
                    <th width="90">انتهاء العضوية</th>
                    @if($include_financial)
                    <th width="100">المستحقات</th>
                    <th width="100">المدفوعات</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($doctors as $index => $doctor)
                <tr>
                    <td>{{ ($doctors->currentPage() - 1) * $doctors->perPage() + $index + 1 }}</td>
                    
                    @if($include_photo)
                    <td>
                        @if($doctor->files->first())
                        <img src="{{ Storage::url($doctor->files->first()->file_path) }}" 
                             alt="صورة" 
                             class="doctor-photo">
                        @else
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #ddd;"></div>
                        @endif
                    </td>
                    @endif
                    
                    <td><strong>{{ $doctor->code }}</strong></td>
                    <td>{{ $doctor->doctor_number }}</td>
                    <td><strong>{{ $doctor->name }}</strong></td>
                    <td>{{ $doctor->doctor_rank->name ?? '-' }}</td>
                    <td>
                     
                    </td>
                    
                    @if($include_contact)
                    <td>{{ $doctor->phone }}</td>
                    <td>{{ $doctor->email }}</td>
                    @endif
                    
                    <td>
                        @switch($doctor->type)
                            @case('libyan') ليبي @break
                            @case('palestinian') فلسطيني @break
                            @case('foreign') أجنبي @break
                            @case('visitor') زائر @break
                            @default {{ $doctor->type }}
                        @endswitch
                    </td>
                    
                    <td>
                        @php
                            $statusClass = match($doctor->membership_status) {
                                'active' => 'badge-active',
                                'expired' => 'badge-expired',
                                'suspended' => 'badge-suspended',
                                default => ''
                            };
                            $statusLabel = match($doctor->membership_status) {
                                'active' => 'نشط',
                                'expired' => 'منتهي',
                                'suspended' => 'معلق',
                                'banned' => 'محظور',
                                default => $doctor->membership_status
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    
                    <td>{{ $doctor->registered_at ? date('Y-m-d', strtotime($doctor->registered_at)) : '-' }}</td>
                    <td>{{ $doctor->membership_expiration_date ? date('Y-m-d', strtotime($doctor->membership_expiration_date)) : '-' }}</td>
                    
                    @if($include_financial && $doctor->invoices)
                    <td class="financial-info">
                        @php
                            $unpaid = $doctor->invoices->where('status', 'unpaid')->sum('total');
                            $paid = $doctor->invoices->where('status', 'paid')->sum('total');
                        @endphp
                        <span class="text-danger">{{ number_format($unpaid, 2) }} د.ل</span>
                    </td>
                    <td class="financial-info">
                        <span class="text-success">{{ number_format($paid, 2) }} د.ل</span>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pagination Note -->
        <div class="pagination-info">
            <p>هذه الصفحة {{ $summary['current_page'] }} من {{ $summary['last_page'] }} صفحة</p>
            <p>عرض {{ count($doctors) }} من إجمالي {{ $summary['total'] }} نتيجة</p>
        </div>
        
        <!-- Footer -->
        <div class="report-footer">
            <p>تم إنشاء هذا التقرير بواسطة نظام إدارة نقابة الأطباء</p>
            <p>جميع الحقوق محفوظة © {{ date('Y') }}</p>
        </div>
    </div>

    {{-- timeout before print --}}
    <script>
        setTimeout(() => {
            window.print();
        }, 1000);
    </script>
</body>
</html>