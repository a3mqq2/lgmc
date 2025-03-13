@extends('layouts.a4')

@section('title', 'تفاصيل الطبيب')

@section('content')
    <div class="row">
        <div class="col-12">
            <h4 class="bg-primary text-light p-2"> بيانات الطبيب </h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="bg-light">كود الطبيب</th>
                            <td>{{ $doctor->ecode }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الاسم</th>
                            <td>{{ $doctor->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الاسم بالإنجليزية</th>
                            <td>{{ $doctor->name_en }}</td>
                        </tr>
                        @if (request('type') == "libyan")
                        <tr>
                            <th class="bg-light">الرقم الوطني</th>
                            <td>{{ $doctor->national_number }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th class="bg-light">اسم الأم</th>
                            <td>{{ $doctor->mother_name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الدولة</th>
                            <td>{{ $doctor->country->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">تاريخ الميلاد</th>
                            <td>{{ $doctor->date_of_birth ? $doctor->date_of_birth->format('Y-m-d') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الحالة الاجتماعية</th>
                            <td>{{ $doctor->marital_status }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الجنس</th>
                            <td>{{ $doctor->gender->label() }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">رقم الجواز</th>
                            <td>{{ $doctor->passport_number }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">تاريخ انتهاء الجواز</th>
                            <td>{{ $doctor->passport_expiration ? $doctor->passport_expiration->format('Y-m-d') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الإقامة</th>
                            <td>{{ $doctor->address }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">رقم الهاتف</th>
                            <td>{{ $doctor->phone }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">رقم الواتساب</th>
                            <td>{{ $doctor->phone_2 }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">البريد الالكتروني</th>
                            <td>{{ $doctor->email }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h4 class="bg-primary text-light p-2">بكالوريس</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="bg-light">جامعة التخرج</th>
                            <td>{{ $doctor->handGraduation->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">تاريخ إنهاء التدريب</th>
                            <td>{{ $doctor->internership_complete ? $doctor->internership_complete->format('Y-m-d') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الدرجة العلمية</th>
                            <td>{{ $doctor->academicDegree->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">جامعة التأهيل</th>
                            <td>{{ $doctor->qualificationUniversity->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">تاريخ التأهيل</th>
                            <td>{{ $doctor->certificate_of_excellence_date ? $doctor->certificate_of_excellence_date: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">شهادة الإمتياز</th>
                            <td>{{ $doctor->certificate_of_excellence }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">تاريخ شهادة الإمتياز</th>
                            <td>{{ $doctor->certificate_of_excellence_date ? $doctor->certificate_of_excellence_date: 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">شهادة التخرج</th>
                            <td>{{ $doctor->graduationـcertificate }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-5" style="margin-top: 30px !important;">
        <div class="col-md-12">
            <h4 class="bg-primary text-light p-2">العمل الحالي</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="bg-light">الفرع</th>
                            <td>{{ $doctor->branch?->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">التخصص الأول</th>
                            <td>{{ $doctor->specialty1->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">التخصص الدقيق</th>
                            <td>{{ $doctor->specialty2->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">تاريخ الإضافة</th>
                            <td>{{ $doctor->created_at->format('Y-m-d') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h4 class="bg-primary text-light p-2">العمل السابق</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="bg-light">الجهات السابقة</th>
                            <td>{{ $doctor->ex_medical_facilities }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الخبرة</th>
                            <td>{{ $doctor->experience }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">ملاحظات</th>
                            <td>{{ $doctor->notes }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
