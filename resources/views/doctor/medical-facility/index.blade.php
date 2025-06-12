@extends('layouts.doctor')

@section('styles')
<style>
/* Upload Report Modal Styles */
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    transition: all 0.3s ease;
    cursor: pointer;
    background: #fafafa;
}

.upload-area:hover {
    border-color: var(--primary-color);
    background: #f8f9fa;
}

.upload-area.dragover {
    border-color: var(--primary-color);
    background: rgba(185, 28, 28, 0.05);
}

.upload-content {
    padding: 2rem;
}

.selected-file {
    border: 1px solid #28a745 !important;
    background: #f8fff9 !important;
}

.modal-header.bg-primary {
    background: linear-gradient(135deg, var(--blood-red) 0%, var(--primary-color) 100%) !important;
}

#uploadProgress .progress {
    height: 8px;
    border-radius: 4px;
}

#uploadProgress .progress-bar {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    transition: width 0.3s ease;
}

/* File upload validation styles */
.upload-area.error {
    border-color: #dc3545;
    background: rgba(220, 53, 69, 0.05);
}

.upload-area.success {
    border-color: #28a745;
    background: rgba(40, 167, 69, 0.05);
}

/* Report upload button styles */
.btn-upload-report {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: var(--transition);
}

.btn-upload-report:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    color: white;
}

/* Missing report indicator */
.missing-report-indicator {
    position: relative;
}

