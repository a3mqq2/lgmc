@extends('layouts.' . get_area_name())
@section('title', 'تفاصيل الطبيب')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">المعلومات الشخصية</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-light">كود الطبيب</th>
                                    <td>{{ $doctor->code }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">الاسم</th>
                                    <td>{{ $doctor->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">الاسم بالإنجليزية</th>
                                    <td>{{ $doctor->name_en }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">الرقم الوطني</th>
                                    <td>{{ $doctor->national_number }}</td>
                                </tr>
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
                                    <td>{{ $doctor->gender }}</td>
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
                                    <th class="bg-light">العنوان</th>
                                    <td>{{ $doctor->address }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">رقم الهاتف</th>
                                    <td>{{ $doctor->phone }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">رقم الهاتف 2</th>
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
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">بكالوريس</h4>
                </div>
                <div class="card-body">
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
                                    <td>{{ $doctor->qualification_date ? $doctor->qualification_date->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">شهادة التميز</th>
                                    <td>{{ $doctor->certificate_of_excellence }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">تاريخ شهادة التميز</th>
                                    <td>{{ $doctor->certificate_of_excellence_date ? $doctor->certificate_of_excellence_date->format('Y-m-d') : 'N/A' }}</td>
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
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">العمل الحالي</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-light">الفرع</th>
                                    <td>{{ $doctor->branch->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">التخصص الأول</th>
                                    <td>{{ $doctor->specialty1->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">التخصص الثاني</th>
                                    <td>{{ $doctor->specialty2->name ?? 'N/A'}}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">التخصص الثالث</th>
                            <td>{{ $doctor->specialty3->name ?? 'N/A' }}</td>
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
</div>
</div>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h4 class="card-title">العمل السابق</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="bg-light">المنشأة السابقة</th>
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
    </div>
    <div class="col-md-12">
        <a href="{{route(get_area_name().".doctors.files.create", ['doctor' => $doctor->id])}}" class="btn btn-success mb-3"><i class="fa fa-plus"></i> اضافة ملف  </a>
        <div class="card">
            <div class="card-header bg-primary text-light">ملفات الطبيب</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <th>#</th>
                            <th>اسم الملف</th>
                            <th>نوع الملف</th>
                            <th>تحميل</th>
                        </thead>
                        <tbody>
                            @foreach ($doctor->files as $file)
                                <tr>
                                    <th>{{$file->id}}</th>
                                    <th>{{$file->file_name}}</th>
                                    <th>{{$file->fileType ? $file->fileType->name : ""}}</th>
                                    <td>
                                        <a download href="{{Storage::url($file->file_path)}}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>
                                        <form action="{{ route(get_area_name().'.files.destroy', ['doctor' => $doctor->id, 'file' => $file->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذه الدرجة العلمية؟')">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="card">
        <div class="card-header bg-primary text-light">اذونات مزاولة الطبيب</div>
        <div class="card-body">
            <table class="table table-bordered table-hover mb-0">
                <thead>
                    <tr>
                        <th class="bg-light">#</th>
                        <th class="bg-light">المرخص له</th>
                        <th class="bg-light">تاريخ الإصدار</th>
                        <th class="bg-light">تاريخ الانتهاء</th>
                        <th class="bg-light">الحالة</th>
                        <th class="bg-light">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($doctor->licenses as $licence)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $licence->licensable->name }}</td>
                        <td>{{ $licence->issued_date }}</td>
                        <td>{{ $licence->expiry_date }}</td>
                        <td>{!! $licence->status_badge !!}</td>
                        <td>
                            <a href="{{ route(get_area_name().'.licences.show', $licence) }}" class="btn btn-primary btn-sm text-light">عرض <i class="fa fa-eye"></i></a>
                            <a href="{{ route(get_area_name().'.licences.edit', $licence) }}" class="btn btn-info btn-sm text-light">تعديل <i class="fa fa-edit"></i></a>
                            <button type="button" class="btn btn-danger btn-sm text-light" data-bs-toggle="modal" data-bs-target="#deleteModal" data-licence-id="{{ $licence->id }}">حذف <i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Add more card sections as necessary -->
@endsection
