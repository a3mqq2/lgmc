@extends('layouts.admin')

@section('title', 'عرض بيانات الموظف')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">بيانات الموظف</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="bg-light">الاسم:</td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">البريد الإلكتروني:</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">الهاتف:</td>
                        <td>{{ $user->phone }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">الهاتف الثاني:</td>
                        <td>{{ $user->phone2 }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">رقم الجواز:</td>
                        <td>{{ $user->passport_number }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">الرقم الوطني:</td>
                        <td>{{ $user->ID_number }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">الفروع:</td>
                        <td>
                            @foreach($user->branches as $branch)
                                <span class="badge badge-primary alert-primary">{{ $branch->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-light">الصلاحيات:</td>
                        <td>
                            @foreach($user->permissions as $permission)
                                <span class="badge badge-success alert-success">{{ $permission->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-light">حالة الحساب:</td>
                        <td>
                            @if($user->active)
                                <span class="badge badge-success alert-success">نشط</span>
                            @else
                                <span class="badge badge-danger alert-danger">غير نشط</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table th, .table td {
        border-top: none;
    }
</style>
@endsection
