@extends('layouts.admin')
@section('content')
<div id="content" class="mh-full pt-4">
    <div class="add_user p-16">
        <div class="card">
            <div class="card-header fs-5 fw-semibold">
                Thêm người dùng
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Họ và tên</label>
                                <input type="text" name="name" id="fullname" class="form-control" value="{{ $user->name  }}">
                                @error('name')
                                <span class="text-danger d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email  }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" name="password" id="password" class="form-control">
                                @error('password')
                                <span class="text-danger d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" name="password_confirmation" id="re-password" class="form-control">
                                @error('password_confirmation')
                                <span class="text-danger d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="select_id" class="form-label">Nhóm quyền</label>
                                <select name="" id="select_id" class="form-select">
                                    <option value="">--- Chọn quyền ---</option>
                                    <option value="1">Quyền thứ 1</option>
                                    <option value="2">Quyền thứ 2</option>
                                    <option value="3">Quyền thứ 3</option>
                                </select>
                            </div>
                            <input type="submit" name="submit-form" value="Cập nhật" class="btn btn-primary">
                        </form>
                    </div>
                    <div class="col-6">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection