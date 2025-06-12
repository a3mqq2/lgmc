@extends('layouts.doctor')
@section('title', 'تعديل طلب أوراق الخارج')

@section('content')
<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-primary mb-0">
                        <i class="fas fa-edit me-2"></i>
                        تعديل طلب أوراق الخارج #{{ str_pad($doctorMail->id, 6, '0', STR_PAD_LEFT) }}
                    </h3>
                    <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>
                        رجوع
                    </a>
                </div>

                @if($doctorMail->edit_note)
                <div class="alert alert-warning mb-4">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading mb-2">ملاحظات التعديل:</h6>
                            <p class="mb-0">{{ $doctorMail->edit_note }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="col-lg-12">
                <div>
                    <div class="tab-content pt-4 text-muted">
                        <div class="tab-pane active" id="requests" role="tabpanel">
                            <doctor-request-edit 
                                :doctor-mail-id="{{ $doctorMail->id }}"
                                :doctor-id="{{ auth()->user('doctor')->id }}"
                            ></doctor-request-edit>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection