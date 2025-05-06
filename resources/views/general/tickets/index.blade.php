@extends('layouts.' . get_area_name())

@section('title', 'قائمة التذاكر')

@section('content')
<div class="row">
    <div class="col-md-12">
        <a href="{{ route(get_area_name() . '.tickets.create') }}" class="btn btn-success mb-2">
            <i class="fa fa-plus"></i> إنشاء تذكرة جديدة
        </a>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h4 class="card-title">قائمة التذاكر</h4>
            </div>
            <div class="card-body">
                <form action="{{ route(get_area_name() . '.tickets.index') }}" method="GET">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="title">عنوان التذكرة</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="عنوان التذكرة" value="{{ request()->input('title') }}">
                        </div>
                        {{-- <div class="col-md-3">
                            <label for="department">القسم</label>
                            <select class="form-control" id="department" name="department">
                                <option value="">اختر القسم</option>
                                @foreach(App\Enums\Department::cases() as $department)
                                    <option value="{{ $department->value }}" {{ request()->input('department') == $department->value ? 'selected' : '' }}>
                                        {{ $department->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col-md-3">
                            <label for="category">الفئة</label>
                            <select class="form-control" id="category" name="category">
                                <option value="">اختر الفئة</option>
                                @foreach(App\Enums\Category::cases() as $category)
                                    <option value="{{ $category->value }}" {{ request()->input('category') == $category->value ? 'selected' : '' }}>
                                        {{ $category->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status">الحالة</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">اختر الحالة</option>
                                @foreach(App\Enums\Status::cases() as $status)
                                    <option value="{{ $status->value }}" {{ request()->input('status') == $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="priority">الأولوية</label>
                            <select class="form-control" id="priority" name="priority">
                                <option value="">اختر الأولوية</option>
                                @foreach(App\Enums\Priority::cases() as $priority)
                                    <option value="{{ $priority->value }}" {{ request()->input('priority') == $priority->value ? 'selected' : '' }}>
                                        {{ $priority->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="created_at">تاريخ الإنشاء</label>
                            <input type="date" class="form-control" id="created_at" name="created_at" value="{{ request()->input('created_at') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="closed_at">تاريخ الإغلاق</label>
                            <input type="date" class="form-control" id="closed_at" name="closed_at" value="{{ request()->input('closed_at') }}">
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block w-100">بحث</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="bg-light">#</th>
                                <th class="bg-light">كود التذكرة</th>
                                <th class="bg-light">الإقامة</th>
                                <th class="bg-light">القسم</th>
                                <th class="bg-light">الفئة</th>
                                <th class="bg-light">الحالة</th>
                                <th class="bg-light">الأولوية</th>
                                <th class="bg-light">تاريخ الإنشاء</th>
                                <th class="bg-light">تاريخ الإغلاق</th>
                                <th class="bg-light">المغلق بواسطة</th>
                                <th class="bg-light">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tickets as $ticket)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $ticket->slug }}</td>
                                    <td>{{ $ticket->title }}</td>
                                    <td>
                                        <span class="badge {{ $ticket->department->badgeClass() }}">
                                            {{ $ticket->department->label() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $ticket->category->badgeClass() }}">
                                            {{ $ticket->category->label() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $ticket->status->badgeClass() }}">
                                            {{ $ticket->status->label() }}
                                        </span>
                                    </td>
                                    {{-- Priority cell with a small colored icon based on priority --}}
                                    <td>
                                        <span class="badge d-inline-flex align-items-center gap-1 {{ $ticket->priority->badgeClass()}}">
                                            @switch($ticket->priority->value)
                                                @case('low')
                                                    <i class="fa fa-circle text-success"></i>
                                                    @break
                                                @case('medium')
                                                    <i class="fa fa-circle text-warning"></i>
                                                    @break
                                                @case('high')
                                                    <i class="fa fa-circle text-danger"></i>
                                                    @break
                                            @endswitch
                                            {{ $ticket->priority->label() }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        {{ $ticket->closed_at ? $ticket->closed_at->format('Y-m-d') : 'لم يُغلق بعد' }}
                                    </td>
                                    <td>
                                        @if($ticket->closedBy)
                                            {{ $ticket->closedBy->name }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route(get_area_name() . '.tickets.show', $ticket) }}" class="btn btn-primary btn-sm text-light">
                                            عرض <i class="fa fa-eye"></i>
                                        </a>
                                         
                                        <button type="button" class="btn btn-danger btn-sm text-light" data-bs-toggle="modal" data-bs-target="#deleteModal" data-ticket-id="{{ $ticket->id }}">
                                            حذف <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">لا توجد تذاكر متاحة.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $tickets->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد أنك تريد حذف هذه التذكرة؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">حذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var ticketId = button.getAttribute('data-ticket-id');
        var action = "{{ url(get_area_name() . '/tickets') }}/" + ticketId;
        var deleteForm = document.getElementById('deleteForm');
        deleteForm.setAttribute('action', action);
    });
</script>
@endsection
