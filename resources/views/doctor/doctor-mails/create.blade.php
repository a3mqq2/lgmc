@extends('layouts.doctor')
@section('content')
    

<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="col-md-12">
                <h3 class="text-primary">طلب اوراق خارجية جديد  </h3> 
            </div>
            <div class="col-lg-12">
                <div>
                    <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="requests" role="tabpanel">
                     <doctor-request :doctor-id="{{ auth()->user('doctor')->id }}"></doctor-request>
                    </div>
                 </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection