@extends('layouts.admin')
@section('title', 'تعديل بيانات الموظف')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل بيانات الموظف</h4>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name().'.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $user->email }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="phone" class="form-label">الهاتف</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="phone2" class="form-label">الهاتف الثاني</label>
                            <input type="text" class="form-control" id="phone2" name="phone2" value="{{ $user->phone2 }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="passport_number" class="form-label">رقم الجواز</label>
                            <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ $user->passport_number }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="ID_number" class="form-label">الرقم الوطني</label>
                            <input type="text" class="form-control" id="ID_number" name="ID_number" value="{{ $user->ID_number }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="branches" class="form-label">اختر الفروع</label>
                            <select class="select2" id="branches" name="branches[]" multiple>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ in_array($branch->id, $user->branches->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($permissions as $permission)
                                    <tr>
                                        <td class="bg-light" style="width: 50%">
                                            <label for="" class="form-check-label">{{ $permission->display_name }}</label>
                                        </td>
                                        <td style="width: 50%;">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            data-on-text="نعم" data-off-text="لا"
                                            data-on-color="success" data-off-color="danger"
                                            {{ $user->permissions->where('name', $permission->name)->count() ? "checked" : "" }}>
                                                                                     </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    

                    <button type="submit" class="btn btn-primary">تحديث</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.role-switch').change(function() {
            var isChecked = $(this).is(':checked');
            var role_id = $(this).attr('name').match(/\[(\d+)\]/)[1];

            // Enable/disable all checkboxes in the same row
            $(this).closest('tr').find('.role-switch').prop('disabled', !isChecked);
        });

        // Trigger change event once on page load
        $('.role-switch:checked').trigger('change');
    });
</script>
<script>
    $(document).ready(function() {
        // Initialize Bootstrap Switch
        $('input[type="checkbox"]').bootstrapSwitch({
            size: 'small', // Adjust the size as needed
            onText: 'نعم', // Text when toggled on
            offText: 'لا', // Text when toggled off
            onColor: 'success', // Color when toggled on
            offColor: 'danger' // Color when toggled off
        });
    });
</script>
@endsection
