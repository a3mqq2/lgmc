<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير إجمالي - الأطباء</title>
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
            line-height: 1.6;
        }
        
        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header Styles */
        .report-header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .logo {
            max-height: 80px;
            margin-bottom: 15px;
        }
        
        .report-title {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin: 10px 0;
        }
        
        .report-subtitle {
            font-size: 16px;
            color: #7f8c8d;
        }
        
        .report-date {
            font-size: 14px;
            color: #95a5a6;
            margin-top: 10px;
        }
        
        /* Filter Summary */
        .filter-summary {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .filter-summary h3 {
            font-size: 18px;
            color: #34495e;
            margin-bottom: 10px;
        }
        
        .filter-item {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            margin: 5px;
            font-size: 14px;
        }
        
        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            font-size: 40px;
            color: #3498db;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 16px;
            color: #7f8c8d;
        }
        
        /* Charts Section */
        .chart-section {
            margin-bottom: 40px;
        }
        
        .chart-container {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .chart-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .data-table th {
            background: #34495e;
            color: white;
            font-weight: 600;
        }
        
        .data-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .data-table tr:hover {
            background: #e3f2fd;
        }
        
        /* Progress Bars */
        .progress-item {
            margin-bottom: 15px;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .progress-bar-bg {
            background: #e0e0e0;
            height: 25px;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-bar-fill {
            background: linear-gradient(90deg, #3498db 0%, #2980b9 100%);
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 12px;
            transition: width 0.5s ease;
        }
        
        /* Footer */
        .report-footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        /* Print Styles */
        @media print {
            body {
                font-size: 12px;
            }
            
            .report-container {
                padding: 10px;
            }
            
            .stat-card {
                break-inside: avoid;
            }
            
            .chart-container {
                break-inside: avoid;
            }
            
            .no-print {
                display: none !important;
            }
        }
        
        /* Color Classes */
        .text-success { color: #27ae60; }
        .text-danger { color: #e74c3c; }
        .text-warning { color: #f39c12; }
        .text-info { color: #3498db; }
        
        .bg-success { background-color: #27ae60; }
        .bg-danger { background-color: #e74c3c; }
        .bg-warning { background-color: #f39c12; }
        .bg-info { background-color: #3498db; }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Report Header -->
        <div class="report-header">
            <img src="{{ asset('/assets/images/lgmc-dark.png') }}" alt="Logo" class="logo">
            <h1 class="report-title">التقرير الإجمالي للأطباء</h1>
            <p class="report-subtitle">نقابة الأطباء - ليبيا</p>
            <p class="report-date">تاريخ التقرير: {{ $report_date }}</p>
        </div>
        
        <!-- Applied Filters -->
        @if(!empty($filters))
        <div class="filter-summary">
            <h3>المعايير المطبقة:</h3>
            @if(isset($filters['doctor_ranks']))
                @foreach($filters['doctor_ranks'] as $rank)
                    <span class="filter-item">صفة: {{ $rank }}</span>
                @endforeach
            @endif
            @if(isset($filters['specialties']))
                @foreach($filters['specialties'] as $specialty)
                    <span class="filter-item">تخصص: {{ $specialty }}</span>
                @endforeach
            @endif
            @if(isset($filters['types']))
                @foreach($filters['types'] as $type)
                    <span class="filter-item">نوع: {{ $type }}</span>
                @endforeach
            @endif
            @if(isset($filters['registration_period']))
                <span class="filter-item">
                    فترة التسجيل: {{ $filters['registration_period']['from'] ?? 'البداية' }} - {{ $filters['registration_period']['to'] ?? 'النهاية' }}
                </span>
            @endif
        </div>
        @endif
        
        <!-- Summary Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-value">{{ number_format($total_doctors) }}</div>
                <div class="stat-label">إجمالي عدد الأطباء</div>
            </div>
            
            @if(isset($membership_expiry))
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-value">{{ number_format($membership_expiry['active']) }}</div>
                <div class="stat-label">عضوية نشطة</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">⚠️</div>
                <div class="stat-value">{{ number_format($membership_expiry['expiring_30_days']) }}</div>
                <div class="stat-label">تنتهي خلال 30 يوم</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">❌</div>
                <div class="stat-value">{{ number_format($membership_expiry['expired']) }}</div>
                <div class="stat-label">عضوية منتهية</div>
            </div>
            @endif
        </div>
        
        <!-- Distribution by Doctor Rank -->
        <div class="chart-container">
            <h2 class="chart-title">توزيع الأطباء حسب الصفة المهنية</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>الصفة المهنية</th>
                        <th>العدد</th>
                        <th>النسبة المئوية</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($by_rank as $rank)
                    <tr>
                        <td>{{ $rank->doctor_rank->name ?? 'غير محدد' }}</td>
                        <td>{{ number_format($rank->total) }}</td>
                        <td>
                            <div class="progress-item">
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width: {{ ($rank->total / $total_doctors) * 100 }}%">
                                        {{ number_format(($rank->total / $total_doctors) * 100, 1) }}%
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Distribution by Specialty -->
 
        
  
        @if (get_area_name() == "admin")
            <!-- Distribution by Type -->
            <div class="chart-container">
               <h2 class="chart-title">توزيع الأطباء حسب النوع</h2>
               <div class="stats-grid">
                  @foreach($by_type as $type)
                  <div class="stat-card">
                     <div class="stat-value">{{ number_format($type->total) }}</div>
                     <div class="stat-label">
                           @switch($type->type)
                              @case('libyan') ليبي @break
                              @case('palestinian') فلسطيني @break
                              @case('foreign') أجنبي @break
                              @case('visitor') زائر @break
                              @default {{ $type->type }}
                           @endswitch
                     </div>
                  </div>
                  @endforeach
               </div>
         </div>
        @endif
        <!-- Gender Distribution -->
        @if(isset($by_gender) && count($by_gender) > 0)
        <div class="chart-container">
            <h2 class="chart-title">توزيع الأطباء حسب الجنس</h2>
            <div class="stats-grid">
                @foreach($by_gender as $gender)
                <div class="stat-card">
                    <div class="stat-icon">{{ $gender->gender == 'male' ? '👨‍⚕️' : '👩‍⚕️' }}</div>
                    <div class="stat-value">{{ number_format($gender->total) }}</div>
                    <div class="stat-label">{{ $gender->gender == 'male' ? 'ذكر' : 'أنثى' }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Financial Summary (if available) -->
        @if(isset($financial_summary))
        <div class="chart-container">
            <h2 class="chart-title">الملخص المالي</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value text-danger">{{ number_format($financial_summary['total_due'], 2) }} د.ل</div>
                    <div class="stat-label">إجمالي المستحقات</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-success">{{ number_format($financial_summary['total_paid'], 2) }} د.ل</div>
                    <div class="stat-label">إجمالي المدفوعات</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-warning">{{ number_format($financial_summary['total_relief'], 2) }} د.ل</div>
                    <div class="stat-label">إجمالي الإعفاءات</div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Registration Trends -->
        @if(isset($registration_trends) && count($registration_trends) > 0)
        <div class="chart-container">
            <h2 class="chart-title">اتجاهات التسجيل (آخر 12 شهر)</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>الشهر</th>
                        <th>عدد التسجيلات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registration_trends as $trend)
                    <tr>
                        <td>{{ $trend->year }}/{{ str_pad($trend->month, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ number_format($trend->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- Report Footer -->
        <div class="report-footer">
            <p>تم إنشاء هذا التقرير بواسطة نظام إدارة نقابة الأطباء</p>
            <p>جميع الحقوق محفوظة © {{ date('Y') }}</p>
        </div>
    </div>
    <script>
      setTimeout(() => {
          window.print();
      }, 1000);
  </script>
</body>
</html>