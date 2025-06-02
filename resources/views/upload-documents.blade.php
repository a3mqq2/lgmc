<!doctype html>
<html lang="ar" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
<head>
    <meta charset="utf-8" />
    <title>رفع المستندات | بوابة النظام</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="النقابة العامة للاطباء - ليبيا - بوابة النظام" name="description" />
    <meta content="النقابة العامة للاطباء - ليبيا" name="author" />
    <!-- App favicon -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="shortcut icon" href="assets/images/logo-primary.png">
    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css--> 
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css--> 
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet" />
    
    <style>
        /* تحسين مظهر FilePond */
        .filepond--root {
            direction: ltr;
            margin-bottom: 1rem;
        }
        
        .filepond--drop-label {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            transition: all 0.3s ease;
            padding: 2rem 1rem;
        }
        
        .filepond--drop-label:hover {
            border-color: #b91c1c;
            background: #fef2f2;
        }
        
        .filepond--panel-root {
            border-radius: 12px;
        }
        
        /* تنبيهات وإرشادات */
        .upload-guidelines {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            border: 2px solid #f59e0b;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .upload-guidelines h6 {
            color: #92400e;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .upload-guidelines .icon {
            width: 32px;
            height: 32px;
            background: #f59e0b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .guideline-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .guideline-list li {
            display: flex;
            align-items: start;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            color: #92400e;
        }
        
        .guideline-list li i {
            color: #f59e0b;
            margin-top: 0.2rem;
            font-size: 0.875rem;
        }
        
        /* تحسين مظهر الحقول */
        .file-upload-container {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .file-upload-container:hover {
            border-color: #b91c1c;
            box-shadow: 0 4px 12px rgba(185, 28, 28, 0.1);
        }
        
        .file-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        
        .file-label .icon {
            width: 24px;
            height: 24px;
            background: #b91c1c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
        }
        
        .file-tips {
            background: #f0f9ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #1e40af;
        }
        
        .file-tips i {
            color: #3b82f6;
            margin-left: 0.5rem;
        }
        
        /* زر الإنهاء */
        .finish-section {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 2px solid #b91c1c;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
        }
        
        .finish-section h6 {
            color: #b91c1c;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .progress-info {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .progress-info .stat {
            text-align: center;
        }
        
        .progress-info .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #b91c1c;
        }
        
        .progress-info .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        #finish-upload:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        /* رسائل الحالة */
        .upload-status {
            margin-top: 1rem;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            display: none;
        }
        
        .upload-status.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            display: block;
        }
        
        .upload-status.warning {
            background: #fffbeb;
            border: 1px solid #fed7aa;
            color: #92400e;
            display: block;
        }
        
        .upload-status.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            display: block;
        }
        
        /* استجابة للشاشات الصغيرة */
        @media (max-width: 768px) {
            .upload-guidelines {
                padding: 1rem;
            }
            
            .file-upload-container {
                padding: 1rem;
            }
            
            .progress-info {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden" dir="rtl">
                            <div class="row g-0">
                                <div class="col-lg-12">
                                    <div class="p-lg-5 p-4">
                                        <div class="text-center mb-4">
                                            <h5 class="text-primary">رفع المستندات المطلوبة</h5>
                                            <p class="text-muted">{{ $doctor->name }} (ID: {{ $doctor->id }})</p>
                                        </div>

                                        <div class="mb-4">
                                            @include('layouts.messages')
                                        </div>

                                        <!-- إرشادات عامة للرفع -->
                                        <div class="upload-guidelines">
                                            <h6>
                                                <div class="icon">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </div>
                                                إرشادات مهمة قبل رفع المستندات
                                            </h6>
                                            <ul class="guideline-list">
                                                <li>
                                                    <i class="fas fa-file-pdf"></i>
                                                    <span><strong>الصيغة المقبولة:</strong> ملفات PDF أو صور (JPG, PNG) فقط</span>
                                                </li>
                                                <li>
                                                    <i class="fas fa-weight-hanging"></i>
                                                    <span><strong>حجم الملف:</strong> يجب ألا يتجاوز 5 ميجابايت لكل ملف</span>
                                                </li>
                                                <li>
                                                    <i class="fas fa-eye"></i>
                                                    <span><strong>وضوح الصورة:</strong> تأكد من أن النص والتفاصيل واضحة ومقروءة</span>
                                                </li>
                                                <li>
                                                    <i class="fas fa-camera"></i>
                                                    <span><strong>جودة التصوير:</strong> استخدم إضاءة جيدة وتجنب الظلال والانعكاسات</span>
                                                </li>
                                                <li>
                                                    <i class="fas fa-crop"></i>
                                                    <span><strong>اقتصاص الصورة:</strong> اقتصص الصورة لتظهر المستند كاملاً بدون أجزاء زائدة</span>
                                                </li>
                                                <li>
                                                    <i class="fas fa-shield-alt"></i>
                                                    <span><strong>سرية البيانات:</strong> تأكد من أن المستندات أصلية وغير معدلة</span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div>
                                            <div class="row">
                                                @foreach($missingFileTypes as $type)
                                                    <div class="col-md-6">
                                                        <div class="file-upload-container">
                                                            <label class="file-label">
                                                                <div class="icon">
                                                                    <i class="fas fa-file-alt"></i>
                                                                </div>
                                                                {{ $type->name }}
                                                                @if($type->is_required)
                                                                    <span class="text-danger">*</span>
                                                                @endif
                                                            </label>
                                                            
                                                            <input type="file"
                                                                   class="filepond"
                                                                   name="filepond_{{ $type->id }}"
                                                                   data-doctor-id="{{ $doctor->id }}"
                                                                   data-file-type-id="{{ $type->id }}"
                                                                   data-required="{{ $type->is_required }}"
                                                                   accept="application/pdf,image/*" />
                                                            
                                                            <div class="file-tips">
                                                                <i class="fas fa-lightbulb"></i>
                                                                <strong>نصائح للرفع:</strong>
                                                                @if(str_contains(strtolower($type->name), 'شهادة') || str_contains(strtolower($type->name), 'دبلوم'))
                                                                    تأكد من وضوح اسمك والدرجة العلمية وتاريخ التخرج
                                                                @elseif(str_contains(strtolower($type->name), 'جواز') || str_contains(strtolower($type->name), 'هوية'))
                                                                    تأكد من وضوح الصورة الشخصية والبيانات الأساسية
                                                                @elseif(str_contains(strtolower($type->name), 'صورة'))
                                                                    استخدم خلفية بيضاء وإضاءة واضحة للصورة الشخصية
                                                                @else
                                                                    تأكد من وضوح جميع النصوص والختم الرسمي إن وجد
                                                                @endif
                                                            </div>
                                                            
                                                            <div class="upload-status" id="status-{{ $type->id }}"></div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- قسم الإنهاء -->
                                        <div class="finish-section">
                                            <h6>
                                                <i class="fas fa-check-circle me-2"></i>
                                                إنهاء عملية الرفع
                                            </h6>
                                            
                                            <div class="progress-info">
                                                <div class="stat">
                                                    <div class="stat-number" id="total-files">{{ count($missingFileTypes) }}</div>
                                                    <div class="stat-label">إجمالي الملفات</div>
                                                </div>
                                                <div class="stat">
                                                    <div class="stat-number" id="uploaded-files">0</div>
                                                    <div class="stat-label">تم رفعها</div>
                                                </div>
                                                <div class="stat">
                                                    <div class="stat-number" id="required-files">{{ $missingFileTypes->where('is_required', true)->count() }}</div>
                                                    <div class="stat-label">ملفات مطلوبة</div>
                                                </div>
                                            </div>
                                            
                                            <div id="upload-message" class="upload-status warning">
                                                <i class="fas fa-clock me-2"></i>
                                                يرجى رفع جميع الملفات المطلوبة قبل الإنهاء
                                            </div>
                                            
                                            <button id="finish-upload" class="btn btn-success btn-lg px-5" disabled>
                                                <i class="fas fa-paper-plane me-2"></i>
                                                إنهاء وإرسال الطلب
                                            </button>
                                            
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    بعد النقر على "إنهاء وإرسال" سيتم مراجعة طلبك من قبل الإدارة
                                                </small>
                                            </div>
                                        </div>

                                        <form action="{{ route('logout') }}" method="GET" class="mt-3 text-center">
                                            @csrf
                                            <button type="submit" class="btn btn-link text-muted">
                                                <i class="fas fa-sign-out-alt me-1"></i>
                                                تسجيل الخروج
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->
        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0">&copy; <script>document.write(new Date().getFullYear())</script> النقابة العامة للاطباء - ليبيا. جميع الحقوق محفوظة.</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>

    <!-- FilePond JS -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script>
        FilePond.registerPlugin(
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType,
            FilePondPluginImagePreview
        );

        const ponds = [];
        const uploadStats = {
            total: {{ count($missingFileTypes) }},
            uploaded: 0,
            required: {{ $missingFileTypes->where('is_required', true)->count() }},
            requiredUploaded: 0
        };

        document.querySelectorAll('.filepond').forEach(input => {
            const fileTypeId = input.dataset.fileTypeId;
            const isRequired = input.dataset.required === '1';
            
            const pond = FilePond.create(input, {
                server: {
                    process: {
                        url: '{{ route("doctor.filepond.process") }}',
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        onload: (response) => {
                            uploadStats.uploaded++;
                            if (isRequired) uploadStats.requiredUploaded++;
                            updateFileStatus(fileTypeId, 'success', 'تم رفع الملف بنجاح');
                            updateUploadStats();
                            return response;
                        },
                        onerror: (response) => {
                            updateFileStatus(fileTypeId, 'error', 'فشل في رفع الملف، يرجى المحاولة مرة أخرى');
                            return response;
                        },
                        ondata: formData => {
                            formData.append('doctor_id', input.dataset.doctorId);
                            formData.append('file_type_id', fileTypeId);
                            return formData;
                        }
                    },
                    revert: {
                        url: '{{ route("doctor.filepond.revert") }}',
                        method: 'DELETE',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        onload: (response) => {
                            uploadStats.uploaded--;
                            if (isRequired) uploadStats.requiredUploaded--;
                            updateFileStatus(fileTypeId, 'warning', 'تم حذف الملف');
                            updateUploadStats();
                            return response;
                        }
                    }
                },
                allowMultiple: false,
                maxFileSize: '5MB',
                acceptedFileTypes: ['application/pdf', 'image/*'],
                labelIdle: 'اسحب الملف هنا أو <span class="filepond--label-action">تصفح</span>',
                labelFileTypeNotAllowed: 'نوع الملف غير مدعوم',
                labelMaxFileSizeExceeded: 'حجم الملف كبير جداً (الحد الأقصى 5MB)',
                labelFileProcessing: 'جاري الرفع...',
                labelFileProcessingComplete: 'تم الرفع بنجاح',
                labelFileProcessingError: 'خطأ في الرفع'
            });
            
            // Event listeners
            pond.on('addfilestart', () => {
                updateFileStatus(fileTypeId, 'warning', 'جاري تحضير الملف...');
            });
            
            pond.on('processfilestart', () => {
                updateFileStatus(fileTypeId, 'warning', 'جاري رفع الملف...');
            });
            
            ponds.push({ pond, required: isRequired, fileTypeId });
        });

        function updateFileStatus(fileTypeId, type, message) {
            const statusEl = document.getElementById(`status-${fileTypeId}`);
            if (statusEl) {
                statusEl.className = `upload-status ${type}`;
                statusEl.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'clock'} me-2"></i>${message}`;
            }
        }

        function updateUploadStats() {
            document.getElementById('uploaded-files').textContent = uploadStats.uploaded;
            
            const finishBtn = document.getElementById('finish-upload');
            const messageEl = document.getElementById('upload-message');
            
            if (uploadStats.requiredUploaded >= uploadStats.required) {
                finishBtn.disabled = false;
                messageEl.className = 'upload-status success';
                messageEl.innerHTML = '<i class="fas fa-check-circle me-2"></i>تم رفع جميع الملفات المطلوبة! يمكنك الآن إنهاء العملية';
            } else {
                finishBtn.disabled = true;
                const remaining = uploadStats.required - uploadStats.requiredUploaded;
                messageEl.className = 'upload-status warning';
                messageEl.innerHTML = `<i class="fas fa-clock me-2"></i>يتبقى رفع ${remaining} ملف مطلوب`;
            }
        }

        function updateButtonState() {
            const allGood = ponds.every(({ pond, required }) => {
                const files = pond.getFiles();
                const processedFiles = files.filter(file => file.status === FilePond.FileStatus.PROCESSING_COMPLETE);
                return required ? processedFiles.length === 1 : true;
            });
            document.getElementById('finish-upload').disabled = !allGood;
        }

        // Initial check
        setTimeout(() => {
            updateButtonState();
            updateUploadStats();
        }, 100);

        document.getElementById('finish-upload').addEventListener('click', () => {
            if (confirm('هل أنت متأكد من إنهاء عملية رفع المستندات؟ سيتم إرسال طلبك للمراجعة.')) {
                window.location = '{{ route("doctor.registration.complete", $doctor->id) }}';
            }
        });
    </script>
</body>
</html>