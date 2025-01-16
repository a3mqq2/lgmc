@extends('layouts.'.get_area_name())
@section('title', 'قائمة التسعيرات')

@section('content')

<div class="container-fluid">
   {{-- Create Button --}}
   <div class="row mb-2">
      <div class="col-md-12">
         <a href="{{ route(get_area_name().'.pricings.create') }}" class="btn btn-primary">إضافة جديد</a>
      </div>
   </div>

   {{-- Filter Section --}}
   <div class="row mb-4">
      <div class="col-md-12">
         <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
               <h5 class="card-title text-light mb-0">فلتر</h5>
            </div>
            <div class="card-body">
               <form method="GET" action="{{ route(get_area_name().'.pricings.index') }}">
                  <div class="row">
                     {{-- Description Filter --}}
                     <div class="col-md-3">
                        <div class="form-group">
                           <label for="description">الوصف</label>
                           <input type="text" name="description" id="description" class="form-control" value="{{ request()->description }}">
                        </div>
                     </div>
                     {{-- Type Filter --}}
                     <div class="col-md-3">
                        <div class="form-group">
                           <label for="type">النوع</label>
                           <select name="type" id="type" class="form-select">
                              <option value="">اختر النوع</option>
                              <option value="membership" {{ request()->type == 'membership' ? 'selected' : '' }}>عضوية</option>
                              <option value="license" {{ request()->type == 'license' ? 'selected' : '' }}>إذن مزاولة</option>
                              <option value="service" {{ request()->type == 'service' ? 'selected' : '' }}>خدمة</option>
                              <option value="penalty" {{ request()->type == 'penalty' ? 'selected' : '' }}> غرامة </option>
                           </select>
                        </div>
                     </div>
                     {{-- Entity Type Filter --}}
                     <div class="col-md-3">
                        <div class="form-group">
                           <label for="entity_type">الجهة المستهدفة</label>
                           <select name="entity_type" id="entity_type" class="form-select">
                              <option value="">اختر الجهة</option>
                              <option value="doctor" {{ request()->entity_type == 'doctor' ? 'selected' : '' }}>طبيب</option>
                              <option value="medical_facility" {{ request()->entity_type == 'medical_facility' ? 'selected' : '' }}>منشأة طبية</option>
                           </select>
                        </div>
                     </div>
                     {{-- Doctor Type Filter --}}
                     <div class="col-md-3">
                        <div class="form-group">
                           <label for="doctor_type">نوع الطبيب</label>
                           <select name="doctor_type" id="doctor_type" class="form-select">
                              <option value="">اختر نوع الطبيب</option>
                              <option value="libyan" {{ request()->doctor_type == 'libyan' ? 'selected' : '' }}>ليبي</option>
                              <option value="foreign" {{ request()->doctor_type == 'foreign' ? 'selected' : '' }}>أجنبي</option>
                              <option value="visitor" {{ request()->doctor_type == 'visitor' ? 'selected' : '' }}>زائر</option>
                              <option value="palestinian" {{ request()->doctor_type == 'palestinian' ? 'selected' : '' }}>فلسطيني</option>
                           </select>
                        </div>
                     </div>
                     {{-- Value Filter --}}
                     <div class="col-md-3">
                        <div class="form-group">
                           <label for="value">القيمة</label>
                           <input type="text" name="value" id="value" class="form-control" value="{{ request()->value }}">
                        </div>
                     </div>
                     {{-- Filter Buttons --}}
                     <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary">بحث</button>
                        <a href="{{ route(get_area_name().'.pricings.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>

   {{-- Pricings Table --}}
   <div class="row">
      <div class="col-md-12">
         <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
               <h5 class="card-title text-light mb-0">قائمة التسعيرات</h5>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-hover table-bordered">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>الوصف</th>
                           <th>النوع</th>
                           <th>الجهة</th>
                           <th>نوع الطبيب</th>
                           <th>القيمة</th>
                           <th>الإجراءات</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($pricings as $pricing)
                           <tr>
                              <th scope="row">{{ $pricing->id }}</th>
                              <td>{{ $pricing->name }}</td>
                              <td>
                                 <span class="{{ $pricing->type ? $pricing->type->badgeClass() : '' }}">
                                    {{ $pricing->type ? $pricing->type->label() : '-' }}
                                 </span>
                              </td>
                              <td>
                                 <span class="{{ $pricing->entity_type ? $pricing->entity_type->badgeClass() : '' }}">
                                    {{ $pricing->entity_type ? $pricing->entity_type->label() : '-' }}
                                 </span>
                              </td>
                              <td>
                                 @if ($pricing->doctor_type)
                                 <span class="badge {{ $pricing->doctor_type->badgeClass() }}" >{{ $pricing->doctor_type->label() }}</span>
                                     @else 
                                    N\A
                                 @endif
                              </td>
                              <td>{{ $pricing->amount }} د.ل</td>
                              <td>
                                 <a href="{{ route(get_area_name().'.pricings.edit', $pricing->id) }}" class="btn btn-sm btn-primary">تعديل</a>
                                 {{-- <form action="{{ route(get_area_name().'.pricings.destroy', $pricing->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                                 </form> --}}
                              </td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
               {{ $pricings->appends($_GET)->links() }}
            </div>
         </div>
      </div>
   </div>
</div>

@endsection
