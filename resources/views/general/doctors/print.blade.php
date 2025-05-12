@extends('layouts.a4')

@section('title', 'تفاصيل الطبيب')

@section('content')
    <div class="row">
                
        <div class="col-md-12 text-center">
            @php($img=$doctor->files->first())
            @if($img)
                <img src="{{ Storage::url($img->file_path) }}" class="img-thumbnail" style="max-width:100%;max-height:300px;">
                <p class="mt-2 text-muted">{{ $img->file_name }}</p>
            @else
                <div class="text-muted">لا توجد صورة</div>
            @endif
        </div>
        <div class="col-md-12">
            <h3 class="text-primary">البيانات الشخصية</h3>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="bg-light text-primary">نوع الطبيب</th>
                        <td class="{{ $doctor->type->badgeClass() }}">{{ $doctor->type->label() }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-primary">كود الطبيب</th>
                        <td>{{ $doctor->code }}</td>
                    </tr>
                    @if (isset($doctor->doctor_number))
                    <tr>
                        <th class="bg-light text-primary">الرقم النقابي الأول</th>
                        <td>{{ $doctor->doctor_number }}</td>
                    </tr>
                    @endif
                   
                    @if (get_area_name() == "admin")
                        <tr>
                            <th class="bg-light text-primary"> الفرع   </th>
                            <td>{{ $doctor->branch ? $doctor->branch->name : "" }}</td>
                        </tr>
                    @endif

                    <tr>
                        <th class="bg-light text-primary">الاسم بالكامل</th>
                        <td>{{ $doctor->name }}</td>
                    </tr>
                    @if (!in_array($doctor->type->value, ['visitor', 'foreign']))
                    <tr>
                        <th class="bg-light text-primary">الاسم بالإنجليزية</th>
                        <td>{{ $doctor->name_en }}</td>
                    </tr>
                    @endif
                    @if ($doctor->type->value === 'libyan')
                    <tr>
                        <th class="bg-light text-primary">الرقم الوطني</th>
                        <td>{{ $doctor->national_number }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light text-primary">اسم الأم</th>
                        <td>{{ $doctor->mother_name }}</td>
                    </tr>
                    @endif
              
                </tbody>
            </table>


            <h3 class="text-primary"> بيانات الاتصال </h3>
            <table class="table table-bordered">
                <tbody>
                  

                    <tr>
                        <th class="bg-light text-primary">رقم الهاتف</th>
                        <td>{{ $doctor->phone }}</td>
                    </tr>

                    @if (isset($doctor->phone_2))
                    <tr>
                        <th class="bg-light text-primary">الواتساب </th>
                        <td>{{ $doctor->phone_2 }}</td>
                    @endif

                    <tr>
                        <th class="bg-light text-primary">البريد الإلكتروني</th>
                        <td>{{ $doctor->email }}</td>
                    </tr>
                    @if (isset($doctor->address))
                    <tr>
                        <th class="bg-light text-primary">العنوان</th>
                        <td>{{ $doctor->address }}</td>
                    </tr>
                    @endif
                  
              
                </tbody>
            </table>
        </div>







    </div>

    <div class="row">
        @if ($doctor->hand_graduation_id)
            <div class="col-md-6">
                <h3 class="text-primary">بيانات البكالوريس</h3>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="bg-light text-primary"> اسم الجامعة </th>
                            <td>{{ $doctor->handGraduation->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light text-primary">تاريخ الحصول عليها </th>
                            <td>{{ $doctor->graduation_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
        @if ($doctor->qualification_university_id || $doctor->internership_complete)
            <div class="col-md-6">
                <h3 class="text-primary">بيانات الامتياز</h3>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="bg-light text-primary"> اسم الجامعة </th>
                            <td>{{ $doctor->qualificationUniversity->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light text-primary">تاريخ الحصول عليها </th>
                            <td>{{ $doctor->internership_complete }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
