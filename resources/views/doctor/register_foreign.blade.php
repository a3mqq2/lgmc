<!doctype html>
<html lang="ar" dir="rtl" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
<head>
    <meta charset="utf-8" />
    <title>دخول الأطباء | بوابة النظام</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="النقابة العامة للاطباء - ليبيا - بوابة النظام" name="description" />
    <meta content="النقابة العامة للاطباء - ليبيا" name="author" />
    <link rel="shortcut icon" href="assets/images/logo-primary.png">
    <script src="assets/js/layout.js"></script>
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
    </style>


    <style>
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

                                            <!-- الخطوة 1 -->
                                            <div class="info-section step">
                                                <div class="row">
                                                    <div class="col-md-12 text-primary">
                                                        <h4 class="mb-3">البيانات الشخصية</h4>
                                                    </div>
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
                                                <div class="d-flex justify-content-end mt-4">
                                                    <button type="button" class="btn btn-primary nextBtn">التالي</button>
                                                </div>
                                            </div>

                                            <!-- الخطوة 2 -->
                                            <div class="info-section step">
                                                <div class="row mb-4">
                                                    <div class="col-md-12 text-primary">
                                                        <h4 class="mb-3">بيانات الاتصال</h4>
                                                    </div>
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

                                            <!-- الخطوة 3 -->
                                            <div class="info-section step">
                                                <div class="row mt-2 mb-4">
                                                    <div class="col-md-12 text-primary">
                                                        <h4 class="mb-3">بيانات البكالوريوس</h4>
                                                    </div>
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

                                            <!-- الخطوة 4 -->
                                            <div class="info-section step">
                                                <div class="row mt-2 mb-4">
                                                    <div class="col-md-12 text-primary">
                                                        <h4 class="mb-3">بيانات الامتياز</h4>
                                                    </div>
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
                                                </div>
                                           


                                                <div class="d-flex justify-content-between mt-4">
                                                    <button type="button" class="btn btn-secondary prevBtn">السابق</button>
                                                    <button type="submit" class="btn btn-success">إرسال الطلب</button>
                                                </div>

                                            </div>

                                            <!-- الخطوة 5 -->
                                            {{-- <div class="info-section step">
                                                <div class="row">
                                                    <div class="col-md-12 text-primary">
                                                        <h4 class="mb-3">بيانات العمل الحالي</h4>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>الصفة</label>
                                                        <select name="doctor_rank_id" required id="doctor_rank_id" class="form-control selectize">
                                                            <option value="">حدد الصفة</option>
                                                            @foreach ($doctor_ranks as $doctor_rank)
                                                                @if(request('type')=="visitor" && ($doctor_rank->id!=1 && $doctor_rank->id!=2))
                                                                    <option value="{{$doctor_rank->id}}" {{old('doctor_rank_id')==$doctor_rank->id?"selected":""}}>{{$doctor_rank->name}}</option>
                                                                @elseif(request('type')!="visitor")
                                                                    <option value="{{$doctor_rank->id}}" {{old('doctor_rank_id')==$doctor_rank->id?"selected":""}}>{{$doctor_rank->name}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mt-3">
                                                        <label>حدد فرع (الأقرب)</label>
                                                        <select name="branch_id" class="form-control selectize" required>
                                                            <option value="">حدد فرع</option>
                                                            @foreach (App\Models\Branch::all() as $branch)
                                                                <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                               
                                                </div>
                                            </div> --}}
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
            let current = 0;
    
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
                }
            }));
    
            prevBtns.forEach(btn => btn.addEventListener('click', () => {
                if (current > 0) {
                    current--;
                    showStep(current);
                }
            }));
    
            showStep(current);
        });
    </script>
    
    
</body>
</html>
