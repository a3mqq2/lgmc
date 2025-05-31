@extends('layouts.' . get_area_name())
@section('title', 'قائمة الحسابات')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">قائمة الحسابات</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr class="bg-light">
                                <th scope="col">#</th>
                                <th scope="col">اسم الحساب</th>
                                <th scope="col" class="bg-primary">الرصيد </th>
                                <th scope="col">التحكم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vaults as $index => $vault)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $vault->name }}</td>
                                    <td>{{ $vault->balance }} د.ل </td>
                                    <td>
                                        <a href="{{ route(get_area_name().'.transactions.index', ['vault_id' => $vault->id]) }}" class="btn btn-sm btn-info">عرض <i class="fa fa-eye"></i> </a>
                                        @if (get_area_name() == "admin")
                                            <a href="{{ route(get_area_name().'.vaults.edit', $vault->id) }}" class="btn btn-sm btn-primary">تعديل <i class="fa fa-edit"></i> </a>
                                            <form action="{{ route(get_area_name().'.vaults.destroy', $vault->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الحساب؟')">حذف <i class="fa fa-trash"></i> </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
