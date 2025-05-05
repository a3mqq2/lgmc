@extends('layouts.a4')

@section('title', 'طباعة طلب أوراق خارجية #' . $doctorMail->id)

@section('content')
<div class="row">
    <div class="col-12">
        <h4 class="bg-primary text-white p-2 mb-4">تفاصيل طلب أوراق خارجية #{{ $doctorMail->id }}</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="bg-light">الطبيب</th>
                        <td>{{ $doctorMail->doctor->name }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">الرقم النقابي</th>
                        <td>({{ $doctorMail->doctor->code }})</td>
                    </tr>
                    <tr>
                        <th class="bg-light">تاريخ الطلب</th>
                        <td>{{ $doctorMail->created_at->format('Y-m-d') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- عناصر الطلب: إيميلات + خدمات --}}
<div class="row mt-4">
    <div class="col-12">
        <h4 class="bg-primary text-white p-2 mb-3">عناصر الطلب</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>الوصف</th>
                        <th style="width: 180px;">جهة العمل</th>
                        <th style="width: 120px;">المبلغ (د.ل)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $index = 1;
                        $unit = match($doctorMail->doctor->type) {
                            'libyan' => 50,
                            'foreign' => 100,
                            default => 0,
                        };
                    @endphp

                    {{-- الإيميلات --}}
                    @foreach($doctorMail->emails as $email)
                        <tr>
                            <td>{{ $index++ }}</td>
                            <td>بريد إلكتروني: {{ $email }}</td>
                            <td>—</td>
                            <td class="text-end">{{ number_format($unit, 2) }}</td>
                        </tr>
                    @endforeach

                    {{-- الخدمات --}}
                    @foreach($doctorMail->doctorMailItems as $item)
                        <tr>
                            <td>{{ $index++ }}</td>
                            <td>{{ $item->pricing->name }}</td>
                            <td>
                                @if ($item->work_mention === 'with')
                                    مع ذكر جهة العمل
                                @elseif ($item->work_mention === 'without')
                                    دون ذكر جهة العمل
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-end">{{ number_format($item->pricing->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">الإجمالي الكلي</th>
                        <th class="text-end">{{ number_format($doctorMail->grand_total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- Print / Back buttons --}}
<div class="row no-print mt-4">
    <div class="col-12 text-center">
        <button class="btn btn-primary me-2" onclick="window.print()">
            <i class="fa fa-print me-1"></i> طباعة
        </button>
        <a href="{{ route(get_area_name().'.doctor-mails.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> رجوع للقائمة
        </a>
    </div>
</div>
@endsection
