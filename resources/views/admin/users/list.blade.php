@extends('layouts.admin')
@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="list_post p-16">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header fw-semibold fs-5">
                    DANH SÁCH THÀNH VIÊN
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        @php
                            $status = isset($_GET['status']) ? $_GET['status'] : 'active';
                        @endphp
                        <div class="card-status">
                            <a href="{{ route('admin.user.list', ['status' => 'active']) }}"
                                class="text-decoration-none {{ $status == 'active' ? 'text-warning' : '' }}">Kích
                                hoạt<span>({{ $count[0] }})</span></a>|
                            <a href="{{ route('admin.user.list', ['status' => 'trash']) }}"
                                class="text-decoration-none {{ $status == 'trash' ? 'text-warning' : '' }}">Vô
                                hiệu hóa<span>({{ $count[1] }})</span></a>
                        </div>
                        <div class="form-search">
                            <form action="" method="GET">
                                <div class="d-flex align-items-center">
                                    <div class="form-group">
                                        <input type="text" name="keyword" class="form-control rounded-0"
                                            placeholder="Nhập từ khóa" value="{{ request()->input('keyword') }}">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="submit" class="btn btn-primary rounded-0"
                                            value="Tìm kiếm">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <form action="{{ url('admin/user/action') }}" method="POST">
                        @csrf
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-3">

                                    <div class="d-flex align-items-center">
                                        <div class="form-group">
                                            <select class="form-select rounded-0" aria-label="Default select example"
                                                name="act">
                                                <option value="0">--- Chọn ---</option>
                                                @foreach ($list_act as $key => $act)
                                                    <option value="{{ $key }}">{{ $act }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary rounded-0" value="Áp dụng"
                                                name="submit-form">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-8">
                                    <!-- Trống -->
                                </div>
                            </div>
                        </div>
                        <div class="table_post mt-3">
                            <table class="table table-striped table-checkall">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th scope="col">
                                            <input type="checkbox" name="checkall" id="checkAll"
                                                aria-label="Input-checkAll">
                                        </th>
                                        <th scope="col">STT</th>
                                        <th scope="col">Họ tên</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Quyền</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($users->total() > 0)
                                        @php
                                            $k = 0;
                                        @endphp
                                        @foreach ($users as $user)
                                            @php
                                                $k++;
                                            @endphp
                                            <tr class="vertical-center text-center">
                                                <td>
                                                    <input type="checkbox" name="list_check[]" value="{{ $user->id }}"
                                                        aria-label="Checkitem">
                                                </td>
                                                <td scope="row">{{ $k }}</td>
                                                <td>
                                                    <span>{{ $user->name }}</span>
                                                </td>
                                                <td>
                                                    <span>{{ $user->email }}</span>
                                                </td>
                                                <td>
                                                    <span>Admintrator</span>
                                                </td>
                                                <td>
                                                    {{ $user->created_at }}
                                                </td>
                                                <td>
                                                    @if (request()->input('status') == 'trash')
                                                        <a href="{{ route('admin.user.restore', $user->id) }}" class="btn btn-success rounded-0 border-0"
                                                            type="button" data-toggle="tooltip" data-placement="top"
                                                            title="Khôi Phục User"><i
                                                                class="fa-solid fa-trash-can-arrow-up"></i></a>
                                                        <a href="{{ route('admin.user.forceDelete', $user->id) }}" class="btn btn-danger rounded-0 border-0"
                                                            type="button" data-toggle="tooltip" data-placement="top"
                                                            title="Xóa vĩnh viễn User"
                                                            onclick="confirm('Bạn muốn xóa vĩnh viễn User ra khỏi hệ thống')"><i
                                                                class="fa-solid fa-square-minus"></i></a>
                                                    @else
                                                        <a href="{{ route('admin.user.edit', $user->id) }}"
                                                            class="btn btn-success rounded-0 border-0" type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                                class="text-white fa-solid fa-pen-to-square"></i></a>
                                                        @if (Auth::id() != $user->id)
                                                            <a href="{{ route('admin.user.delete', $user->id) }}"
                                                                class="btn btn-danger rounded-0 border-0" type="button"
                                                                data-toggle="tooltip" data-placement="top" title="Delete"
                                                                onclick="return confirm('Bạn chắc chăn muốn xóa thành viên này')"><i
                                                                    class="text-white fa-solid fa-trash"></i></a>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="bg-white">
                                            <td colspan="7"><span class="text-danger">Không có kết quả tồn tại</span>
                                            </td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
