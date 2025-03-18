@extends('layouts.' . get_area_name())
@section('title', 'استيراد بيانات الأطباء')

@section('content')
      <div class="row">
         <div class="col-md-12">
               <div class="card">
                  <form action="{{ route(get_area_name().'.doctors.import-store') }}" method="post" enctype="multipart/form-data">
                     @csrf
                     <div class="card-header">
                           <h3 class="card-title text-center">استيراد بيانات الأطباء</h3>
                     </div>
                     <div class="card-body">
                           <div class="form-group text-center">
                              <label for="file">اختر ملف الاستيراد</label>
                              <input type="file" name="file" id="file" class="form-control-file">
                           </div>


                           <input type="hidden" name="doctor_type" value="{{request('doctor_type')}}">
                     </div>
                     <div class="card-footer">
                           <button type="submit" class="btn btn-primary">استيراد</button>
                     </div>
                  </form>
               </div>
         </div>
      </div>
@endsection
