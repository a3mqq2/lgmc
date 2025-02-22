@extends('layouts.' . get_area_name())

@section('title', 'قائمة السجلات')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">قائمة السجلات</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route(get_area_name().'.logs') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label>نوع السجل</label>
                                <select name="log_type" class="form-control select2">
                                    <option value="">-- اختر نوع السجل --</option>
                                    @foreach($logTypes as $key => $label)
                                        <option value="{{ $key }}" {{ request('log_type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>المستخدم</label>
                                <select name="user_id" class="form-control">
                                    <option value="">-- جميع المستخدمين --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>الفرع</label>
                                <select name="branch_id" class="form-control">
                                    <option value="">-- جميع الفروع --</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">تصفية</button>
                    </form>
                    

                    <div class="mt-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">المستخدم</th>
                                    <th scope="col">التفاصيل</th>
                                    <th scope="col">تاريخ الإنشاء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <th scope="row">{{ $log->id }}</th>
                                        <td>{{ $log->user->name }}</td>
                                        <td>{{ $log->details }}</td>
                                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
