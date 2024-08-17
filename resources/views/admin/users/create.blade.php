@extends('layouts.admin')
@section('title', 'إنشاء مستخدم جديد')
@section('content')

<div class="row ">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">إنشاء مستخدم جديد</h4>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name().'.users.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="phone" class="form-label">الهاتف</label>
                            <input type="text" class="form-control" id="phone" maxlength="10" name="phone" value="{{ old('phone') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="phone2" class="form-label">الهاتف الثاني</label>
                            <input type="text" class="form-control" id="phone2" maxlength="10" name="phone2" value="{{ old('phone2') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="passport_number" class="form-label">رقم الجواز</label>
                            <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ old('passport_number') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="ID_number" class="form-label">الرقم الوطني</label>
                            <input type="text" class="form-control" id="ID_number" name="ID_number" value="{{ old('ID_number') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="branches" class="form-label">اختر الفروع</label>
                            <select class="select2" id="branches" name="branches" multiple>
                                @foreach ($branches as $branch)
                                <option value="{{$branch->id}}">{{$branch->name}}</option>
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
                                            data-on-color="success" data-off-color="danger" >
                                                                                     </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    

                    <button type="submit" class="btn btn-primary">إنشاء</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
