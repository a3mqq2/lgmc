@extends('layouts.doctor')
@section('content')



<div class="tab-pane {{request('licences') ? "active" : ""}} " id="licences" role="tabpanel">
<div class="card">
   <div class="card-body">
         <h4 class="text-primary">منشآتي الطبية</h4>
   </div>   
</div> 
</div>


@endsection

@section('scripts')

@endsection
