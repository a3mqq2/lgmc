@extends('layouts.' . get_area_name())
@section('title', 'عرض تفاصيل المنشأة الطبية')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">عرض تفاصيل المنشأة الطبية</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th class="bg-light">اسم المنشأة</th>
                            <td>{{ $medicalFacility->name }}</td>
                        </tr>

                        <tr>
                            <th class="bg-light"> السجل التجاري </th>
                            <td>{{ $medicalFacility->commerical_number }}</td>
                        </tr>


                        <tr>
                            <th class="bg-light">نوع الملكية</th>
                            <td>{{ $medicalFacility->ownership == 'private' ? 'خاص' : 'عام' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الفرع</th>
                            <td>{{ $medicalFacility->branch->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">نوع المنشأة الطبية</th>
                            <td>{{ $medicalFacility->medicalFacilityType->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">العنوان</th>
                            <td>{{ $medicalFacility->address }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">رقم الهاتف</th>
                            <td>{{ $medicalFacility->phone_number }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">تاريخ الإنشاء</th>
                            <td>{{ $medicalFacility->created_at }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">آخر تحديث</th>
                            <td>{{ $medicalFacility->updated_at }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <a href="{{route(get_area_name().".medical-facilities.medical-facility-files.create", $medicalFacility->id)}}" class="btn btn-success mb-3"><i class="fa fa-plus"></i> اضف مستند جديد </a>
            <div class="card">
                <div class="card-header bg-primary text-light">المستندات</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th class="bg-light">#</th>
                                <th class="bg-light">اسم الملف</th>
                                <th class="bg-light">نوع الملف</th>
                                <th class="bg-light">الاعدادات</th>
                            </thead>
                            <tbody>
                                @foreach ($medicalFacility->files as $file)
                                    <tr>
                                        <td>{{$file->id}}</td> 
                                        <td>{{$file->file_name}}</td>
                                        <td>{{$file->fileType ? $file->fileType->name : "-"}}</td>
                                        <td>
                                            <a download href="{{Storage::url($file->file_path)}}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>
                                            <form action="{{ route(get_area_name().'.medical-facilities.medical-facility-files.destroy', ['medical_facility' => $medicalFacility->id, 'medical_facility_file' => $file]) }}" method="POST" class="d-inline">
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
        <div class="col-md-12">
            <a href="{{ route(get_area_name().'.medical-facilities.index') }}" class="btn btn-secondary mt-3">عودة إلى القائمة</a>
        </div>
    </div>
@endsection
