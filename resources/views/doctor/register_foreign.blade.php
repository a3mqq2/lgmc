<!doctype html>
<html lang="ar" dir="rtl" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
<head>
    <meta charset="utf-8" />
    <title>دخول الأطباء | بوابة النظام</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="النقابة العامة للاطباء - ليبيا - بوابة النظام" name="description" />
    <meta content="النقابة العامة للاطباء - ليبيا" name="author" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.5/js/standalone/selectize.min.js"></script>

    <script>
        $(".selectize").selectize({dir:"rtl",placeholder:"اختر"});
    
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.step');
            const progressBar = document.getElementById('progressBar');
            const nextBtns = document.querySelectorAll('.nextBtn');
            const prevBtns = document.querySelectorAll('.prevBtn');
            const branchCards = document.querySelectorAll('.branch-card');
            const selectedBranchInput = document.getElementById('selected_branch');
            const branchNextBtn = document.getElementById('branchNextBtn');
            let current = 0;
    
            // Branch selection functionality
            branchCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remove selected class from all cards
                    branchCards.forEach(c => c.classList.remove('selected'));
                    
                    // Add selected class to clicked card
                    this.classList.add('selected');
                    
                    // Update hidden input
                    const branchId = this.dataset.branchId;
                    selectedBranchInput.value = branchId;
                    
                    // Enable next button
                    branchNextBtn.disabled = false;
                    
                    // Add animation effect
                    this.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 200);
                });
            });
            
            const showStep = i => {
                steps.forEach((s, idx) => s.style.display = idx === i ? 'block' : 'none');
                progressBar.style.width = ((i + 1) / steps.length * 100) + '%';
            };
    
            const validateStep = () => {
                const currentStep = steps[current];
                const inputs = currentStep.querySelectorAll('input, select, textarea');
                let valid = true;
    
                inputs.forEach(input => {
                    input.classList.remove('is-invalid');
                    if (input.hasAttribute('required') && !input.value.trim()) {
                        input.classList.add('is-invalid');
                        input.reportValidity();
                        valid = false;
                    }
                });
    
                return valid;
            };
    
            nextBtns.forEach(btn => btn.addEventListener('click', () => {
                if (validateStep() && current < steps.length - 1) {
                    current++;
                    showStep(current);
                    
                    // Scroll to top of form
                    document.querySelector('.info-section').scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }));
    
            prevBtns.forEach(btn => btn.addEventListener('click', () => {
                if (current > 0) {
                    current--;
                    showStep(current);
                    
                    // Scroll to top of form
                    document.querySelector('.info-section').scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }));
    
            // Form submission validation
            document.getElementById('multiStepForm').addEventListener('submit', function(e) {
                if (!validateStep()) {
                    e.preventDefault();
                    alert('يرجى التأكد من ملء جميع الحقول المطلوبة');
                }
            });
            
            // Password confirmation validation
            const passwordInput = document.querySelector('input[name="password"]');
            const confirmPasswordInput = document.querySelector('input[name="password_confirmation"]');
            
            if (passwordInput && confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', function() {
                    if (this.value !== passwordInput.value) {
                        this.classList.add('is-invalid');
                        this.setCustomValidity('كلمة المرور غير متطابقة');
                    } else {
                        this.classList.remove('is-invalid');
                        this.setCustomValidity('');
                    }
                });
            }
            
            // Enhanced form field animations
            const formInputs = document.querySelectorAll('input, select, textarea');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                    this.parentElement.style.transition = 'transform 0.2s ease';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = '';
                });
            });
    
            showStep(current);
        });
    </script>

    <script src="assets/js/layout.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.5/css/selectize.bootstrap5.min.css" integrity="sha512-w4sRMMxzHUVAyYk5ozDG+OAyOJqWAA+9sySOBWxiltj63A8co6YMESLeucKwQ5Sv7G4wycDPOmlHxkOhPW7LRg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        
        .is-invalid {
            border: 2px solid #dc3545 !important;
            box-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
        }
        
        .step{display:none;}
        .info-section{border:2px dashed #9d1414;padding:20px;margin:20px 0}
        .progress{height:8px}
        .progress-bar{transition:width .4s ease}
        
        /* prettier alert */
        .custom-alert{
            background:linear-gradient(135deg,#f0f9ff 0%,#e0f4ff 100%);
            border:1px solid #b6e0fe!important;
            color:#05668d;
            border-radius:0.75rem;
            box-shadow:0 4px 12px rgba(0,0,0,.05);
            padding:1rem 1.25rem;
            font-weight:500;
        }
        
        /* Branch Cards Styling */
        .branch-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .branch-card:hover {
            border-color: #b91c1c;
            box-shadow: 0 4px 12px rgba(185, 28, 28, 0.15);
            transform: translateY(-2px);
        }
        
        .branch-card.selected {
            border-color: #b91c1c;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            box-shadow: 0 6px 20px rgba(185, 28, 28, 0.2);
        }
        
        .branch-card .branch-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #b91c1c, #dc2626);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }
        
        .branch-card.selected .branch-icon {
            background: linear-gradient(135deg, #059669, #10b981);
        }
        
        .branch-card h6 {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .branch-card p {
            color: #6b7280;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .branch-card.selected h6 {
            color: #b91c1c;
        }
        
        .branch-card.selected p {
            color: #991b1b;
        }
        
        /* Specialty warning alert */
        .specialty-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .specialty-warning .icon {
            width: 24px;
            height: 24px;
            background: #f59e0b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.875rem;
            margin-left: 0.75rem;
        }
        
        /* Enhanced form sections */
        .step-header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-radius: 12px;
        }
        
        .step-header h4 {
            color: #b91c1c;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .step-header p {
            color: #6b7280;
            margin: 0;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            background: #b91c1c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin: 0 auto 1rem;
        }
    </style>
</head>
<body>
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-12">
                                    <div class="logo d-flex justify-content-center">
                                        <img class="logo-dark" src="{{ asset('/assets/images/lgmc-dark.png?v=44') }}" width="300" alt="">
                                    </div>

                                    <div class="container p-3">
                                        @include('layouts.messages')
                                    </div>

                                    <div class="p-lg-5 p-4">
                                        <div style="text-align: right;">
                                            <h5 class="text-primary">طلب عضوية جديدة</h5>
                                            <p class="text-muted">أدخل بياناتك خطوة بخطوة لإكمال الطلب.</p>
                                        </div>

                                        <div class="progress mb-4">
                                            <div class="progress-bar bg-primary" id="progressBar" role="progressbar"></div>
                                        </div>

                                        <form id="multiStepForm" method="POST" dir="rtl" enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')

                                            <!-- الخطوة 1: اختيار الفرع -->
                                            <div class="info-section step">
                                                <div class="step-header">
                                                    <div class="step-number">1</div>
                                                    <h4>اختيار الفرع</h4>
                                                    <p>يرجى اختيار الفرع الأقرب لمكان إقامتك أو عملك</p>
                                                </div>
                                                
                                                <div class="row">
                                                    @foreach (App\Models\Branch::all() as $branch)
                                                        <div class="col-md-4 col-sm-6">
                                                            <div class="branch-card" data-branch-id="{{ $branch->id }}">
                                                                <div class="branch-icon">
                                                                    <i class="fas fa-map-marker-alt"></i>
                                                                </div>
                                                                <h6>{{ $branch->name }} 
                                                                
                                                                    @if ($branch->id == 1)
                                                                        (النقابة العامة)
                                                                    @endif

                                                                </h6>
                                                                <p>اختر هذا الفرع للمتابعة</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                
                                                <input type="hidden" name="branch_id" id="selected_branch" required>
                                                
                                                <div class="d-flex justify-content-end mt-4">
                                                    <button type="button" class="btn btn-primary nextBtn" disabled id="branchNextBtn">التالي</button>
                                                </div>
                                            </div>

                                            <!-- الخطوة 2: البيانات الشخصية -->
                                            <div class="info-section step">
                                                <div class="step-header">
                                                    <div class="step-number">2</div>
                                                    <h4>البيانات الشخصية</h4>
                                                    <p>أدخل بياناتك الشخصية كما هي موجودة في الوثائق الرسمية</p>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>الاسم بالكامل</label>
                                                        <input type="text" required name="name" value="{{old('name')}}" class="form-control">
                                                        <input type="hidden" name="type" value="{{request('type')}}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>الاسم بالكامل باللغة الانجليزية</label>
                                                        <input type="text" required name="name_en" value="{{old('name_en')}}" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>رقم جواز السفر</label>
                                                        <input type="text" required name="passport_number" value="{{old('passport_number')}}" class="form-control">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>تاريخ انتهاء صلاحية جواز السفر</label>
                                                        <input type="date" required name="passport_expiration" value="{{old('passport_expiration')}}" class="form-control">
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label>الجنسية</label>
                                                        <select name="country_id" required id="country_id" class="form-control selectize" @if(request('type')=="libyan"||request('type')=="palestinian") disabled @endif>
                                                            <option value="">حدد دولة</option>
                                                            @foreach ($countries as $country)
                                                                @if ($country->id!=1 && $country->id!=2)
                                                                    <option value="{{$country->id}}" {{old('country_id')==$country->id?"selected":""}}>{{$country->nationality_name_ar}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @if(request('type')=="palestinian")
                                                            <input type="hidden" name="country_id" value="2">
                                                        @endif
                                                        @if(request('type')=="libyan")
                                                            <input type="hidden" name="country_id" value="1">
                                                        @endif
                                                    </div>

                                                    <div class="col-md-12 mt-3">
                                                        <div class="custom-alert d-flex align-items-center mb-4" role="alert">
                                                            <i class="fas fa-passport fs-4 me-2 flex-shrink-0"></i>
                                                            <span class="flex-grow-1">يجب عليك التأكد من أن كل بياناتك المدخلة مطابقة لما هو مذكور في جواز سفرك.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-4">
                                                    <button type="button" class="btn btn-secondary prevBtn">السابق</button>
                                                    <button type="button" class="btn btn-primary nextBtn">التالي</button>
                                                </div>
                                            </div>

                                            <!-- الخطوة 3: بيانات الاتصال -->
                                            <div class="info-section step">
                                                <div class="step-header">
                                                    <div class="step-number">3</div>
                                                    <h4>بيانات الاتصال</h4>
                                                    <p>أدخل بيانات الاتصال الخاصة بك وكلمة المرور للحساب</p>
                                                </div>
                                                
                                                <div class="row mb-4">
                                                    <div class="col-md-12">
                                                        <label>رقم الهاتف @if(request('type')=="visitor") - الشركة @endif</label>
                                                        <input type="phone" required name="phone" maxlength="10" value="{{old('phone')}}" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>رقم الواتساب</label>
                                                        <input type="phone" name="phone_2" maxlength="10" value="{{old('phone_2')}}" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>البريد الإلكتروني</label>
                                                        <input type="email" name="email" value="{{old('email')}}" required class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>كلمة المرور</label>
                                                        <input type="password" name="password" required class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>تأكيد كلمة المرور</label>
                                                        <input type="password" name="password_confirmation" required class="form-control">
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-4">
                                                    <button type="button" class="btn btn-secondary prevBtn">السابق</button>
                                                    <button type="button" class="btn btn-primary nextBtn">التالي</button>
                                                </div>
                                            </div>

                                            <!-- الخطوة 4: بيانات البكالوريوس -->
                                            <div class="info-section step">
                                                <div class="step-header">
                                                    <div class="step-number">4</div>
                                                    <h4>بيانات البكالوريوس</h4>
                                                    <p>أدخل معلومات شهادة البكالوريوس في الطب</p>
                                                </div>
                                                
                                                <div class="row mt-2 mb-4">
                                                    <div class="col-md-6">
                                                        <label>جهة التخرج</label>
                                                        <select name="hand_graduation_id" class="form-control selectize">
                                                            <option value="">حدد الجهة</option>
                                                            @foreach ($universities as $university)
                                                                <option value="{{$university->id}}" {{old('hand_graduation_id')==$university->id?"selected":""}}>{{$university->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>تاريخ التخرج</label>
                                                        <input type="date" name="graduation_date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-4">
                                                    <button type="button" class="btn btn-secondary prevBtn">السابق</button>
                                                    <button type="button" class="btn btn-primary nextBtn">التالي</button>
                                                </div>
                                            </div>

                                            <!-- الخطوة 5: بيانات الامتياز والعمل الحالي -->
                                            <div class="info-section step">
                                                <div class="step-header">
                                                    <div class="step-number">5</div>
                                                    <h4>بيانات الامتياز والعمل الحالي</h4>
                                                    <p>أدخل معلومات سنة الامتياز والوضع المهني الحالي</p>
                                                </div>
                                                
                                                <div class="row mt-2 mb-4">
                                                    <div class="col-md-6">
                                                        <label>جهة الحصول على الامتياز</label>
                                                        <select name="qualification_university_id" class="form-control selectize">
                                                            <option value="">حدد الجهة</option>
                                                            @foreach ($universities as $university)
                                                                <option value="{{$university->id}}" {{old('qualification_university_id')==$university->id?"selected":""}}>{{$university->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>تاريخ الحصول عليها</label>
                                                        <input type="date" name="internership_complete" class="form-control">
                                                    </div>
                                                          
                                                    <div class="col-md-12 mt-3">
                                                        <label>الصفة الوظيفية</label>
                                                        <select name="doctor_rank_id" required id="doctor_rank_id" class="form-control selectize">
                                                            <option value="">حدد الصفة</option>
                                                            @foreach ($doctor_ranks as $doctor_rank)
                                                              <option value="{{$doctor_rank->id}}" {{old('doctor_rank_id')==$doctor_rank->id?"selected":""}}>{{$doctor_rank->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-md-12 mt-3">
                                                        <label class="form-label">حدد تخصص (ان وجد)</label>
                                                        
                                                        <!-- تحذير متطلبات التخصص -->
                                                        <div class="specialty-warning d-flex align-items-start mb-3">
                                                            <div class="icon">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                            </div>
                                                            <div>
                                                                <strong class="text-amber-800">متطلبات اختيار التخصص:</strong>
                                                                <p class="text-amber-700 mb-2 mt-1">
                                                                    يجب أن تكون لديك خبرة عملية في التخصص المختار لا تقل عن <strong>أربع سنوات</strong> من تاريخ إنهاء سنة الامتياز.
                                                                </p>
                                                                <small class="text-amber-600">
                                                                    <i class="fas fa-info-circle me-1"></i>
                                                                    إذا لم تستوف هذا الشرط، يرجى ترك هذا الحقل فارغاً واختيار "ممارس عام" كصفة.
                                                                </small>
                                                            </div>
                                                        </div>
                                                        
                                                        <select name="specialty_1_id" class="form-control">
                                                            <option value="">اختر تخصص (اختياري)</option>
                                                            @foreach ($specialties as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mt-4">
                                                    <button type="button" class="btn btn-secondary prevBtn">السابق</button>
                                                    <button type="submit" class="btn btn-success">إرسال الطلب</button>
                                                </div>
                                            </div>
                                        </form>

                                        {!! NoCaptcha::renderJs() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <div class="text-center">
                    <p class="mb-0">&copy; <script>document.write(new Date().getFullYear())</script> النقابة العامة للاطباء - ليبيا. جميع الحقوق محفوظة.</p>
                </div>
            </div>
        </footer>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.5/js/standalone/selectize.min.js"></script>

    <script>
        $(".selectize").selectize({dir:"rtl",placeholder:"اختر"});
    
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.step');
            const progressBar = document.getElementById('progressBar');
            const nextBtns = document.querySelectorAll('.nextBtn');
            const prevBtns = document.querySelectorAll('.prevBtn');
            const branchCards = document.querySelectorAll('.branch-card');
            const selectedBranchInput = document.getElementById('selected_branch');
            const branchNextBtn = document.getElementById('branchNextBtn');
            let current = 0;
    
            // Branch selection functionality
            branchCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remove selected class from all cards
                    branchCards.forEach(c => c.classList.remove('selected'));
                    
                    // Add selected class to clicked card
                    this.classList.add('selected');
                    
                    // Update hidden input
                    const branchId = this.dataset.branchId;
                    selectedBranchInput.value = branchId;
                    
                    // Enable next button
                    branchNextBtn.disabled = false;
                    
                    // Add animation effect
                    this.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 200);
                });
            });
            
            const showStep = i => {
                steps.forEach((s, idx) => s.style.display = idx === i ? 'block' : 'none');
                progressBar.style.width = ((i + 1) / steps.length * 100) + '%';
            };
    
            const validateStep = () => {
                const currentStep = steps[current];
                const inputs = currentStep.querySelectorAll('input, select, textarea');
                let valid = true;
    
                inputs.forEach(input => {
                    input.classList.remove('is-invalid');
                    if (input.hasAttribute('required') && !input.value.trim()) {
                        input.classList.add('is-invalid');
                        input.reportValidity();
                        valid = false;
                    }
                });
    
                return valid;
            };
    
            nextBtns.forEach(btn => btn.addEventListener('click', () => {
                if (validateStep() && current < steps.length - 1) {
                    current++;
                    showStep(current);
                    
                    // Scroll to top of form
                    document.querySelector('.info-section').scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }));
    
            prevBtns.forEach(btn => btn.addEventListener('click', () => {
                if (current > 0) {
                    current--;
                    showStep(current);
                    
                    // Scroll to top of form
                    document.querySelector('.info-section').scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }));
    
            // Form submission validation
            document.getElementById('multiStepForm').addEventListener('submit', function(e) {
                if (!validateStep()) {
                    e.preventDefault();
                    alert('يرجى التأكد من ملء جميع الحقول المطلوبة');
                }
            });
            
            // Password confirmation validation
            const passwordInput = document.querySelector('input[name="password"]');
            const confirmPasswordInput = document.querySelector('input[name="password_confirmation"]');
            
            if (passwordInput && confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', function() {
                    if (this.value !== passwordInput.value) {
                        this.classList.add('is-invalid');
                        this.setCustomValidity('كلمة المرور غير متطابقة');
                    } else {
                        this.classList.remove('is-invalid');
                        this.setCustomValidity('');
                    }
                });
            }
            
            showStep(current);
        });
    </script>
    
</body>
</html>