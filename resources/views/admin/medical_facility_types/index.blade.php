@extends('layouts.'.get_area_name())
@section('title', 'عرض أنواع المنشآت طبية')

@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-light">
                    <h5 class="card-title text-light">قائمة أنواع المنشآت طبية</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-light" scope="col">#</th>
                                    <th class="bg-light" scope="col">اسم النوع</th>
                                    <th class="bg-light" scope="col">تاريخ الإنشاء</th>
                                    <th class="bg-light" scope="col">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicalFacilityTypes as $medicalFacilityType)
                                <tr>
                                    <th scope="row">{{ $medicalFacilityType->id }}</th>
                                    <td>{{ $medicalFacilityType->name }}</td>
                                    <td>{{ $medicalFacilityType->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route(get_area_name().'.medical-facility-types.edit', $medicalFacilityType->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $medicalFacilityType->id }}">حذف</button>
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $medicalFacilityType->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        هل أنت متأكد من رغبتك في حذف هذا النوع؟
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                        <form action="{{ route(get_area_name().'.medical-facility-types.destroy', $medicalFacilityType->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">حذف</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Delete Modal -->
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

@endsection
