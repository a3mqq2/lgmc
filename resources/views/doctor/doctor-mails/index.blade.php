@extends('layouts.doctor')

@push('styles')
<style>
.switch { position: relative; display: inline-block; width: 50px; height: 24px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 24px; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
.switch input:checked + .slider { background-color: #28a745; }
.switch input:focus + .slider { box-shadow: 0 0 1px #28a745; }
.switch input:checked + .slider:before { transform: translateX(26px); }
</style>
@endpush

@section('content')

<div class="card">
   <div class="card-body">
      <h4 class="text-primary font-weight-bold">طلب مستندات خارجية </h4>
      
      {{-- create new --}}

      <div class="d-flex justify-content-between">
         <a href="{{ route('doctor.doctor-mails.create') }}" class="btn btn-primary mb-3">إنشاء طلب جديد</a>
     

      <div class="row">
            @foreach ($doctorMails as $doctor_mail)
                <div class="col-md-12">
                  <div class="card border">
                        
                  </div>   
                </div>
            @endforeach
      </div>

   </div>
</div>
@endsection
