@extends('layouts.admin')
@section('title', 'عرض الموظفين')

@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-light">
                    <h5 class="card-title text-light">قائمة الموظفين</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <form action="{{ route(get_area_name().'.users.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="name" placeholder="البحث بالاسم" value="{{ request('name') }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="phone" placeholder="البحث بالهاتف" value="{{ request('phone') }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="passport_number" placeholder="البحث برقم الجواز" value="{{ request('passport_number') }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="ID_number" placeholder="البحث برقم الهوية" value="{{ request('ID_number') }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <select class="form-select" name="active">
                                        <option value="" selected disabled>التفعيل</option>
                                        <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>مفعل</option>
                                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>غير مفعل</option>
                                    </select>
                                </div>
                                <!-- Add more filters as needed -->
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-light" scope="col">#</th>
                                    <th class="bg-light" scope="col">الاسم</th>
                                    <th class="bg-light" scope="col">البريد الإلكتروني</th>
                                    <th class="bg-light" scope="col">الهاتف</th>
                                    <th class="bg-light" scope="col">تاريخ الإنشاء</th>
                                    <th class="bg-light" scope="col">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>
                                          <!-- Edit button -->
                                          <a href="{{ route(get_area_name().'.users.edit', $user->id) }}" class="btn btn-primary btn-sm">تعديل</a>
                                          <!-- Show button -->
                                          <a href="{{ route(get_area_name().'.users.show', $user->id) }}" class="btn btn-info btn-sm">عرض</a>
                                          <!-- Enable/disable button -->
                                          @if($user->active)
                                              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#disableModal{{ $user->id }}">تعطيل</button>
                                          @else
                                              <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#enableModal{{ $user->id }}">تفعيل</button>
                                          @endif
                                          <!-- Delete button -->
                                          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">حذف</button>
                                          <!-- Modal for enable -->
                                          <div class="modal fade" id="enableModal{{ $user->id }}" tabindex="-1" aria-labelledby="enableModalLabel{{ $user->id }}" aria-hidden="true">
                                              <div class="modal-dialog">
                                                  <div class="modal-content">
                                                      <div class="modal-header">
                                                          <h5 class="modal-title" id="enableModalLabel{{ $user->id }}">تفعيل الموظف</h5>
                                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                      </div>
                                                      <div class="modal-body">
                                                          هل أنت متأكد من تفعيل هذا الموظف؟
                                                      </div>
                                                      <div class="modal-footer">
                                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                          <a href="{{ route(get_area_name().'.users.change-status', $user->id) }}" class="btn btn-success">تفعيل</a>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          <!-- Modal for disable -->
                                          <div class="modal fade" id="disableModal{{ $user->id }}" tabindex="-1" aria-labelledby="disableModalLabel{{ $user->id }}" aria-hidden="true">
                                              <div class="modal-dialog">
                                                  <div class="modal-content">
                                                      <div class="modal-header">
                                                          <h5 class="modal-title" id="disableModalLabel{{ $user->id }}">تعطيل الموظف</h5>
                                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                      </div>
                                                      <div class="modal-body">
                                                          هل أنت متأكد من تعطيل هذا الموظف؟
                                                      </div>
                                                      <div class="modal-footer">
                                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                          <a href="{{ route(get_area_name().'.users.change-status', $user->id) }}" class="btn btn-warning">تعطيل</a>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          <!-- Modal for delete -->
                                          <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                              <div class="modal-dialog">
                                                  <div class="modal-content">
                                                      <div class="modal-header">
                                                          <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">حذف الموظف</h5>
                                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                      </div>
                                                      <div class="modal-body">
                                                          هل أنت متأكد من حذف هذا الموظف؟
                                                      </div>
                                                      <div class="modal-footer">
                                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                          <form action="{{ route(get_area_name().'.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                              @csrf
                                                              @method('DELETE')
                                                              <button type="submit" class="btn btn-danger">حذف</button>
                                                          </form>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
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

@endsection
