<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    function __construct(){
        $this->middleware(function($request, $next){
            session(['module_active'=>'user']);
            return $next($request);
        });
    }

    // Danh sách users
    function list(Request $request)
    {
        $list_act = [
            'delete' => 'Xóa tạm thời',
        ];

        if ($request->input('status') == 'trash') {
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn',
            ];
            $users = User::onlyTrashed()->paginate(15);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
                $users = User::where('name', 'LIKE', "%{$keyword}%")->orwhere('email', 'LIKE', "%{$keyword}%")->orwhere('id', 'LIKE', "%{$keyword}%")->paginate(15);
            } else {
                $users = User::paginate(15);
            }
        }

        // Lấy thông số thống kê
        $activeUser = User::count();
        $trashUser = User::onlyTrashed()->count();
        $count = [$activeUser, $trashUser];
        return view('admin.users.list', compact('users', 'count', 'list_act'));
    }

    // Thêm user
    function add()
    {
        return view('admin.users.add');
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'password_confirmation' => ['required', 'string', 'min:8'],
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => ':attribute đã được sử dụng vui lòng chọn mới',
                'email' => ':attribute phải nhập đúng định dạng',
                'confirmed' => ':attribute Không trùng khớp',
                'min' => ':attribute ít nhất 8 ký tự',
            ],
            [
                'name' => 'Tên người dùng',
                'password' => 'Mật khẩu',
                'email' => 'Email',
                'password_confirmation' => 'Mật khẩu xác nhận',
            ],
        );

        User::create([
            'name' => $request->input('name'),
            'email' =>  $request->input('email'),
            'password' => Hash::make($request->input('name')),
        ]);

        return redirect('admin/user/list')->with('status', 'Thêm mới thành viên thành công');
    }

    // Thêm user vào thùng rác
    function delete($id)
    {
        if (Auth::id() != $id) {
            $user = User::find($id);
            $user->delete();
            return redirect('admin/user/list')->with('status', 'Xóa thành công');
        } else {
            return redirect('admin/user/list')->with('status', 'Không thể xóa chính bạn');
        }
    }

    // Xử lí theo checkbox
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if ($list_check) {
            foreach ($list_check as $key => $id) {
                if (Auth::id() == $id) {
                    unset($list_check[$key]);
                }
            }

            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act != 0) {
                    if ($act == 'delete') {
                        User::destroy($list_check);
                        return redirect('admin/user/list')->with('status', 'Xóa thành công');
                    }

                    if ($act == 'restore') {
                        User::withTrashed()->whereIn('id', $list_check)->restore();
                        return redirect('admin/user/list')->with('status', 'Khôi phục thành công');
                    }

                    if ($act == 'forceDelete') {
                        User::withTrashed()->whereIn('id', $list_check)->forceDelete();
                        return redirect('admin/user/list')->with('status', 'Đã xóa vĩnh viễn thành viên ra khỏi hệ thống');
                    }
                } else {
                    return redirect('admin/user/list')->with('status', 'Vui lòng chọn tác vụ cụ thể');
                }
            } else {
                return redirect('admin/user/list')->with('status', 'Không thể thao tác trên tài khoản của bạn');
            }
        } else {
            return redirect('admin/user/list')->with('status', 'Không có phần tử được thực thi');
        }
    }

    // Edit
    function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.edit', compact('user'));
    }

    function update(Request $request, $id)
    {
       
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'password_confirmation' => ['required', 'string', 'min:8'],
            ],
            [
                'required' => ':attribute không được để trống',
                'confirmed' => ':attribute Không trùng khớp',
                'min' => ':attribute ít nhất 8 ký tự',
            ],
            [
                'name' => 'Tên người dùng',
                'password' => 'Mật khẩu',
                'password_confirmation' => 'Mật khẩu xác nhận',
            ],
        );

        User::find($id)->update([
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect('admin/user/list')->with('status', 'Cập nhật thành công');
    }

    /**
     * Khôi phục User
     * 
     */
    function restore($id){
        User::onlyTrashed()->find($id)->restore();
        return redirect()->route('admin.user.list', ['status' => 'trash'])->with('status', 'Khôi phục thành công');
    }

    /**
     * Xóa vĩnh viễn User ra khỏi hệ thống
     * 
     */
    function forceDelete($id){
        User::onlyTrashed()->find($id)->forceDelete();
        return redirect()->route('admin.user.list', ['status' => 'trash'])->with('status', 'Đã xóa vĩnh viễn User ra khỏi hệ thống');
    }
}
