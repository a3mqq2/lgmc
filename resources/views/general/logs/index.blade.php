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
                    <form action="{{ route(get_area_name().'.logs') }}" method="GET">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">المستخدم</label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">الكل</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">تطبيق</button>
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
