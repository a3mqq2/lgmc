{{-- resources/views/user/doctor_transfers/print.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تقرير تحويلات الأطباء</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap');
        *{box-sizing:border-box;margin:0;padding:0}body{font-family:'Cairo',sans-serif;color:#333;background:#fff}
        .container{max-width:1000px;margin:auto;padding:20px}
        h1{text-align:center;margin-bottom:20px}
        .meta{margin-bottom:15px;font-size:14px}
        table{width:100%;border-collapse:collapse;font-size:12px}
        th,td{border:1px solid #ddd;padding:8px;text-align:right}
        th{background:#2c3e50;color:#fff}
        tr:nth-child(even){background:#f8f9fa}
        .badge{padding:3px 6px;border-radius:6px;font-size:11px;color:#fff}
        .pending{background:#ffc107}.approved{background:#28a745}.rejected{background:#dc3545}
        @media print{@page{size:A4 landscape;margin:10mm}body{font-size:10px}}
    </style>
</head>
<body>
    <div class="container">
        <h1>تقرير تحويلات الأطباء - {{ auth()->user()->branch->name ?? '' }}</h1>

        <div class="meta">
            <strong>تاريخ الطباعة:</strong> {{ $printed_at }}<br>
        </div>

        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>الطبيب</th>
                <th>من الفرع</th>
                <th>إلى الفرع</th>
                <th>المستخدم</th>
                <th>الحالة</th>
                <th>التاريخ</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transfers as $i=>$t)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $t->doctor->name }}</td>
                    <td>{{ $t->fromBranch->name }}</td>
                    <td>{{ $t->toBranch->name }}</td>
                    <td>{{ $t->createdBy->name }}</td>
                    <td>
                        <span class="badge {{ $t->status }}">
                            @switch($t->status)
                                @case('approved') موافق عليه @break
                                @case('rejected') مرفوض @break
                                @default قيد الانتظار
                            @endswitch
                        </span>
                    </td>
                    <td>{{ $t->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <script>
        setTimeout(()=>window.print(),800);
    </script>
</body>
</html>
