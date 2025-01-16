<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة طلب الطبيب</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
            direction: rtl;
        }

        .a4-container {
            margin: auto;
            padding: 20mm;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0;
        }

        .content-section {
            margin-bottom: 20px;
        }

        .content-section h2 {
            font-size: 18px;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .content-section table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .content-section table th,
        .content-section table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: right;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            position: absolute;
            bottom: 20mm;
            width: calc(100% - 40mm);
        }
    </style>
</head>
<body onload="window.print()">
    <div class="a4-container">
        <div class="header">
            <img src="{{asset('/assets/images/lgmc-dark.png')}}" style="width: 330px!important;margin: 20px auto;" alt="">
            <h1>طلب خدمة طبيب</h1>
            <p>رقم الطلب: {{ $doctorRequest->id }}</p>
            <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
        </div>

        <div class="content-section">
            <h2>تفاصيل الطلب</h2>
            <table>
                <tr>
                    <th>اسم الطبيب</th>
                    <td>{{ $doctorRequest->doctor->name }}</td>
                </tr>
                <tr>
                    <th>نوع الطلب</th>
                    <td>{{ $doctorRequest->pricing->name }}</td>
                </tr>
                <tr>
                    <th>السعر</th>
                    <td>{{ number_format($doctorRequest->pricing->amount, 2) }} د.ل</td>
                </tr>
                <tr>
                    <th>التاريخ</th>
                    <td>{{ $doctorRequest->date->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <th>الحالة</th>
                    <td>{{ $doctorRequest->status->label() }}</td>
                </tr>
                <tr>
                    <th>الملاحظات</th>
                    <td>{{ $doctorRequest->notes ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="content-section">
            <h2>معلومات إضافية</h2>
            <table>
                <tr>
                    <th>الموافقة بواسطة</th>
                    <td>{{ $doctorRequest->approvedBy->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>تاريخ الموافقة</th>
                    <td>{{ $doctorRequest->approved_at ? $doctorRequest->approved_at->format('Y-m-d H:i') : '-' }}</td>
                </tr>
                <tr>
                    <th>الرفض بواسطة</th>
                    <td>{{ $doctorRequest->rejectedBy->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>تاريخ الرفض</th>
                    <td>{{ $doctorRequest->rejected_at ? $doctorRequest->rejected_at->format('Y-m-d H:i') : '-' }}</td>
                </tr>
                <tr>
                    <th>الإكمال بواسطة</th>
                    <td>{{ $doctorRequest->doneBy->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>تاريخ الإكمال</th>
                    <td>{{ $doctorRequest->done_at ? $doctorRequest->done_at->format('Y-m-d H:i') : '-' }}</td>
                </tr>
                <tr>
                    <th>الفاتورة</th>
                    <td>{{ $doctorRequest->invoice ? $doctorRequest->invoice->status->label() : 'لا توجد فاتورة' }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
