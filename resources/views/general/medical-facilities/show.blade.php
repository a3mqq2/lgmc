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
                            <th class="bg-light"> رقم السجل التجاري </th>
                            <td>{{ $medicalFacility->commerical_number }}</td>
                        </tr>


                    
                        <tr>
                            <th class="bg-light">نوع المنشأة الطبية</th>
                            <td>{{ $medicalFacility->medicalFacilityType->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">الموقع</th>
                            <td>{{ $medicalFacility->address }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">رقم الهاتف</th>
                            <td>{{ $medicalFacility->phone_number }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light"> تاريخ بدء النشاط </th>
                            <td>{{ $medicalFacility->activity_start_date }}</td>
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
                                            {{-- preview image --}}
                                            <a href="{{Storage::url($file->file_path)}}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>
                                            <a download href="{{Storage::url($file->file_path)}}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>
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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="main-content-label">  سجلات المنشأة  </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="bg-primary text-light">#</th>
                                    <th class="bg-primary text-light">المستخدم</th>
                                    <th class="bg-primary text-light">تفاصيل</th>
                                    <th class="bg-primary text-light">تاريخ الانشاء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medicalFacility->logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $log->user->name }}</td>
                                    <td>{{ $log->details}}</td>
                                    <td>{{ $log->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
