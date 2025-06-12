@extends('layouts.doctor')
@section('title', 'طلبات أوراق الخارج')

@section('styles')
<style>
.switch { 
    position: relative; 
    display: inline-block; 
    width: 50px; 
    height: 24px; 
}
.switch input { 
    opacity: 0; 
    width: 0; 
    height: 0; 
}
.slider { 
    position: absolute; 
    cursor: pointer; 
    top: 0; 
    left: 0; 
    right: 0; 
    bottom: 0; 
    background-color: #ccc; 
    transition: .4s; 
    border-radius: 24px; 
}
.slider:before { 
    position: absolute; 
    content: ""; 
    height: 18px; 
    width: 18px; 
    left: 3px; 
    bottom: 3px; 
    background-color: white; 
    transition: .4s; 
    border-radius: 50%; 
}
.switch input:checked + .slider { 
    background-color: #28a745; 
}
.switch input:focus + .slider { 
    box-shadow: 0 0 1px #28a745; 
}
.switch input:checked + .slider:before { 
    transform: translateX(26px); 
}

/* Custom styles for mobile-friendly design */
.request-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.request-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.request-header {
    background: linear-gradient(135deg, #F44336 0%, #9c1f25 100%);
    color: white;
    padding: 1.25rem;
    position: relative;
}

.request-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
}

.request-id {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
   text-align: left !important;
}

.request-date {
    opacity: 0.9;
    font-size: 0.9rem;
}

.status-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.request-content {
    padding: 1.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.info-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.1rem;
}

.info-text {
    flex: 1;
}

.info-label {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.info-value {
    font-weight: 600;
    color: #495057;
}

.services-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.service-tag {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.emails-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.email-tag {
    background: #17a2b8;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
}

.countries-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.country-tag {
    background: #28a745;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
}

.total-amount {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    text-align: center;
    padding: 1rem;
    border-radius: 10px;
    margin: 1rem 0;
}

.amount-value {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.25rem;
}

.amount-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
    margin-top: 1.5rem;
    flex-wrap: wrap;
}

.btn-action {
    flex: 1;
    min-width: 120px;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1.5rem;
}

.page-header {
   background: linear-gradient(135deg, #F44336 0%, #9c1f25 100%) !important;
    color: white;
    padding: 2rem 1.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translate(-50%, -50%) rotate(0deg); }
    50% { transform: translate(-50%, -50%) rotate(180deg); }
}

.header-content {
    position: relative;
    z-index: 2;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color:#fff;
}

.page-subtitle {
    opacity: 0.9;
    font-size: 1rem;
}

.create-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 50px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.create-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    color: white;
}

/* Responsive design */
@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem 1rem;
        margin-bottom: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .request-card {
        margin-bottom: 1rem;
    }
    
    .request-header {
        padding: 1rem;
    }
    
    .request-content {
        padding: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-action {
        min-width: auto;
    }
    
    .status-badge {
        position: static;
        display: inline-block;
        margin-top: 0.5rem;
    }
}

