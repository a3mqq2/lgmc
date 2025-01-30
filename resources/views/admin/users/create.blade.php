@extends('layouts.' . get_area_name())

@section('title', 'إنشاء مستخدم جديد')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">إنشاء مستخدم جديد</h4>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name() . '.users.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        {{-- Name --}}
                        <div class="col-md-4 mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required
                            >
                            @error('name')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required
                            >
                            @error('email')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="col-md-4 mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                required
                            >
                            @error('password')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="col-md-4 mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required
                            >
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-4 mb-3">
                            <label for="phone" class="form-label">الهاتف</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="phone" 
                                maxlength="10" 
                                name="phone" 
                                value="{{ old('phone') }}"
                            >
                        </div>

                        {{-- Secondary Phone --}}
                        <div class="col-md-4 mb-3">
                            <label for="phone2" class="form-label">الهاتف الثاني</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="phone2" 
                                maxlength="10" 
                                name="phone2" 
                                value="{{ old('phone2') }}"
                            >
                        </div>

                        {{-- Passport Number --}}
                        <div class="col-md-4 mb-3">
                            <label for="passport_number" class="form-label">رقم الجواز</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="passport_number" 
                                name="passport_number" 
                                pattern="[A-Z0-9]+" 
                                value="{{ old('passport_number') }}"
                            >
                        </div>

                        {{-- National ID Number --}}
                        <div class="col-md-4 mb-3">
                            <label for="ID_number" class="form-label">الرقم الوطني</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="ID_number" 
                                name="ID_number" 
                                value="{{ old('ID_number') }}"
                            >
                        </div>

                        {{-- Branches (Multiple) --}}
                        <div class="col-md-4 mb-3">
                            <label for="branches" class="form-label">اختر الفروع</label>
                            <select 
                                class="select2 form-control" 
                                id="branches" 
                                name="branches[]" 
                                multiple
                            >
                                @foreach ($branches as $branch)
                                    <option 
                                        value="{{ $branch->id }}"
                                        {{ (collect(old('branches'))->contains($branch->id)) ? 'selected' : '' }}
                                    >
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>{{-- End Row --}}

                    <hr>

                    {{-- Roles & Permissions Table --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-light">
                                    <h4 class="card-title">الأدوار والصلاحيات</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>الدور </th>
                                                <th>الصلاحيات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($roles as $index => $role)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        {{-- Role-level checkbox --}}
                                                        <div class="form-check">
                                                            <input 
                                                                class="form-check-input role-checkbox" 
                                                                type="checkbox" 
                                                                name="roles[]" 
                                                                id="role-{{ $role->id }}" 
                                                                value="{{ $role->name }}"
                                                                data-role-id="{{ $role->id }}" 
                                                                {{-- old('roles') handling --}}
                                                                {{ (collect(old('roles'))->contains($role->name)) ? 'checked' : '' }}
                                                            >
                                                            <label class="form-check-label" for="role-{{ $role->id }}">
                                                                <strong>{{ $role->display_name }}</strong>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($role->permissions->count() > 0)
                                                            {{-- Permissions container, hidden by default --}}
                                                            <div 
                                                                id="permissions-{{ $role->id }}" 
                                                                style="display: none; margin-top: 10px;"
                                                            >
                                                                <ul class="mb-0" style="list-style: none; padding-left: 0;">
                                                                    @foreach($role->permissions as $permission)
                                                                        <li class="mb-2">
                                                                            <div class="form-check">
                                                                                <input 
                                                                                    class="form-check-input" 
                                                                                    type="checkbox" 
                                                                                    name="permissions[]" 
                                                                                    id="perm-{{$role->id}}-{{ $permission->id }}" 
                                                                                    value="{{ $permission->name }}"
                                                                                    {{-- old('permissions') handling --}}
                                                                                    {{ (collect(old('permissions'))->contains($permission->name)) ? 'checked' : '' }}
                                                                                >
                                                                                <label class="form-check-label" for="perm-{{$role->id}}-{{ $permission->id }}">
                                                                                    <strong>{{ $permission->display_name }}</strong> 
                                                                                </label>
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @else
                                                            <span class="text-danger">لا توجد صلاحيات لهذا الدور.</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">
                                                        لا توجد أدوار متاحة.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-3">إنشاء</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // On page load, toggle all role's permissions blocks according to whether the role is checked
    document.addEventListener('DOMContentLoaded', function () {
        const roleCheckboxes = document.querySelectorAll('.role-checkbox');
        roleCheckboxes.forEach(checkbox => {
            togglePermissions(checkbox);
            checkbox.addEventListener('change', function() {
                togglePermissions(this);
            });
        });
    });

    function togglePermissions(roleCheckbox) {
        // get role ID from data attribute
        const roleId = roleCheckbox.dataset.roleId;
        const permBlock = document.getElementById('permissions-' + roleId);

        if (!permBlock) return; // if there's no permission block for this role, do nothing

        if (roleCheckbox.checked) {
            // Show the permission block
            permBlock.style.display = 'block';
        } else {
            // Hide the permission block
            permBlock.style.display = 'none';

            // Optionally uncheck all permission checkboxes within this block
            const permInputs = permBlock.querySelectorAll('input[type="checkbox"]');
            permInputs.forEach(input => {
                input.checked = false;
            });
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Input fields
        const phoneInput = document.getElementById('phone');
        const nationalIdInput = document.getElementById('ID_number');
        const emailInput = document.getElementById('email');

        // Validation messages
        const phoneMessage = document.createElement('small');
        phoneMessage.style.color = 'red';
        phoneInput.parentNode.appendChild(phoneMessage);

        const nationalIdMessage = document.createElement('small');
        nationalIdMessage.style.color = 'red';
        nationalIdInput.parentNode.appendChild(nationalIdMessage);

        const emailMessage = document.createElement('small');
        emailMessage.style.color = 'red';
        emailInput.parentNode.appendChild(emailMessage);

        // Phone validation (Libyan phone numbers)
        phoneInput.addEventListener('input', function () {
            const phoneRegex = /^(091|092|093|095)\d{7}$/; // Starts with 091, 092, 093, or 095 and 7 digits
            if (!phoneRegex.test(this.value)) {
                phoneMessage.textContent = "رقم الهاتف يجب أن يبدأ بـ 091 أو 092 أو 093 أو 095 ويتكون من 10 أرقام.";
                this.classList.add('is-invalid');
            } else {
                phoneMessage.textContent = "";
                this.classList.remove('is-invalid');
            }
        });

        // National ID validation (12 digits starting with 1 or 2)
        nationalIdInput.addEventListener('input', function () {
            const nationalIdRegex = /^(1|2)\d{11}$/; // Starts with 1 or 2 and followed by 11 digits
            if (!nationalIdRegex.test(this.value)) {
                nationalIdMessage.textContent = "الرقم الوطني يجب أن يبدأ بـ 1 أو 2 ويتكون من 12 رقمًا.";
                this.classList.add('is-invalid');
            } else {
                nationalIdMessage.textContent = "";
                this.classList.remove('is-invalid');
            }
        });

        // Email validation (valid email format)
        emailInput.addEventListener('input', function () {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Standard email validation regex
            if (!emailRegex.test(this.value)) {
                emailMessage.textContent = "يرجى إدخال بريد إلكتروني صحيح.";
                this.classList.add('is-invalid');
            } else {
                emailMessage.textContent = "";
                this.classList.remove('is-invalid');
            }
        });
    });
</script>
@endsection
