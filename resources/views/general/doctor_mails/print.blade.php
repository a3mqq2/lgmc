{{-- resources/views/general/doctor_mails/print.blade.php --}}
@extends('layouts.a4')

@section('title', 'طباعة طلب أوراق خارجية #'.$doctorMail->id)

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="bg-primary text-white p-2 mb-4">تفاصيل طلب أوراق خارجية #{{ $doctorMail->id }}</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="bg-light">الطبيب</th>
                            <td>{{ $doctorMail->doctor->name }} ({{ $doctorMail->doctor->code }})</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الإيميلات</th>
                            <td>
                                <ul class="mb-0 ps-3">
                                    @foreach($doctorMail->emails as $email)
                                        <li>{{ $email }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">الدول المستهدفة</th>
                            <td>{{ $doctorMail->country_names }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">ملاحظات</th>
                            <td>{{ $doctorMail->notes ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">استخراج سابقًا؟</th>
                            <td>{{ $doctorMail->contacted_before ? 'نعم' : 'لا' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الإجمالي</th>
                            <td>{{ number_format($doctorMail->grand_total, 2) }} د.ل</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الحالة</th>
                            <td>@lang("status.{$doctorMail->status}")</td>
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

    {{-- Services section --}}
    <div class="row mt-4">
        <div class="col-12">
            <h4 class="bg-primary text-white p-2 mb-3">الخدمات المطلوبة</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>الخدمة</th>
                            <th style="width: 120px;">المبلغ (د.ل)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctorMail->doctorMailItems as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->pricing->name }}</td>
                                <td class="text-end">{{ number_format($item->pricing->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end">مجموع الخدمات</th>
                            <th class="text-end">{{ number_format($doctorMail->doctorMailItems->sum(fn($i)=>$i->pricing->amount), 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Print / Back buttons (hidden on print) --}}
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
