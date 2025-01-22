@extends('layouts.' . get_area_name())
@section('title', 'تفاصيل الطبيب')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="main-content-label">المعلومات الشخصية </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="{{$doctor->type->badgeClass()}}">نوع الطبيب</th>
                                    <th class="bg-primary text-light">كود الطبيب</th>
                                    <th class="bg-primary text-light"> الرقم النقابي الاول </th>
                                    <th class="bg-primary text-light">الاسم</th>
                                    <th class="bg-primary text-light">الاسم بالإنجليزية</th>
                                    @if ($doctor->type == "libyan")
                                    <th class="bg-primary text-light">الرقم الوطني</th>
                                    @endif
                                    <th class="bg-primary text-light">اسم الأم</th>
                                    <th class="bg-primary text-light">الدولة</th>
                                    <th class="bg-primary text-light">تاريخ الميلاد</th>
                                    <th class="bg-primary text-light">الحالة الاجتماعية</th>
                                </tr>
                                <tr>
                                    <td  class="{{$doctor->type->badgeClass()}}" >{{ $doctor->type->label() }}</td>
                                    <td>{{ $doctor->code }}</td>
                                    <td>{{ $doctor->doctor_number }}</td>
                                    <td>{{ $doctor->name }}</td>
                                    <td>{{ $doctor->name_en }}</td>
                                    <td>{{ $doctor->mother_name }}</td>
                                    @if ($doctor->type == "libyan")
                                    <td>{{ $doctor->national_number }}</td>
                                    @endif
                                    <td>{{ $doctor->country->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->date_of_birth ? $doctor->date_of_birth->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        {{ $doctor->marital_status->label() }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-primary text-light">الجنس</th>
                                    <th class="bg-primary text-light">رقم الجواز</th>
                                    <th class="bg-primary text-light">تاريخ انتهاء الجواز</th>
                                    <th class="bg-primary text-light">العنوان</th>
                                    <th class="bg-primary text-light">رقم الهاتف</th>
                                    <th class="bg-primary text-light">رقم الهاتف 2</th>
                                    <th class="bg-primary text-light">البريد الالكتروني</th>
                                    <th class="bg-primary text-light">حالة العضوية</th>
                                    <th class="bg-primary text-light">تاريخ انتهاء العضوية</th>
                                </tr>
                                <tr>
                                    <td>{{ $doctor->gender->label() }}</td>
                                    <td>{{ $doctor->passport_number }}</td>
                                    <td>{{ $doctor->passport_expiration ? $doctor->passport_expiration->format('Y-m-d') : 'N/A' }}</td>
                                    <td>{{ $doctor->address }}</td>
                                    <td>{{ $doctor->phone }}</td>
                                    <td>{{ $doctor->phone_2 }}</td>
                                    <td>{{ $doctor->email }}</td>
                                    <td>
                                        <span class="badge {{$doctor->membership_status->badgeClass()}} ">
                                            {{ $doctor->membership_status->label() }}
                                        </span>
                                    </td>
                                    <td>{{ $doctor->membership_expiration_date ? $doctor->membership_expiration_date->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4 class="main-content-label"> بكالوريس </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-primary text-light">جامعة التخرج</th>
                                    <th class="bg-primary text-light">تاريخ إنهاء الامتياز</th>
                                    <th class="bg-primary text-light">الدرجة العلمية</th>
                                    <th class="bg-primary text-light">جهة التخرج</th>
                                    <th class="bg-primary text-light">تاريخ التأهيل</th>
                                    <th class="bg-primary text-light">سنة  الإمتياز</th>
                                </tr>
                                <tr>
                                    <td>{{ $doctor->handGraduation->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->internership_complete ? $doctor->internership_complete->format('Y-m-d') : 'N/A' }}</td>
                                    <td>{{ $doctor->academicDegree->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->qualificationUniversity->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->certificate_of_excellence_date ? $doctor->certificate_of_excellence_date->format('Y-m-d') : 'N/A' }}</td>
                                    <td>{{$doctor->internership_complete ? $doctor->internership_complete->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4 class="main-content-label"> العمل الحالي  </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-primary text-light">الفرع</th>
                                    <th class="bg-primary text-light">التخصص الأول</th>
                                    <th class="bg-primary text-light">التخصص الثاني</th>
                                    <th class="bg-primary text-light">التخصص الثالث</th>
                                    <th class="bg-primary text-light">تاريخ الإضافة</th>
                                </tr>
                                <tr>
                                    <td>{{ $doctor->branch->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->specialty1->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->specialty2->name ?? 'N/A'}}
                                    <td>{{ $doctor->specialty3->name ?? 'N/A' }}</td>
                                    <td>{{ $doctor->created_at->format('Y-m-d') }}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4 class="main-content-label"> العمل السابق  </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-primary text-light">المنشأت السابقة</th>
                                    <th class="bg-primary text-light">الخبرة</th>
                                    <th class="bg-primary text-light">ملاحظات</th>

                                </tr>
                                <tr>
                                    <td>{{ $doctor->experience }}</td>
                                    <td>{{ $doctor->ex_medical_facilities }}</td>
                                    <td>{{ $doctor->notes }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4 class="main-content-label">  ملفات الطبيب  </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th class="bg-primary text-light">#</th>
                                <th class="bg-primary text-light">اسم الملف</th>
                                <th class="bg-primary text-light">نوع الملف</th>
                                <th class="bg-primary text-light">تحميل</th>
                            </thead>
                            <tbody>
                                @foreach ($doctor->files as $file)
                                    <tr>
                                        <th>{{$file->id}}</th>
                                        <th>{{$file->file_name}}</th>
                                        <th>{{$file->fileType ? $file->fileType->name : ""}}</th>
                                        <td>
                                            <a download href="{{Storage::url($file->file_path)}}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>
                                            {{-- <form action="{{ route(get_area_name().'.files.destroy', ['doctor' => $doctor->id, 'file' => $file->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذه الدرجة العلمية؟')">حذف</button>
                                            </form> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <h4 class="main-content-label">   اذونات مزاولة الطبيب   </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="bg-primary text-light">#</th>
                                    <th class="bg-primary text-light">المرخص له</th>
                                    <th class="bg-primary text-light">تاريخ الإصدار</th>
                                    <th class="bg-primary text-light">تاريخ الانتهاء</th>
                                    <th class="bg-primary text-light">الحالة</th>
                                    <th class="bg-primary text-light">الإجراءات</th>
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
        </div>
    </div>
</div>
</div>
@endsection
