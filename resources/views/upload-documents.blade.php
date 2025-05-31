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
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-12">
                                    <div class="p-lg-5 p-4">
                                        <div class="text-center mb-4">
                                            <h5 class="text-primary">رفع المستندات </h5>
                                            <p class="text-muted">{{ $doctor->name }} (ID: {{ $doctor->id }})</p>
                                        </div>

                                        <div class="mb-4">
                                            @include('layouts.messages')
                                        </div>

                                        <div>
                                             <div class="row">
                                                @foreach($missingFileTypes as $type)
                                                <div class="col-md-6">
                                                   <div class="mb-3">
                                                      <label class="form-label">{{ $type->name }} @if($type->is_required)<span class="text-danger">*</span>@endif</label>
                                                      <input type="file"
                                                             class="filepond"
                                                             name="filepond_{{ $type->id }}"
                                                             data-doctor-id="{{ $doctor->id }}"
                                                             data-file-type-id="{{ $type->id }}"
                                                             data-required="{{ $type->is_required }}"
                                                             accept="application/pdf,image/*" />
                                                  </div>
                                                </div>
                                            @endforeach
                                             </div>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <button id="finish-upload" class="btn btn-success w-100" disabled>إنهاء وارسال</button>
                                        </div>

                                        <form action="{{ route('logout') }}" method="GET" class="mt-3 text-center">
                                          @csrf
                                          <button type="submit" class="btn btn-link">تسجيل الخروج</button>
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
        document.querySelectorAll('.filepond').forEach(input => {
            const pond = FilePond.create(input, {
                server: {
                    process: {
                        url: '{{ route("doctor.filepond.process") }}',
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        ondata: formData => {
                            formData.append('doctor_id', input.dataset.doctorId);
                            formData.append('file_type_id', input.dataset.fileTypeId);
                            return formData;
                        }
                    },
                    revert: {
                        url: '{{ route("doctor.filepond.revert") }}',
                        method: 'DELETE',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                    }
                },
                allowMultiple: false,
                maxFileSize: '5MB',
                acceptedFileTypes: ['application/pdf', 'image/*']
            });
            pond.on('processfile', updateButtonState);
            pond.on('removefile', updateButtonState);
            ponds.push({ pond, required: input.dataset.required === '1' });
        });

        function updateButtonState() {
            const finishBtn = document.getElementById('finish-upload');
            const allGood = ponds.every(({ pond, required }) => {
                const count = pond.getFiles().length;
                return required ? count === 1 : true;
            });
            finishBtn.disabled = !allGood;
        }

        document.getElementById('finish-upload').addEventListener('click', () => {
            window.location = '{{ route("doctor.registration.complete", $doctor->id) }}';
        });
    </script>
</body>
</html>
