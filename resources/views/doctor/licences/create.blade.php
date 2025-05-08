@extends('layouts.doctor')
@section('content')



<div class="tab-pane {{request('licences') ? "active" : ""}} " id="licences" role="tabpanel">
   <div class="card">
       <div class="card-body">
           <h4 class="font-weight-bold text-primary">
               <i class="fas fa-id-card text-primary"></i> اذونات مزاولة  - اضافة جديد 
           </h4>

           <div class="row">
               
               <form action="{{route('doctor.licences.store')}}">
                  @csrf
                  @method('POST')

                  <div class="row">
                     <div class="col-md-12">
                        <label for="">مكان العمل</label>
                        <select name="medical_facility_id" id="medical_facility_id" required  class="  selectize">
                           <option value="">اختر مكان العمل</option>
                           @foreach ($medical_facilities as $medical_facility)
                              <option value="{{ $medical_facility->id }}">{{ $medical_facility->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>


                  {{-- submit button --}}
                  <div class="row mt-3">
                     <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">اضافة اذن مزاولة جديد</button>
                     </div>
                  </div>

               </form>

           </div>



       </div>
   </div>
   </div>


@endsection

@section('scripts')

@endsection
