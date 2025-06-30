{{-- ملف resources/views/admin/invoices/print-multiple.blade.php --}}
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة فواتير {{ $doctor->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Cairo', sans-serif; 
            margin: 0; 
            padding: 0; 
            background: #fff; 
            color: #333; 
            direction: rtl; 
            text-align: right 
        }
        
        .invoice-container { 
            max-width: 800px; 
            margin: 20px auto; 
            border: 1px solid #ddd; 
            padding: 30px; 
            background: #fff; 
            box-shadow: 0 4px 8px rgba(0,0,0,.1) 
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #cc0100; 
            padding-bottom: 10px 
        }
        
        .header img { 
            display: block; 
            margin: 0 auto 10px 
        }
        
        .header h2 { 
            margin: 0; 
            color: #cc0100; 
            font-weight: 600 
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px 
        }
        
        table th, table td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            font-size: 14px 
        }
        
        table th { 
            background: #fff0f0; 
            font-weight: 600; 
            text-align: center 
        }
        
        .bg-light { 
            background: #f9fafc; 
            font-weight: 600 
        }
        
        .text-end { 
            text-align: right 
        }
        
        .text-center { 
            text-align: center 
        }
        
        .items h4 { 
            margin-top: 30px; 
            color: #cc0100 
        }
        
        .print-button { 
            margin-top: 20px; 
            text-align: center 
        }
        
        .print-button button { 
            padding: 10px 20px; 
            font-size: 16px; 
            cursor: pointer; 
            background: #cc0100; 
            color: #fff; 
            border: none; 
            border-radius: 4px;
            margin: 0 10px;
        }
        
        .print-button button:hover { 
            background: #a00100 
        }
        
        .status-paid { 
            color: #388E3C; 
            font-weight: 600 
        }
        
        .status-unpaid { 
            color: #cc0100; 
            font-weight: 600 
        }
        
        .status-pending { 
            color: #FF9800; 
            font-weight: 600 
        }
        
        .summary-row {
            background: #f0f0f0;
            font-weight: 600;
        }
        
        @media print {
            .no-print { 
                display: none 
            }
            .invoice-container { 
                margin: 0; 
                border: none; 
                box-shadow: none 
            }
        }
    </style>
</head>
<body>

    <div class="invoice-container">
        {{-- رأس الفاتورة --}}
        <div class="header">
            <img src="{{ asset('assets/images/lgmc-dark.png') }}" style="width:200px" alt="Logo">
            <h2>فاتورة</h2>
        </div>

        {{-- بيانات الدكتور --}}
        <div class="details">
            <table>
                <tr>
                    <td class="bg-light">اسم الطبيب</td>
                    <td>{{ $doctor->name }}</td>
                    <td class="bg-light">كود الطبيب</td>
                    <td>{{ $doctor->code }}</td>
                </tr>
                <tr>
                    <td class="bg-light"> المعد </td>
                    <td>{{ auth()->user()->name }}</td>
                    <td class="bg-light">تاريخ الطباعة</td>
                    <td>
                        {{ now()->format('Y-m-d') }}
                        الساعة {{ now()->format('h:i A') }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- تفاصيل الفواتير --}}
        <div class="items">
            <h4>تفاصيل الفواتير</h4>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الوصف</th>
                        <th>المبلغ (د.ل.)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        @foreach ($invoice->items as $invoice_item)
                        <tr>
                           <td>{{$loop->iteration}}</td>
                           <td>{{ $invoice_item->description }}</td>
                           <td class="text-end">{{ number_format($invoice_item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="summary-row">
                        <th colspan="2" class="text-end">الإجمالي الكلي</th>
                        <th class="text-end">{{ number_format($totalAmount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- ملاحظة مهمة --}}
        @if (auth()->user()->branch_id == null)
        <p style="margin-top:30px">
            يتم السداد كل يوم احد و ثلاثاء والاستلام يكون بعد تاريخ الدفع بإسبوع 
        </p>
        @endif

        {{-- أزرار الطباعة والعودة --}}
        <div class="print-button no-print">
            <button onclick="window.print()">طباعة القائمة</button>
            <button onclick="window.close()">إغلاق</button>
        </div>
    </div>

</body>
</html>