@extends('layouts.' . get_area_name())

@section('title', 'تغيير كلمة المرور')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">تغيير كلمة المرور</h4>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name().'.profile.change-password') }}" method="POST" onsubmit="return validatePasswords()">
                    @csrf
                    
                    {{-- Current Password --}}
                    <div class="mb-3 position-relative">
                        <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            <span class="input-group-text" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye" id="eye_current_password"></i>
                            </span>
                        </div>
                        @error('current_password')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    
                    {{-- New Password --}}
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">كلمة المرور الجديدة</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            <span class="input-group-text" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="eye_password"></i>
                            </span>
                        </div>
                        @error('password')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    
                    {{-- Confirm New Password --}}
                    <div class="mb-3 position-relative">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <span class="input-group-text" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="eye_password_confirmation"></i>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">تحديث كلمة المرور</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function validatePasswords() {
        let newPassword = document.getElementById('password').value;
        let confirmPassword = document.getElementById('password_confirmation').value;
        if (newPassword !== confirmPassword) {
            alert('كلمة المرور الجديدة وتأكيد كلمة المرور غير متطابقين!');
            return false;
        }
        return true;
    }

    function togglePassword(fieldId) {
        let field = document.getElementById(fieldId);
        let eyeIcon = document.getElementById('eye_' + fieldId);
        if (field.type === "password") {
            field.type = "text";
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            field.type = "password";
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection
