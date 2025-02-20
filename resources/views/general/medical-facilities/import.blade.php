@extends('layouts.' . get_area_name())
@section('title', 'استيراد بيانات الشركات الطبية')

@section('content')
      <div class="row">
         <div class="col-md-12">
               <div class="card">
                  <form action="{{ route('admin.medical-facilities.import-store') }}" method="post" enctype="multipart/form-data">
                     @csrf
                     <div class="card-header">
                           <h3 class="card-title text-center">استيراد بيانات الشركات الطبية</h3>
                     </div>
                     <div class="card-body">
                           <div class="form-group text-center">
                              <label for="file">اختر ملف الاستيراد</label>
                              <input type="file" name="file" id="file" class="form-control-file">
                           </div>
                     </div>
                     <div class="card-footer">
                           <button type="submit" class="btn btn-primary">استيراد</button>
                     </div>
                  </form>
               </div>
         </div>
      </div>
@endsection
