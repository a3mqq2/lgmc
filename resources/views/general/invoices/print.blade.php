<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة رقم {{ $invoice->id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; margin:0; padding:0; background:#fff; color:#333; direction:rtl; text-align:right }
        .invoice-container { max-width:800px; margin:20px auto; border:1px solid #ddd; padding:30px; background:#fff; box-shadow:0 4px 8px rgba(0,0,0,.1) }
        .header { text-align:center; margin-bottom:30px; border-bottom:2px solid #cc0100; padding-bottom:10px }
        .header img { display:block; margin:0 auto 10px }
        .header h2 { margin:0; color:#cc0100; font-weight:600 }
        table { width:100%; border-collapse:collapse; margin-top:10px }
        table th, table td { border:1px solid #ddd; padding:10px; font-size:14px }
        table th { background:#fff0f0; font-weight:600; text-align:center }
        .bg-light { background:#f9fafc; font-weight:600 }
        .text-end { text-align:right }
        .items h4 { margin-top:30px; color:#cc0100 }
        .print-button { margin-top:20px; text-align:center }
        .print-button button { padding:10px 20px; font-size:16px; cursor:pointer; background:#cc0100; color:#fff; border:none; border-radius:4px }
        .print-button button:hover { background:#388E3C }
        .print-button a button { background:#757575 }
        @media print {
            .no-print { display:none }
            .invoice-container { margin:0; border:none; box-shadow:none }
        }
    </style>
</head>
<body>

    <div class="invoice-container">
        {{-- رأس الفاتورة --}}
        <div class="header">
            <img src="{{ asset('assets/images/lgmc-dark.png') }}" style="width:200px" alt="Logo">
            <h2>فاتورة رقم {{ $invoice->id }}</h2>
        </div>

        {{-- بيانات الدكتور والفاتورة --}}
        <div class="details">
            <table>
                <tr>
                    <td class="bg-light">الاسم</td>
                    <td>{{ $invoice->doctor->name }}</td>
                    <td class="bg-light">كود الطبيب</td>
                    <td>{{ $invoice->doctor->code }}</td>
                </tr>
              
                <tr>
                    <td  class="bg-light">المستخدم</td>
                    <td>{{ $invoice->user?->name ?? '-' }}</td>
                    <td class="bg-light">تاريخ الإنشاء</td>
                    <td>
                        {{ $invoice->created_at->format('Y-m-d') }}
                        الساعة {{ $invoice->created_at->format('h:i A') }}
                    </td>
                </tr>
                <tr>
                    <td class="bg-light">الوصف</td>
                    <td colspan="3">{{ $invoice->description }}</td>
                </tr>
            </table>
        </div>

        {{-- تفاصيل البنود --}}
        <div class="items">
            <h4>تفاصيل الفاتورة</h4>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الوصف</th>
                        <th>المبلغ (د.ل.)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $idx = 1; @endphp
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $idx++ }}</td>
                            <td>{{ $item->description }}</td>
                            <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end">الإجمالي</th>
                        <th class="text-end">{{ number_format($invoice->amount, 2) }}</th>
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
            <button onclick="window.print()">طباعة الفاتورة</button>
          
        </div>
    </div>

</body>
</html>
