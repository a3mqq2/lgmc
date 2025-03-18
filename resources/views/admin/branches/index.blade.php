@extends('layouts.'.get_area_name())
@section('title', 'قائمة الفروع')
@section('content')

<div class="">
    <a href="{{route(get_area_name().'.branches.create')}}" class="btn btn-success btn-sm mb-3">اضافه فرع جديد <i class="fa fa-plus mb-2"></i></a>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">قائمة الفروع</h4>
                </div>                                                                                                                                                                       
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الهاتف</th>
                                    <th>المدينة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الاعدادات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branches as $branch)
                                <tr>
                                    <td>{{ $branch->id }}</td>
                                    <td>{{ $branch->name }}</td>
                                    <td>{{ $branch->phone }}</td>
                                    <td>{{ $branch->city }}</td>
                                    <td>{{ $branch->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{route(get_area_name().'.branches.edit', $branch)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                                        <!-- Delete Button -->
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $branch->id }}" data-name="{{ $branch->name }}">حذف</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد أنك تريد حذف هذا الفرع؟</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route(get_area_name().'.branches.destroy', 'branch_id') }}" method="POST" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Set data for the modal when delete button is clicked
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var branchId = button.data('id');
        var branchName = button.data('name');

        var modal = $(this);
        modal.find('#branch-name').text(branchName);
        
        // Update form action URL with the branch ID
        var action = '{{ route(get_area_name().".branches.destroy", ":id") }}';
        action = action.replace(':id', branchId);
        modal.find('#delete-form').attr('action', action);
    });
</script>
@endsection
