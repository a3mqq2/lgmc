{{-- resources/views/doctor/medical-facility/upload-documents.blade.php --}}
@extends('layouts.doctor')

@section('styles')
    <!-- FilePond core CSS -->
    <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet" />
    <!-- FilePond preview CSS -->
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet" />
    <style>
        .filepond--root { direction: ltr; }
        .upload-card   { max-width:900px; margin:2rem auto; }
        .file-label    { font-weight:600; margin-bottom:.5rem; display:block }
        #finish-upload:disabled { opacity:.6; cursor:not-allowed }
    </style>
@endsection

@section('content')
<div class="container upload-card">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white text-center">
      <h5 class="mb-0 text-light"><i class="fas fa-building me-1"></i> رفع مستندات الطبيب</h5>
      <small>{{ $doctor->name }} (ID: {{ $doctor->id }})</small>
    </div>
    <div class="card-body">
      @include('layouts.messages')
      <div class="row">
        @foreach($missingFileTypes as $type)
          <div class="col-md-6 mb-4">
            <label class="file-label">
              <i class="far fa-file-alt text-secondary me-1"></i>
              {{ $type->name }}
              @if($type->is_required)<span class="text-danger">*</span>@endif
            </label>

            {{-- ↓ لا تضع form-control، وأضف name --}}
            <input
              type="file"
              class="filepond"
              name="file_{{ $type->id }}"
              data-doctor-id="{{ $doctor->id }}"
              data-file-type-id="{{ $type->id }}"
              data-required="{{ $type->is_required }}"
              accept="application/pdf,image/*"
            />
          </div>
        @endforeach
      </div>
      <div class="text-center mt-3">
        <button id="finish-upload" class="btn btn-success px-5" disabled>
          <i class="fas fa-check-circle me-1"></i> إنهاء وإرسال
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
    <!-- FilePond plugins & core -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
      FilePond.registerPlugin(
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType,
        FilePondPluginImagePreview
      );

      const ponds = [];
      document.querySelectorAll('.filepond').forEach(input => {
        const pond = FilePond.create(input, {
          credits: false,
          labelIdle: 'اسحب الملف أو <span class="filepond--label-action">تصفح</span>',
          allowMultiple: false,
          server: {
            process: {
              url:    '{{ route("doctor.filepond.process") }}',
              method: 'POST',
              headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
              ondata: fd => {
                fd.append('doctor_id', input.dataset.doctorId);
                fd.append('file_type_id',        input.dataset.fileTypeId);
                return fd;
              }
            },
            revert: {
              url:    '{{ route("doctor.filepond.revert") }}',
              method: 'DELETE',
              headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}' }
            }
          }
        });

        pond.on('processfile', updateFinishButton);
        pond.on('removefile',  updateFinishButton);

        ponds.push({
          pond,
          required: input.dataset.required === '1'
        });
      });

      const finishBtn = document.getElementById('finish-upload');
      function updateFinishButton(){
        finishBtn.disabled = !ponds.every(({pond,required}) =>
          required ? pond.getFiles().length === 1 : true
        );
      }

      finishBtn.addEventListener('click', () => {
        window.location.href = '{{ route("doctor.visitor-doctors.complete_registration", $doctor ) }}';
      });
    });
    </script>
@endsection
