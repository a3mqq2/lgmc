<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة رقم {{ $invoice->invoice_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <style>

        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #333;
            direction: rtl;
            text-align: right;
        }

        .invoice-container {  
            margin: auto;
            border: 1px solid #ddd;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #cc0100;
            padding-bottom: 10px;
        }

        .header img {
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            color: #cc0100;
            font-weight: 600;
        }

        /* Invoice Details */
        .details {
            margin-top: 20px;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .details table td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
        }

        .bg-light{
            font-weight: bold;
            background-color: #f9fafc;
        }

        /* Items Section */
        .items {
            margin-top: 30px;
        }

        .items h4 {
            margin-bottom: 10px;
            color: #cc0100;
        }

        .items table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items table th,
        .items table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .items table th {
            background-color: #fff0f0;
            font-weight: bold;
        }

        /* Footer Section */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }

        .footer p {
            margin: 5px 0;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
                box-shadow: none;
            }

            .invoice-container {
                margin: 0;
                border: none;
                box-shadow: none;
            }
        }

        .print-button {
            margin-top: 20px;
            text-align: center;
        }

        .print-button button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #cc0100;
            color: #fff;
            border: none;
            border-radius: 4px;
        }

        .print-button button:hover {
            background-color: #388E3C;
        }

        .print-button a button {
            background-color: #757575;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header Section -->
        <div class="header">
            <img src="{{asset('/assets/images/lgmc-dark.png')}}" style="width: 330px!important;margin: 20px auto;" alt="">
            <h2>فاتورة</h2>
        </div>

        <!-- Invoice Details -->
        <div class="details">
         <table>
             <tr>
                 <td class="bg-light"><strong>الاسم:</strong></td>
                 <td>{{ $invoice->invoiceable->name }}</td>
                 <td><strong>المستخدم:</strong></td>
                 <td>{{ $invoice->user?->name ?? '-' }}</td>
             </tr>
             <tr>
               
             </tr>
             <tr>
                 <td class="bg-light"><strong>الحالة:</strong></td>
                 <td>
                     @if($invoice->status->value == 'paid')
                         مدفوعة
                     @elseif($invoice->status->value == 'relief')
                         معفى عنها
                     @else
                         غير مدفوعة
                     @endif
               </td>
                 <td class="bg-light"><strong>تاريخ الإنشاء:</strong></td>
                 <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
             </tr>
         </table>
     </div>
     

        <!-- Items Section -->
        <div class="items">
            <h4>تفاصيل إضافية</h4>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الوصف</th>
                        <th>المبلغ</th>
                    </tr>
                </thead> 
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{ $invoice->description }}</td>
                        <td>{{ number_format($invoice->amount, 2) }} د.ل</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p><strong>التوقيع:</strong> ______________________</p>
            <p><strong>تاريخ الطباعة:</strong> {{ now()->format('Y-m-d') }}</p>
        </div>

        <!-- Print Button -->
        <div class="print-button no-print">
            <button onclick="window.print()">طباعة الفاتورة</button>
            <a href="{{ route(get_area_name().'.invoices.index') }}">
                <button>العودة إلى القائمة</button>
            </a>
        </div>
    </div>
</body>
</html>
