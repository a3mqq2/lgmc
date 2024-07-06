// Get a reference to the file input element
const inputElement = document.querySelector('input[type="file"]');
FilePond.registerPlugin(FilePondPluginImagePreview);

FilePond.create(inputElement, {
    server: {
        url: '/config/store-file'+appends,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    },
    labelIdle: '<span class="filepond--label-action">إضغط هنا لإدخال الصور</span>',
    maxFiles: '15',
    maxFileSize: '10MB',
    labelMaxFileSizeExceeded: 'حجم الملف كبير جداً',
    labelMaxFileSize: 'max {filesize}',
    labelFileTypeNotAllowed: 'لا يمكن رفع هذا النوع من الملفات',
    fileValidateTypeLabelExpectedTypes: 'فقط {allTypes}'
})