.missing-report-indicator::after {
    content: '';
    position: absolute;
    top: -2px;
    right: -2px;
    width: 8px;
    height: 8px;
    background: #dc3545;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>
<style>
    /* ===== Visitor Cards Styles ===== */
    .visitor-cards-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .visitor-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        border: 2px solid transparent;
        transition: var(--transition);
        overflow: hidden;
        margin-bottom: 1rem;
    }
    
    .visitor-card.active {
        border-color: var(--success-color);
    }
    
    .visitor-card.expired {
        border-color: var(--danger-color);
        opacity: 0.9;
    }
    
    .visitor-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow-hover);
    }
    
    /* ===== Card Header ===== */
    .visitor-card-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .visitor-info {
        flex: 1;
        min-width: 0;
    }
    
    .visitor-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0 0 0.25rem 0;
        line-height: 1.3;
    }
    
    .visitor-email {
        font-size: 0.875rem;
        color: var(--secondary-color);
        margin: 0;
    }
    
    .visitor-status {
        flex-shrink: 0;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
        min-width: 80px;
        justify-content: center;
    }
    
    .status-badge.active {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
    
    .status-badge.expired {
        background: linear-gradient(135deg, var(--danger-color) 0%, #b91c1c 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
    }
    
    .status-badge.expiring {
        background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }
    
    /* ===== Card Body ===== */
    .visitor-card-body {
        padding: 1.5rem;
    }
    
    .visitor-details {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .detail-item:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--secondary-color);
        font-weight: 500;
        flex: 1;
    }
    
    .detail-label i {
        width: 16px;
        color: var(--primary-color);
    }
    
    .detail-value {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary-dark);
        text-align: left;
        max-width: 50%;
        word-break: break-word;
    }
    
    /* ===== Card Footer ===== */
    .visitor-card-footer {
        padding: 1rem 1.5rem;
        background: #fafafa;
        border-top: 1px solid #e2e8f0;
    }
    
    .visitor-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: 0.75rem;
    }
    
    .action-btn-mobile {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        padding: 0.75rem 0.5rem;
        border-radius: var(--border-radius-sm);
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        transition: var(--transition);
        border: none;
        cursor: pointer;
        min-height: 60px;
        justify-content: center;
    }
    
    .action-btn-mobile i {
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
    }
    
    .action-btn-mobile.primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
    }
    
    .action-btn-mobile.warning {
        background: linear-gradient(135deg, var(--warning-color), #d97706);
        color: white;
    }
    
    .action-btn-mobile.success {
        background: linear-gradient(135deg, var(--success-color), #059669);
        color: white;
    }
    
    .action-btn-mobile.danger {
        background: linear-gradient(135deg, var(--danger-color), #b91c1c);
        color: white;
    }
    
    .action-btn-mobile:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: white;
    }
    
    /* ===== Responsive Enhancements ===== */
    @media (max-width: 576px) {
        .visitor-card-header {
            padding: 1rem;
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
        }
    
        .visitor-status {
            align-self: center;
        }
    
        .visitor-card-body {
            padding: 1rem;
        }
    
        .visitor-card-footer {
            padding: 1rem;
        }
    
        .visitor-actions {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }
    
        .action-btn-mobile {
            padding: 0.5rem;
            min-height: 50px;
            font-size: 0.75rem;
        }
    
        .detail-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
            padding: 0.5rem 0;
        }
    
        .detail-value {
            max-width: 100%;
            text-align: right;
            align-self: flex-end;
        }
    
        .visitor-name {
            font-size: 1rem;
        }
    
        .status-badge {
            width: 100%;
            padding: 0.75rem;
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 375px) {
        .visitor-actions {
            grid-template-columns: 1fr;
        }
    
        .action-btn-mobile {
            flex-direction: row;
            gap: 0.5rem;
            justify-content: center;
            min-height: 44px;
        }
    
        .action-btn-mobile i {
            margin-bottom: 0;
            font-size: 1rem;
        }
    }
    
    /* ===== Animation للكروت ===== */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .visitor-card {
        animation: slideInUp 0.5s ease-out;
    }
    
    .visitor-card:nth-child(1) { animation-delay: 0.1s; }
    .visitor-card:nth-child(2) { animation-delay: 0.2s; }
    .visitor-card:nth-child(3) { animation-delay: 0.3s; }
    .visitor-card:nth-child(4) { animation-delay: 0.4s; }
    .visitor-card:nth-child(5) { animation-delay: 0.5s; }
    
    /* ===== Empty State للهواتف ===== */
    @media (max-width: 768px) {
        .empty-state {
            padding: 2rem 1rem;
        }
        
        .empty-state-icon {
            font-size: 2.5rem !important;
        }
        
        .empty-state h4 {
            font-size: 1.25rem;
        }
        
        .empty-state p {
            font-size: 1rem;
        }
    }
    </style>
    
    <script>
    function deleteVisitor(visitorId) {
        if (confirm('هل أنت متأكد من حذف هذا الطبيب الزائر؟')) {
            // إنشاء نموذج للحذف
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('doctor.visitor-doctors.destroy', '') }}/${visitorId}`;
            
            // إضافة CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            // إضافة method DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // تحسين الأداء للهواتف
    document.addEventListener('DOMContentLoaded', function() {
        // إضافة تأثير اللمس للكروت
        const visitorCards = document.querySelectorAll('.visitor-card');
        
        visitorCards.forEach(card => {
            card.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            card.addEventListener('touchend', function() {
                this.style.transform = '';
            });
        });
        
        // تحسين الأداء عند التمرير
        let ticking = false;
        
        function updateCards() {
            const cards = document.querySelectorAll('.visitor-card');
            const scrollTop = window.pageYOffset;
            
            cards.forEach((card, index) => {
                const cardTop = card.offsetTop;
                const cardHeight = card.offsetHeight;
                const windowHeight = window.innerHeight;
                
                if (scrollTop + windowHeight > cardTop && scrollTop < cardTop + cardHeight) {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }
            });
            
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateCards);
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestTick);
    });
    </script>
<style>
/* ===== CSS Variables ===== */
:root {
    --primary-color: #b91c1c;
    --primary-light: #dc2626;
    --primary-dark: #991b1b;
    --blood-red: #7f1d1d;
    --blood-red-light: #a91b1b;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #dc2626;
    --info-color: #b91c1c;
    --secondary-color: #6b7280;
    --light-bg: #fef2f2;
    --card-shadow: 0 10px 25px -5px rgba(185, 28, 28, 0.15), 0 10px 10px -5px rgba(185, 28, 28, 0.08);
    --card-shadow-hover: 0 20px 25px -5px rgba(185, 28, 28, 0.2), 0 10px 10px -5px rgba(185, 28, 28, 0.1);
    --border-radius: 16px;
    --border-radius-sm: 8px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== Helpers ===== */
.text-end { text-align: right !important; }

/* ===== Tabs ===== */
.nav-tabs {
    border-bottom: none;
    margin-bottom: 1.5rem;
    gap: 0.75rem;
    flex-wrap: nowrap;
    overflow-x: auto;
}

.nav-tabs .nav-link {
    border: none;
    border-radius: 50px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    background: #fef2f2;
    color: var(--primary-dark);
    transition: var(--transition);
    white-space: nowrap;
}

.nav-tabs .nav-link.active,
.nav-tabs .nav-link:hover {
    background: linear-gradient(135deg, var(--blood-red) 0%, var(--primary-color) 50%, var(--primary-light) 100%);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(185, 28, 28, 0.3);
}

/* ===== Page Header ===== */
.page-header {
    background: linear-gradient(135deg, var(--blood-red) 0%, var(--primary-color) 50%, var(--primary-light) 100%);
    margin: -1rem -1rem 2rem -1rem;
    padding: 2rem 1rem;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
    color: white;
    box-shadow: var(--card-shadow);
}

.page-header h3 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(127, 29, 29, 0.3);
}

.page-header i {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem;
    border-radius: var(--border-radius-sm);
    margin-left: 0.5rem;
}

/* ===== Enhanced Cards ===== */
.enhanced-card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    overflow: hidden;
    background: white;
}

.enhanced-card:hover {
    box-shadow: var(--card-shadow-hover);
    transform: translateY(-2px);
}

.enhanced-card .card-header {
    background: linear-gradient(135deg, var(--blood-red) 0%, var(--primary-color) 50%, var(--primary-light) 100%);
    border: none;
    padding: 1.25rem 1.5rem;
    font-weight: 600;
    color: white;
}

.enhanced-card .card-body {
    padding: 2rem 1.5rem;
}

/* ===== Status Badges Enhanced ===== */
.badge-under_approve { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color:#92400e;font-weight:600;padding:0.5rem 1rem;border-radius:50px;font-size:0.875rem;box-shadow:0 4px 6px -1px rgba(251,191,36,0.3); }
.badge-under_complete{background:linear-gradient(135deg,#fb923c 0%,#f97316 100%);color:white;font-weight:600;padding:0.5rem 1rem;border-radius:50px;font-size:0.875rem;box-shadow:0 4px 6px -1px rgba(249,115,22,0.3);}
.badge-under_edit{background:linear-gradient(135deg,#34d399 0%,#10b981 100%);color:white;font-weight:600;padding:0.5rem 1rem;border-radius:50px;font-size:0.875rem;box-shadow:0 4px 6px -1px rgba(16,185,129,0.3);}
.badge-active{background:linear-gradient(135deg,#4ade80 0%,#22c55e 100%);color:white;font-weight:600;padding:0.5rem 1rem;border-radius:50px;font-size:0.875rem;box-shadow:0 4px 6px -1px rgba(34,197,94,0.3);}
.badge-expired{background:linear-gradient(135deg,#f87171 0%,#ef4444 100%);color:white;font-weight:600;padding:0.5rem 1rem;border-radius:50px;font-size:0.875rem;box-shadow:0 4px 6px -1px rgba(239,68,68,0.3);}
.badge-banned{background:linear-gradient(135deg,#9ca3af 0%,#6b7280 100%);color:white;font-weight:600;padding:0.5rem 1rem;border-radius:50px;font-size:0.875rem;box-shadow:0 4px 6px -1px rgba(107,114,128,0.3);}

/* ===== Empty State Enhanced ===== */
.empty-state {text-align:center;padding:3rem 2rem;background:linear-gradient(135deg,#fef2f2 0%,#fee2e2 100%);border-radius:var(--border-radius);}
.empty-img{max-width:280px;width:100%;opacity:0.9;filter:drop-shadow(0 10px 20px rgba(0,0,0,0.1));margin-bottom:2rem;}
.empty-state h4{color:var(--secondary-color);font-weight:600;margin-bottom:1rem;}
.empty-state p{color:var(--secondary-color);font-size:1.125rem;margin-bottom:2rem;}

/* ===== Enhanced Buttons ===== */
.btn-enhanced{padding:0.875rem 2rem;border-radius:50px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;transition:var(--transition);border:none;font-size:1rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);}
.btn-enhanced:hover{transform:translateY(-2px);box-shadow:0 10px 20px -5px rgba(0,0,0,0.2);}
.btn-enhanced.btn-primary{background:linear-gradient(135deg,var(--blood-red) 0%,var(--primary-color) 50%,var(--primary-light) 100%);color:white;box-shadow:0 4px 15px rgba(185,28,28,0.3);}
.btn-enhanced.btn-success{background:linear-gradient(135deg,var(--success-color) 0%,#059669 100%);color:white;}
.btn-enhanced.btn-info{background:linear-gradient(135deg,var(--info-color) 0%,#0891b2 100%);color:white;}
.btn-enhanced.btn-secondary{background:linear-gradient(135deg,var(--secondary-color) 0%,#4b5563 100%);color:white;}

/* ===== Status Cards Enhanced - خلفية بيضاء وخط أكبر ===== */
.status-card {
    background: white !important; /* خلفية بيضاء ثابتة */
    border-radius: var(--border-radius);
    padding: 3rem 2.5rem; /* زيادة الحشو */
    text-align: center;
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    border: 3px solid transparent; /* حدود أوضح */
}

.status-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--card-shadow-hover);
}

/* كرت النجاح */
.status-card.success-card {
    border: 3px solid var(--success-color);
    background: white !important; /* إزالة الخلفية الملونة */
}

/* كرت التحذير */
.status-card.warning-card {
    border: 3px solid var(--warning-color);
    background: white !important; /* إزالة الخلفية الملونة */
}

/* كرت المعلومات */
.status-card.info-card {
    border: 3px solid var(--primary-color);
    background: white !important; /* إزالة الخلفية الملونة */
}

/* كرت منتهي الصلاحية */
.status-card.expired-card {
    border: 3px solid #ef4444 !important;
    background: white !important; /* إزالة الخلفية الملونة */
    position: relative;
    overflow: hidden;
}

.status-card.expired-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px; /* زيادة سمك الخط العلوي */
    background: linear-gradient(90deg, #ef4444, #dc2626, #b91c1c);
    animation: expiredGlow 2s ease-in-out infinite alternate;
}

/* صور الكروت */
.status-card img {
    filter: drop-shadow(0 8px 16px rgba(0,0,0,0.15));
    margin-bottom: 2rem; /* زيادة المسافة */
    max-width: 120px; /* زيادة حجم الصورة */
}

/* عناوين الكروت */
.status-card h4 {
    font-weight: 700;
    line-height: 1.4;
    margin-bottom: 2rem; /* زيادة المسافة */
    font-size: 1.75rem !important; /* زيادة حجم الخط */
    color: #1f2937 !important; /* لون ثابت للوضوح */
}

/* نصوص الكروت */
.status-card p {
    font-size: 1.25rem !important; /* زيادة حجم الخط */
    line-height: 1.6;
    margin-bottom: 2rem;
    color: #4b5563 !important; /* لون رمادي واضح */
    font-weight: 500;
}

/* النصوص الصغيرة */
.status-card .text-muted {
    font-size: 1.1rem !important; /* زيادة حجم الخط الصغير */
    color: #6b7280 !important;
    font-weight: 500;
}

/* الأزرار داخل الكروت */
.status-card .btn {
    font-size: 1.1rem !important; /* زيادة حجم خط الأزرار */
    padding: 1rem 2.5rem !important; /* زيادة حجم الأزرار */
    font-weight: 600;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    transition: var(--transition);
}

.status-card .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

/* تحسين عرض معلومات انتهاء الصلاحية */
.status-card .d-flex.flex-column.flex-md-row {
    gap: 1.5rem !important;
}

.status-card .small {
    font-size: 1rem !important; /* جعل النص "الصغير" أكبر */
    font-weight: 600;
    color: #374151 !important;
}

/* تحسين ألوان النصوص المختلفة */
.status-card .text-success {
    color: #059669 !important;
    font-weight: 700;
}

.status-card .text-warning {
    color: #d97706 !important;
    font-weight: 700;
}

.status-card .text-danger {
    color: #dc2626 !important;
    font-weight: 700;
}

/* تحسين مظهر الأيقونات */
.status-card i {
    font-size: 1.2em;
    margin-left: 0.5rem;
}

@keyframes expiredGlow {
    from {
        opacity: 0.6;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
    }
    to {
        opacity: 1;
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.6);
    }
}

/* ===== Info List Enhanced ===== */
.info-list{background:white;border-radius:var(--border-radius);overflow:hidden;box-shadow:var(--card-shadow);}
.info-item{padding:1.25rem 1.5rem;border:none;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;transition:var(--transition);}
.info-item:last-child{border-bottom:none;}
.info-item:hover{background:linear-gradient(135deg,#fef2f2 0%,rgba(185,28,28,0.02) 100%);}
.info-item .info-label{display:flex;align-items:center;gap:0.75rem;color:var(--secondary-color);font-weight:500;}
.info-item .info-label i{width:32px;height:32px;display:flex;align-items:center;justify-content:center;border-radius:var(--border-radius-sm);font-size:0.875rem;}
.info-item .info-label i.fa-clinic-medical{background:linear-gradient(135deg,var(--blood-red),var(--primary-color));color:white;}
.info-item .info-label i.fa-tags{background:linear-gradient(135deg,var(--primary-light),var(--primary-color));color:white;}
.info-item .info-label i.fa-map-marker-alt{background:linear-gradient(135deg,var(--danger-color),var(--primary-color));color:white;}
.info-item .info-label i.fa-phone-alt{background:linear-gradient(135deg,var(--success-color),#059669);color:white;}
.info-item .info-label i.fa-calendar-alt{background:linear-gradient(135deg,var(--warning-color),#d97706);color:white;}
.info-item .info-value{font-weight:600;color:#1f2937;}

/* ===== Visitor Doctors Table Enhanced ===== */
.visitors-table {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--card-shadow);
}

.visitors-table thead th {
    background: linear-gradient(135deg, var(--blood-red) 0%, var(--primary-color) 100%);
    color: white;
    font-weight: 600;
    padding: 1rem 0.75rem;
    border: none;
    font-size: 0.9rem;
    text-align: center;
}

.visitors-table tbody tr {
    border: none;
    transition: var(--transition);
}

.visitors-table tbody tr:hover {
    background: linear-gradient(135deg, #fef2f2 0%, rgba(185, 28, 28, 0.02) 100%);
}

.visitors-table tbody td {
    padding: 1rem 0.75rem;
    border: none;
    color: var(--secondary-color);
    text-align: center;
    vertical-align: middle;
}

.visitors-table tbody tr:not(:last-child) td {
    border-bottom: 1px solid #f1f5f9;
}

/* ===== Visitor Status Badges ===== */
.visitor-status-active {
    background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
    color: white;
    font-weight: 600;
    padding: 0.375rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}

.visitor-status-expired {
    background: linear-gradient(135deg, var(--danger-color) 0%, #b91c1c 100%);
    color: white;
    font-weight: 600;
    padding: 0.375rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);
}

.visitor-status-expiring {
    background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
    color: white;
    font-weight: 600;
    padding: 0.375rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
}

/* ===== Action Buttons ===== */
.action-btn-group {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.action-btn {
    padding: 0.5rem;
    border: none;
    border-radius: 6px;
    transition: var(--transition);
    color: white;
    font-size: 0.875rem;
    min-width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    color: white;
}

.action-btn.view {
    background: var(--primary-color);
}

.action-btn.edit {
    background: var(--warning-color);
}

.action-btn.renew {
    background: var(--info-color);
}

.action-btn.delete {
    background: var(--danger-color);
}

/* ===== Animations ===== */
@keyframes fadeInUp{from{opacity:0;transform:translateY(30px);}to{opacity:1;transform:translateY(0);}}
.fade-in-up{animation:fadeInUp 0.6s ease-out;}
@keyframes pulse{0%,100%{opacity:1;}50%{opacity:0.8;}}
.pulse-animation{animation:pulse 2s infinite;}

/* ===== Responsive Design ===== */
@media (max-width: 768px) {
    .page-header{margin:-0.5rem -0.5rem 1.5rem -0.5rem;padding:1.5rem 1rem;}
    .page-header h3{font-size:1.5rem;}
    .enhanced-card .card-body{padding:1.5rem 1rem;}
    .info-item{padding:1rem;flex-direction:column;align-items:flex-start;gap:0.5rem;}
    .info-item .info-value{text-align:right;width:100%;}
    
    .status-card {
        padding: 2.5rem 1.5rem;
    }
    
    .status-card h4 {
        font-size: 1.5rem !important;
    }
    
    .status-card p {
        font-size: 1.1rem !important;
    }
    
    .status-card .small {
        font-size: 0.95rem !important;
    }
    
    .status-card img {
        max-width: 100px;
    }

    .visitors-table thead th,
    .visitors-table tbody td {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
    }

    .action-btn-group {
        flex-direction: column;
        gap: 0.25rem;
    }
}

/* ===== Renew Status Card ===== */
.status-card.renew-card {
    border: 3px solid var(--info-color);
    background: white !important;
    position: relative;
    overflow: hidden;
}

.status-card.renew-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--info-color), var(--primary-color), var(--primary-light));
    animation: renewGlow 3s ease-in-out infinite alternate;
}

@keyframes renewGlow {
    from {
        opacity: 0.7;
        box-shadow: 0 0 15px rgba(185, 28, 28, 0.3);
    }
    to {
        opacity: 1;
        box-shadow: 0 0 25px rgba(185, 28, 28, 0.5);
    }
}

/* Enhanced renew badge */
.badge-renew {
    background: linear-gradient(135deg, var(--info-color) 0%, var(--primary-color) 100%);
    color: white;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    box-shadow: 0 4px 6px -1px rgba(185, 28, 28, 0.3);
    position: relative;
    overflow: hidden;
}

.badge-renew::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shimmer 2s infinite;
}

/* Processing animation for renew status */
.processing-animation {
    animation: processing 2s ease-in-out infinite;
}

@keyframes processing {
    0%, 100% { 
        transform: scale(1);
        opacity: 1;
    }
    50% { 
        transform: scale(1.05);
        opacity: 0.9;
    }
}

/* Enhanced alerts inside renew card */
.status-card.renew-card .alert {
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    padding: 1rem;
}

.status-card.renew-card .alert-info {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
    color: #1e40af;
    border-left: 4px solid #3b82f6;
}

.status-card.renew-card .alert-warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.05) 100%);
    color: #92400e;
    border-left: 4px solid #f59e0b;
}

.status-card.renew-card .alert-primary {
    background: linear-gradient(135deg, rgba(185, 28, 28, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
    color: var(--primary-dark);
    border-left: 4px solid var(--primary-color);
}

.status-card.renew-card .alert-secondary {
    background: linear-gradient(135deg, rgba(107, 114, 128, 0.1) 0%, rgba(75, 85, 99, 0.05) 100%);
    color: #374151;
    border-left: 4px solid #6b7280;
}

/* Responsive improvements for renew card */
@media (max-width: 768px) {
    .status-card.renew-card .row.g-3 {
        gap: 1rem !important;
    }
    
    .status-card.renew-card .alert {
        padding: 0.75rem;
        font-size: 0.9rem;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid px-0">

{{-- ===============================================================
   إذا لم توجد منشأة
================================================================ --}}
@unless(auth('doctor')->user()->medicalFacility)
    <div class="enhanced-card fade-in-up">
        <div class="card-body empty-state">
            <h4 class="text-end mb-3">
                <i class="fas fa-exclamation-circle text-warning ms-1"></i>
                عذرًا، لا توجد لديك منشأة طبية مسجَّلة.
            </h4>

            <img src="{{ asset('assets/images/No data-pana.png') }}" class="empty-img" alt="no-data">

            <p class="mb-4">يمكنك التقديم لإضافة منشأة طبية جديدة والبدء في تقديم الخدمات الطبية.</p>

            <a href="{{ route('doctor.my-facility.create') }}" class="btn-enhanced btn-primary btn-lg pulse-animation">
                <i class="fas fa-plus-circle"></i> اضغط هنا للتقديم
            </a>
        </div>
    </div>
@endunless

{{-- ===============================================================
   إذا وُجدت منشأة
================================================================ --}}
@isset(auth('doctor')->user()->medicalFacility)
@php
    $facility   = auth('doctor')->user()->medicalFacility;
    $statusVal  = $facility->membership_status->value;
    $isPending  = in_array($statusVal, ['under_edit']);
@endphp

@if ($facility->membership_status->value != 'active')

<div class="card">
   <div class="card-body">

{{-- ------------------------ كروت الحالة ------------------------ --}}
<div class="row g-4 mb-3">
    @if($statusVal=='under_complete')
        <div class="col-12">
            <div class="status-card success-card fade-in-up">
                <img src="{{asset('assets/images/celebrate.png')}}" width="120">
                <h4 class="text-success">🎉 تم حفظ بيانات منشأتك بنجاح</h4>
                <p class="text-muted">يرجى رفع الملفات المطلوبة ليتم إرسال الطلب للمراجعة والموافقة.</p>
                <a href="{{route('doctor.my-facility.upload-documents')}}" class="btn btn-success">رفع المستندات ( اضغط هنا )</a>
            </div>
        </div>
    @elseif($statusVal=='under_approve')
        <div class="col-12">
            <div class="status-card warning-card fade-in-up">
                <img src="{{asset('assets/images/review.png')}}" width="120">
                <h4 class="text-warning">⏳ طلبك قيد المراجعة</h4>
                <p class="text-muted mb-0">تم إرسال طلبك بنجاح وهو الآن قيد المراجعة من قبل الإدارة. سيتم إشعارك فور الانتهاء من المراجعة.</p>
            </div>
        </div>
{{-- في قسم كروت الحالة، استبدل جزء under_edit بهذا الكود --}}
@elseif($statusVal=='under_edit')
    <div class="col-12">
        {{-- التحقق من وجود renew_number --}}
        @if($facility->renew_number)
            {{-- كرت تجديد يحتاج تعديل --}}
            <div class="status-card warning-card fade-in-up">
                <img src="{{asset('assets/images/under_edit.png')}}" width="120">
                <h4 class="text-warning">✏️ طلب تجديدك يحتاج تعديل</h4>
                <p class="text-muted mb-3">
                    تم مراجعة طلب تجديد اشتراك منشأتك الطبية وهناك بعض الملاحظات التي تحتاج للتعديل.
                    يرجى مراجعة الملاحظات وتحديث البيانات أو المستندات المطلوبة.
                </p>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="alert alert-info d-flex align-items-center mb-0">
                            <i class="fas fa-hashtag text-info me-2"></i>
                            <div>
                                <small class="text-muted d-block">رقم طلب التجديد</small>
                                <strong>#{{ $facility->renew_number }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-warning d-flex align-items-center mb-0">
                            <i class="fas fa-calendar-edit text-warning me-2"></i>
                            <div>
                                <small class="text-muted d-block">تاريخ آخر مراجعة</small>
                                <strong>{{ optional($facility->updated_at)->format('Y-m-d') ?? 'غير محدد' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>سبب التعديل:</strong> {{ $facility->edit_reason ?? 'غير محدد' }}
                </div>
                
                <div class="alert alert-primary">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>تنبيه:</strong> يرجى إجراء التعديلات المطلوبة في أقرب وقت ممكن لتجنب تأخير معالجة طلب التجديد.
                </div>
            </div>
        @else
            {{-- كرت تسجيل جديد يحتاج تعديل --}}
            <div class="status-card success-card fade-in-up">
                <img src="{{asset('assets/images/under_edit.png')}}" width="120">
                <h4 class="text-success">✏️ طلبك يحتاج تعديل</h4>
                <p class="text-muted mb-0">يرجى مراجعة البيانات وتعديلها حسب الملاحظات المطلوبة.</p>
                <div class="alert alert-info mt-3">
                    <strong>سبب التعديل:</strong> {{ $facility->edit_reason ?? 'غير محدد' }}
                </div>
            </div>
        @endif
    </div>
    @elseif($statusVal=='under_payment')
        <div class="col-12">
            <div class="status-card success-card fade-in-up">
                <img src="{{asset('assets/images/under_payment.png')}}" width="120">
                <h4 class="text-success m-0">💰 طلبك قيد السداد</h4>
                <p class="text-muted">تم قبول طلبك بنجاح! يرجى التوجه إلى الفرع الخاص بك لإتمام عملية السداد.</p>
                <div class="alert alert-warning mt-3">
                    <strong>المبلغ المطلوب:</strong> {{auth('doctor')->user()->invoices()->where('status','unpaid')->sum('amount')}} دينار ليبي
                </div>
            </div>
        </div>
    @elseif($statusVal=='expired')
        <div class="col-12">
            <div class="status-card expired-card fade-in-up">
                <img src="{{asset('assets/images/expired.png')}}" width="120" alt="expired">
                <h4 class="text-danger">⚠️ انتهت صلاحية اشتراك منشأتك الطبية</h4>
                <p class="mb-4">
                    انتهت صلاحية اشتراك منشأتك الطبية ولا يمكنك تقديم الخدمات الطبية حالياً. 
                    <strong>يجب عليك تجديد الاشتراك فوراً للمتابعة في العمل. سيتم فرض غرامات في حال لم تقم بالتجديد</strong>
                </p>
                
                <div class="d-flex flex-column flex-md-row gap-4 justify-content-center align-items-center mb-4">
                    <div class="alert alert-danger d-flex align-items-center" style="margin-bottom: 0; padding: 1rem 1.5rem;">
                        <i class="fas fa-calendar-times text-danger me-2" style="font-size: 1.2rem;"></i>
                        <span style="font-size: 1.1rem; font-weight: 600;">
                            تاريخ الانتهاء: {{ optional($facility->membership_expiration_date)->format('Y-m-d') ?? 'غير محدد' }}
                        </span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center">
                    <a href="{{ route('doctor.my-facility.renew') }}" class="btn btn-danger btn-lg pulse-animation" style="font-size: 1.25rem; padding: 1.25rem 3rem;">
                        <i class="fas fa-sync-alt me-2"></i>
                        تجديد الاشتراك الآن
                    </a>
                </div>
            </div>
        </div>
    @elseif($statusVal=='under_renew')
        <div class="col-12">
            <div class="status-card info-card fade-in-up">
                <img src="{{asset('assets/images/renew.png')}}" width="120" alt="renew">
                <h4 class="text-primary">🔄 طلب التجديد قيد المعالجة</h4>
                <p class="text-muted mb-3">
                    تم استلام طلب تجديد اشتراك منشأتك الطبية بنجاح وهو الآن قيد المعالجة من قبل الإدارة.
                </p>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="alert alert-info d-flex align-items-center mb-0">
                            <i class="fas fa-calendar-check text-info me-2"></i>
                            <div>
                                <small class="text-muted d-block">تاريخ تقديم الطلب</small>
                                <strong>{{ optional($facility->updated_at)->format('Y-m-d') ?? 'غير محدد' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-warning d-flex align-items-center mb-0">
                            <i class="fas fa-clock text-warning me-2"></i>
                            <div>
                                <small class="text-muted d-block">الوقت المتوقع للمعالجة</small>
                                <strong>3-5 أيام عمل</strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-primary">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>ملاحظة:</strong> سيتم إشعارك فور الانتهاء من معالجة طلب التجديد عبر البريد الإلكتروني والرسائل النصية.
                </div>
                
                @if($facility->renew_notes)
                    <div class="alert alert-secondary mt-3">
                        <strong>ملاحظات إضافية:</strong> {{ $facility->renew_notes }}
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

   </div>
</div>

@endif

{{-- ------------------------ بطاقة التابات ------------------------ --}}
@if($isPending)        {{-- نموذج موحّد للتعديل متاح فقط إذا الحالة تسمح --}}
<form action="{{ route('doctor.my-facility.update',$facility) }}"
      method="POST" enctype="multipart/form-data">
    @csrf  @method('PUT')
@endif

    {{-- ===== شريط التابات ===== --}}
    <ul class="nav nav-tabs" id="facilityTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{!request('visitors') ? "active" : ""}} " data-bs-toggle="tab"
                    data-bs-target="#tab-info" type="button">المعلومات الاساسية</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab"
                    data-bs-target="#tab-files" type="button">المستندات</button>
        </li>
        @if ($facility->membership_status->value == "active")
        <li class="nav-item" role="presentation">
            <button class="nav-link {{request('visitors') ? "active" : ""}} " data-bs-toggle="tab"
                    data-bs-target="#tab-visitors" type="button">
                <i class="fas fa-user-friends me-1"></i>الأطباء الزوار
            </button>
        </li>
        @endif
    </ul>

    <div class="tab-content" id="facilityTabsContent">

        {{-- ================= تبويب المعلومات ================= --}}
        <div class="tab-pane fade show {{!request('visitors') ? "active" : ""}}" id="tab-info">
            <div class="enhanced-card fade-in-up my-3">
                <div class="card-header d-flex justify-content-between">
                    <span><i class="fas fa-info-circle me-2"></i> بيانات المنشأة الطبية</span>
                    <span class="badge {{ $facility->membership_status->badgeClass() }}">
                        {{ $facility->membership_status->label() }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="info-list">

                        {{-- اسم المنشأة --}}
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-clinic-medical"></i> اسم المنشأة</span>
                            @if($isPending)
                                <input name="name" class="form-control"
                                       value="{{ old('name',$facility->name) }}">
                            @else
                                <span class="info-value">{{ $facility->name }}</span>
                            @endif
                        </div>

                        @if (!$isPending)
                            {{-- نوع المنشأة --}}
                            <div class="info-item">
                               <span class="info-label"><i class="fas fa-tags"></i> نوع المنشأة</span>
                               @if($isPending)
                                   <select name="type" class="form-select">
                                       <option value="private_clinic"  @selected($facility->type=='private_clinic')>
                                           عيادة خاصة
                                       </option>
                                       <option value="medical_services" @selected($facility->type!='private_clinic')>
                                           خدمات طبية
                                       </option>
                                   </select>
                               @else
                                   <span class="info-value">
                                       {{ $facility->type=='private_clinic'?'عيادة خاصة':'خدمات طبية' }}
                                   </span>
                               @endif
                           </div>
                        @endif

                        {{-- العنوان --}}
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-map-marker-alt"></i> العنوان</span>
                            @if($isPending)
                                <input name="address" class="form-control"
                                       value="{{ old('address',$facility->address) }}">
                            @else
                                <span class="info-value">{{ $facility->address }}</span>
                            @endif
                        </div>

                        {{-- الهاتف --}}
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-phone-alt"></i> رقم الهاتف</span>
                            @if($isPending)
                                <input name="phone_number" class="form-control"
                                       value="{{ old('phone_number',$facility->phone_number) }}">
                            @else
                                <span class="info-value">{{ $facility->phone_number }}</span>
                            @endif
                        </div>

                        {{-- تاريخ الانتهاء --}}
                        <div class="info-item">
                            <span class="info-label"><i class="fas fa-calendar-alt"></i> انتهاء الاشتراك</span>
                            <span class="info-value">
                                {{ optional($facility->membership_expiration_date)->format('Y-m-d') ?? 'غير محدد' }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ================= تبويب المستندات ================= --}}
        <div class="tab-pane fade" id="tab-files">
            <div class="enhanced-card fade-in-up my-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-file-alt me-2"></i> المستندات
                    </span>
                    {{-- إظهار رقم التجديد إذا كان موجود --}}
                    @if($facility->renew_number && $statusVal == 'under_edit')
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-sync-alt me-1"></i>
                            طلب تجديد #{{ $facility->renew_number }}
                        </span>
                    @endif
                </div>

                <div class="card-body">
                    @php
                        // فلترة الملفات حسب renew_number إذا كانت الحالة under_edit وموجود renew_number
                        $filteredFiles = $facility->files;
                        if ($facility->renew_number && $statusVal == 'under_edit') {
                            $filteredFiles = $facility->files->where('renew_number', $facility->renew_number);
                        }
                    @endphp
                    
                    @if($facility->renew_number && $statusVal == 'under_edit')
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>ملاحظة:</strong> يتم عرض المستندات الخاصة بطلب التجديد رقم #{{ $facility->renew_number }} فقط.
                        </div>
                    @endif

                    <div class="list-group">
                        @forelse($filteredFiles as $doc)
                            <div class="list-group-item d-flex flex-column flex-md-row
                                        justify-content-between align-items-start align-items-md-center">
                                <div class="flex-grow-1 mb-2 mb-md-0">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <strong>{{ $doc->fileType->name }}</strong>
                                        @if($doc->renew_number)
                                            <span class="badge bg-primary text-white small">
                                                <i class="fas fa-sync-alt me-1"></i>
                                                تجديد #{{ $doc->renew_number }}
                                            </span>
                                        @endif
                                    </div>
                                    <a href="{{ Storage::url($doc->file_path) }}"
                                    target="_blank" class="small">
                                        عرض الملف <i class="fa fa-eye"></i>
                                    </a>

                                    
                                    <div class="small text-muted mt-1">
                                        آخر تعديل: {{ $doc->updated_at->format('Y-m-d H:i') }}
                                    </div>
                                    @if($doc->renew_number)
                                        <div class="small text-primary mt-1">
                                            <i class="fas fa-clock me-1"></i>
                                            مرفوع لطلب التجديد
                                        </div>
                                    @endif
                                </div>

                                @if($isPending)
                                    <div class="d-flex align-items-center">
                                        <label class="btn btn-outline-secondary btn-sm mb-0 me-2">
                                            <i class="fa fa-upload"></i> تغيير
                                            <input type="file"
                                                name="files[{{ $doc->id }}]"
                                                hidden
                                                onchange="this.parentElement.nextElementSibling.textContent = this.files[0] ? this.files[0].name : ''">
                                        </label>
                                        <span class="small text-truncate" style="max-width:150px"></span>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted">
                                @if($facility->renew_number && $statusVal == 'under_edit')
                                    لا توجد مستندات مرفوعة لطلب التجديد #{{ $facility->renew_number }} حتى الآن.
                                @else
                                    لا توجد مستندات مرفوعة حتى الآن.
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade   {{request("visitors") ? "active show" : ""}} " id="tab-visitors">
            <div class="enhanced-card fade-in-up my-3">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <span>
                        <i class="fas fa-user-friends me-2"></i> الأطباء الزوار
                    </span>
                    <a href="{{ route('doctor.visitor-doctors.create') }}" class="btn-enhanced text-white text-light btn-primary btn-sm w-100 w-md-auto" style="color: #fff!important;">
                        <i class="fas fa-user-plus me-1"></i>إضافة طبيب زائر جديد
                    </a>
                </div>
        
                <div class="card-body">
        
                    @if($facility->visitor_doctors->count() > 0)
                        {{-- عرض للشاشات الكبيرة (جدول) --}}
                        <div class="d-none d-lg-block">
                            <div class="table-responsive">
                                <table class="visitors-table table mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>اسم الطبيب</th>
                                            <th>الصفة والتخصص</th>
                                            <th>الحالة</th>
                                            <th>فترة الزيارة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($facility->visitor_doctors as $visitor)
                                        @php
                                            $endDate = \Carbon\Carbon::parse($visitor->visit_to);
                                            $isActive = $endDate->isFuture();
                                            $daysLeft = $endDate->diffInDays(now(), false);
                                            
                                            // Check if visitor needs report upload
                                            $needsReport = $visitor->membership_status->value == 'expired' || $endDate->isPast();
                                            
                                            // Check if report already exists (assuming file_type_id 54 for visitor report)
                                            $hasReport = $visitor->files->where('file_type_id', 54)->count() > 0;
                                            
                                            $reportRequired = $needsReport && !$hasReport;
                                        @endphp
                                            <tr>
                                                <td><strong>{{ $loop->iteration }}</strong></td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $visitor->name }}</strong>
                                                    </div>
                                                    <small class="text-muted">{{ $visitor->email }}</small>
                                                </td>
                                                <td>
                                                    <div>{{ $visitor->doctor_rank->name }}
                                                    </div>
                                                    <small class="text-muted">{{ $visitor->specialty1?->name }}
                                                        @if ($visitor->specialty_2)
                                                            - {{ $visitor->specialty_2 }}
                                                        @endif
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge {{$visitor->membership_status->badgeClass()}} " >
                                                        {{$visitor->membership_status->label()}}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $endDate = \Carbon\Carbon::parse($visitor->visit_to);
                                                        $isActive = $endDate->isFuture();
                                                        $daysLeft = $endDate->diffInDays(now(), false);
                                                    @endphp
                                                    <div class="small">
                                                        <div><strong>من:</strong> {{ date('Y-m-d', strtotime($visitor->visit_from)) }}</div>
                                                        <div><strong>إلى:</strong> {{ date('Y-m-d', strtotime($visitor->visit_to)) }}</div>
                                                        @if($isActive)
                                                            <div class="text-success">{{ abs($daysLeft) }} يوم متبقي</div>
                                                        @else
                                                            <div class="text-danger">انتهى منذ {{ $daysLeft }} يوم</div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="action-btn-group">
                                                        <a href="{{ route('doctor.visitor-doctors.show', $visitor->id) }}" 
                                                           class="action-btn view" title="عرض">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        
                                                    
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
        
                        {{-- عرض للهواتف والأجهزة اللوحية (كروت) --}}
                        <div class="d-lg-none">
                            <div class="visitor-cards-container">
                                @foreach($facility->visitor_doctors as $visitor)
                                    @php
                                        $endDate = \Carbon\Carbon::parse($visitor->visit_end_date);
                                        $isActive = $endDate->isFuture();
                                        $daysLeft = $endDate->diffInDays(now(), false);
                                    @endphp
                                    <div class="visitor-card {{ $isActive ? 'active' : 'expired' }}" data-visitor-id="{{ $visitor->id }}">
                                        {{-- رأس الكرت --}}
                                        <div class="visitor-card-header">
                                            <div class="visitor-info">
                                                <h5 class="visitor-name">{{ $visitor->name }}</h5>
                                                <p class="visitor-email">{{ $visitor->email }}</p>
                                            </div>
                                            <div class="visitor-status">
                                                <span class="badge {{$visitor->membership_status->badgeClass()}}">{{$visitor->membership_status->label()}}</span>
                                            </div>
                                        </div>
        
                                        {{-- محتوى الكرت --}}
                                        <div class="visitor-card-body">
                                            <div class="visitor-details">
                                                <div class="detail-item">
                                                    <span class="detail-label">
                                                        <i class="fas fa-user-tie"></i>
                                                        الصفة
                                                    </span>
                                                    <span class="detail-value">{{ $visitor->doctor_rank->name ?? 'غير محدد' }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">
                                                        <i class="fas fa-stethoscope"></i>
                                                        التخصص
                                                    </span>
                                                    <span class="detail-value">{{ $visitor->specialty1->name }} 
                                                        @if ($visitor->specialty_2)
                                                            - {{ $visitor->specialty_2 }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">
                                                        <i class="fas fa-calendar-alt"></i>
                                                        من تاريخ
                                                    </span>
                                                    <span class="detail-value">{{ date('Y-m-d', strtotime($visitor->visit_from)) }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">
                                                        <i class="fas fa-calendar-check"></i>
                                                        إلى تاريخ
                                                    </span>
                                                    <span class="detail-value">{{ date('Y-m-d', strtotime($visitor->visit_to)) }}</span>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">
                                                        <i class="fas fa-hourglass-half"></i>
                                                        المدة المتبقية
                                                    </span>
                                                    <span class="detail-value {{ $isActive ? 'text-success' : 'text-danger' }}">
                                                        @if($isActive)
                                                            {{ abs($daysLeft) }} يوم متبقي
                                                        @else
                                                            انتهى منذ {{ $daysLeft }} يوم
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
        
                                        {{-- أسفل الكرت (الإجراءات) --}}
                                        <div class="visitor-card-footer">
                                            <div class="visitor-actions">
                                                <a href="{{ route('doctor.visitor-doctors.show', $visitor->id) }}" 
                                                   class="action-btn-mobile primary">
                                                    <i class="fas fa-eye"></i>
                                                    <span>عرض</span>
                                                </a>
                                      
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-user-friends empty-state-icon"></i>
                            <h4>لا يوجد أطباء زوار</h4>
                            <p>لم يتم تسجيل أي أطباء زوار في هذه المنشأة حتى الآن.</p>
                            <a href="{{ route('doctor.visitor-doctors.create') }}" class="btn-enhanced btn-primary">
                                <i class="fas fa-user-plus me-2"></i>إضافة أول طبيب زائر
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div> {{-- /tab-content --}}

    @if($isPending)
        <div class="text-center my-4">
            <button class="btn-enhanced btn-success px-5">
                <i class="fas fa-save"></i> حفظ جميع التعديلات
            </button>
        </div>
    @endif

@if($isPending)
</form>
@endif
@endisset
</div>
<!-- Upload Report Modal -->
<div class="modal fade" id="uploadReportModal" tabindex="-1" aria-labelledby="uploadReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-light" id="uploadReportModalLabel">
                    <i class="fas fa-file-upload me-2"></i>
                    رفع تقرير زيارة الطبيب
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="uploadReportForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Doctor Info -->
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="fas fa-user-md me-3 fs-4"></i>
                        <div>
                            <h6 class="mb-1">معلومات الطبيب الزائر</h6>
                            <p class="mb-0" id="doctorInfo">اسم الطبيب</p>
                        </div>
                    </div>

                    <!-- Upload Area -->
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-content text-center p-4">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                            <h5>اسحب وأفلت الملف هنا أو اضغط للاختيار</h5>
                            <p class="text-muted">صورة من التقرير بعد زيارة الطبيب الزائر</p>
                            <p class="small text-muted">الملفات المسموحة: PDF, JPG, PNG (الحجم الأقصى: 2MB)</p>
                            <input type="file" id="reportFile" name="report_file" accept=".pdf,.jpg,.jpeg,.png" hidden>
                            <input type="hidden" id="visitorId" name="visitor_id">
                        </div>
                    </div>

                    <!-- File Preview -->
                    <div id="filePreview" class="mt-3" style="display: none;">
                        <div class="selected-file p-3 border rounded bg-light">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file fa-2x text-success me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1" id="fileName">اسم الملف</h6>
                                    <small class="text-muted" id="fileSize">حجم الملف</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div id="uploadProgress" class="mt-3" style="display: none;">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <small class="text-muted mt-1">جاري رفع الملف...</small>
                    </div>

                    <!-- Error Messages -->
                    <div id="uploadErrors" class="alert alert-danger mt-3" style="display: none;"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
                        <i class="fas fa-upload me-2"></i>رفع التقرير
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
// Global variables
let currentVisitorId = null;

// Open upload modal
function openUploadModal(visitorId, visitorName, visitFrom, visitTo) {
    currentVisitorId = visitorId;
    
    // Update modal content
    document.getElementById('visitorId').value = visitorId;
    document.getElementById('doctorInfo').innerHTML = `
        <strong>${visitorName}</strong><br>
        <small class="text-muted">فترة الزيارة: ${visitFrom} إلى ${visitTo}</small>
    `;
    
    // Reset form
    resetUploadForm();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('uploadReportModal'));
    modal.show();
}

// Reset upload form
function resetUploadForm() {
    document.getElementById('uploadReportForm').reset();
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('uploadProgress').style.display = 'none';
    document.getElementById('uploadErrors').style.display = 'none';
    document.getElementById('uploadBtn').disabled = true;
    
    const uploadArea = document.getElementById('uploadArea');
    uploadArea.classList.remove('error', 'success', 'dragover');
}

// File selection handling
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('reportFile');
    const uploadForm = document.getElementById('uploadReportForm');
    
    // Click to select file
    uploadArea.addEventListener('click', () => fileInput.click());
    
    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelection();
        }
    });
    
    // File input change
    fileInput.addEventListener('change', handleFileSelection);
    
    // Form submission
    uploadForm.addEventListener('submit', handleFormSubmission);
});

// Handle file selection
function handleFileSelection() {
    const fileInput = document.getElementById('reportFile');
    const file = fileInput.files[0];
    
    if (!file) return;
    
    // Validate file
    const validationResult = validateFile(file);
    if (!validationResult.valid) {
        showError(validationResult.message);
        return;
    }
    
    // Show file preview
    showFilePreview(file);
    document.getElementById('uploadBtn').disabled = false;
    document.getElementById('uploadArea').classList.add('success');
}

// Validate file
function validateFile(file) {
    const maxSize = 2 * 1024 * 1024; // 2MB
    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
    
    if (file.size > maxSize) {
        return {
            valid: false,
            message: 'حجم الملف يجب أن يكون أقل من 2 ميجابايت'
        };
    }
    
    if (!allowedTypes.includes(file.type)) {
        return {
            valid: false,
            message: 'نوع الملف غير مدعوم. يرجى اختيار ملف PDF أو صورة (JPG, PNG)'
        };
    }
    
    return { valid: true };
}

// Show file preview
function showFilePreview(file) {
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = formatFileSize(file.size);
    document.getElementById('filePreview').style.display = 'block';
    document.getElementById('uploadErrors').style.display = 'none';
}

// Remove file
function removeFile() {
    document.getElementById('reportFile').value = '';
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('uploadBtn').disabled = true;
    document.getElementById('uploadArea').classList.remove('success', 'error');
}

// Handle form submission
async function handleFormSubmission(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const fileInput = document.getElementById('reportFile');
    const visitorId = document.getElementById('visitorId').value;
    
    if (!fileInput.files[0]) {
        showError('يرجى اختيار ملف للرفع');
        return;
    }
    
    formData.append('report_file', fileInput.files[0]);
    formData.append('visitor_id', visitorId);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    try {
        // Show progress
        showProgress();
        
        const response = await fetch(`/doctor/visitor-doctors/${visitorId}/upload-report`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            // Success
            showSuccess('تم رفع التقرير بنجاح');
            setTimeout(() => {
                location.reload(); // Reload to update the interface
            }, 1500);
        } else {
            // Error
            showError(result.message || 'حدث خطأ أثناء رفع الملف');
        }
    } catch (error) {
        showError('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى');
    } finally {
        hideProgress();
    }
}

// Show progress
function showProgress() {
    document.getElementById('uploadProgress').style.display = 'block';
    document.getElementById('uploadBtn').disabled = true;
    
    // Simulate progress
    let progress = 0;
    const progressBar = document.querySelector('#uploadProgress .progress-bar');
    const interval = setInterval(() => {
        progress += 10;
        progressBar.style.width = progress + '%';
        
        if (progress >= 90) {
            clearInterval(interval);
        }
    }, 100);
}

// Hide progress
function hideProgress() {
    document.getElementById('uploadProgress').style.display = 'none';
    document.querySelector('#uploadProgress .progress-bar').style.width = '0%';
    document.getElementById('uploadBtn').disabled = false;
}

// Show error
function showError(message) {
    const errorDiv = document.getElementById('uploadErrors');
    errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
    errorDiv.style.display = 'block';
    document.getElementById('uploadArea').classList.add('error');
}

// Show success
function showSuccess(message) {
    const errorDiv = document.getElementById('uploadErrors');
    errorDiv.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}`;
    errorDiv.className = 'alert alert-success mt-3';
    errorDiv.style.display = 'block';
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>
@endsection