@media (max-width: 576px) {
    .info-item {
        flex-direction: column;
        text-align: center;
    }
    
    .info-icon {
        margin-right: 0;
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-envelope me-3"></i>
                        طلبات أوراق الخارج
                    </h1>
                    <p class="page-subtitle mb-0">
                        إدارة وتتبع جميع طلبات المستندات الخارجية الخاصة بك
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('doctor.doctor-mails.create') }}" class="create-btn">
                        <i class="fas fa-plus me-2"></i>
                        طلب جديد
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests List -->
    <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-body border" style="    border: 2px dashed #bb2c2b !important;
            font-size: 20px !important;
            font-weight: bold;">
               <p class="text-center m-0">سجل طلباتك السابقة <i class="fa fa-arrow-down"></i> </p>
            </div>
         </div>
      </div>
        @forelse($doctorMails as $doctorMail)
            <div class="col-12 col-lg-6 col-xl-4">
                <div class="request-card" style="">
                    <!-- Card Header -->
                    <div class="request-header">
                        <div class="request-id">
                            طلب رقم #{{ str_pad($doctorMail->id, 6, '0', STR_PAD_LEFT) }}
                        </div>
                        <div class="request-date">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ $doctorMail->created_at->format('Y/m/d - h:i A') }}
                        </div>
                        
                        @php
                            $statusClasses = [
                                'under_approve' => 'bg-warning',
                                'under_edit' => 'bg-info',
                                'under_payment' => 'bg-primary',
                                'under_proccess' => 'bg-secondary',
                                'done' => 'bg-success',
                                'canceled' => 'bg-danger'
                            ];
                            $statusLabels = [
                                'under_approve' => 'قيد الموافقة',
                                'under_edit' => 'قيد التعديل',
                                'under_payment' => 'قيد الدفع',
                                'under_proccess' => 'قيد الإجراء',
                                'done' => 'مكتمل',
                                'canceled' => 'ملغي'
                            ];
                        @endphp
                        
                        <div class="status-badge">
                            {{ $statusLabels[$doctorMail->status] ?? $doctorMail->status }}
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="request-content">
                        <!-- Services -->
                        <div class="info-item">
                            <div class="info-icon bg-primary text-white">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="info-text">
                                <div class="info-label">الخدمات المطلوبة</div>
                                <div class="info-value">{{ $doctorMail->services->count() }} خدمة</div>
                                <div class="services-list">
                                    @foreach($doctorMail->services->take(3) as $service)
                                        <span class="service-tag">{{ $service->service_name }}</span>
                                    @endforeach
                                    @if($doctorMail->services->count() > 3)
                                        <span class="service-tag">+{{ $doctorMail->services->count() - 3 }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Emails -->
                        <div class="info-item">
                            <div class="info-icon bg-info text-white">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-text">
                                <div class="info-label">عناوين البريد الإلكتروني</div>
                                <div class="info-value">{{ $doctorMail->emails->count() }} بريد إلكتروني</div>
                                <div class="emails-list">
                                    @foreach($doctorMail->emails->take(2) as $email)
                                        <span class="email-tag">{{ $email->email_value }}</span>
                                    @endforeach
                                    @if($doctorMail->emails->count() > 2)
                                        <span class="email-tag">+{{ $doctorMail->emails->count() - 2 }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Countries -->
                        @if($doctorMail->countries->count() > 0)
                            <div class="info-item">
                                <div class="info-icon bg-success text-white">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div class="info-text">
                                    <div class="info-label">الدول المستهدفة</div>
                                    <div class="info-value">{{ $doctorMail->countries->count() }} دولة</div>
                                    <div class="countries-list">
                                        @foreach($doctorMail->countries->take(3) as $country)
                                            <span class="country-tag">{{ $country->country_value }}</span>
                                        @endforeach
                                        @if($doctorMail->countries->count() > 3)
                                            <span class="country-tag">+{{ $doctorMail->countries->count() - 3 }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Notes -->
                        @if($doctorMail->notes)
                            <div class="info-item">
                                <div class="info-icon bg-warning text-white">
                                    <i class="fas fa-sticky-note"></i>
                                </div>
                                <div class="info-text">
                                    <div class="info-label">ملاحظات</div>
                                    <div class="info-value">{{ Str::limit($doctorMail->notes, 50) }}</div>
                                </div>
                            </div>
                        @endif

                        <!-- Edit Note -->
                        @if($doctorMail->edit_note)
                            <div class="info-item border border-warning">
                                <div class="info-icon bg-warning text-white">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="info-text">
                                    <div class="info-label text-warning">ملاحظات التعديل</div>
                                    <div class="info-value text-warning">{{ $doctorMail->edit_note }}</div>
                                </div>
                            </div>
                        @endif

                        <!-- Total Amount -->
                        <div class="total-amount">
                            <div class="amount-value">{{ number_format($doctorMail->grand_total, 2) }} د.ل</div>
                            <div class="amount-label">المبلغ الإجمالي</div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('doctor.doctor-mails.show', $doctorMail) }}" 
                               class="btn btn-primary btn-action">
                                <i class="fas fa-eye me-2"></i>
                                عرض التفاصيل
                            </a>
                            
                            @if($doctorMail->status == 'under_edit')
                                <a href="{{ route('doctor.doctor-mails.edit', $doctorMail) }}" 
                                   class="btn btn-warning btn-action">
                                    <i class="fas fa-edit me-2"></i>
                                    تعديل الطلب
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3 class="text-muted mb-3">لا توجد طلبات بعد</h3>
                    <p class="text-muted mb-4">
                        لم تقم بإنشاء أي طلبات لأوراق الخارج حتى الآن. 
                        ابدأ بإنشاء طلبك الأول الآن!
                    </p>
                    <a href="{{ route('doctor.doctor-mails.create') }}" class="create-btn">
                        <i class="fas fa-plus me-2"></i>
                        إنشاء أول طلب
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Add any JavaScript functionality here
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips if needed
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    });
</script>
@endsection